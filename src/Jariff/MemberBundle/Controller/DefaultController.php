<?php

namespace Jariff\MemberBundle\Controller;

use Elasticsearch\Endpoints\Cat\Aliases;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\MemberBundle\Entity\MemberSetting;
use Jariff\MemberBundle\Entity\MemberNotify;
use Jariff\MemberBundle\Entity\Member;


class DefaultController extends BaseController
{
    /**
     * @Route("/member/", name="dashboard_member")
     * @ Route("/member/{_locale}/", name="dashboard_member", requirements = { "_locale" : "cn|fr|es" })
     * @Template("JariffMemberBundle:Default:index.html.twig")
     */
    public function indexAction()
    {

        $checkNotify = $this->repo("JariffMemberBundle:MemberNotify")->findOneBy(array("member" => $this->user()));

        $showSetting = false;

        if (!$checkNotify) {
            $showSetting = true;
        } else {

            if ($checkNotify->getReaded()) {
                $showSetting = false;
            } else {
                if ($checkNotify->getReadLater()) {
                    $today = new \DateTime('now');

                    $diff = abs(strtotime($today->format("Y-m-d H:i:s")) - strtotime($checkNotify->getLastUpdate()->format("Y-m-d H:i:s")));
                    $maxTime = $diff / 3600;
                    if ($maxTime > 48) {

                        $showSetting = true;
                    }
                }
            }
        }


        $user = $this->user();
        $cookies = $this->container->get('request')->cookies;
        // phpsessid disimpan agar kalau login dari browser yg lain, 
        // phpsessid yg ini dihapus agar hanya satu unique login yg boleh
        $user->setSessionId($cookies->get('PHPSESSID'));
        $this->flush();


        $query = $this->repo('JariffMemberBundle:MemberActivityFeed')->findBy(array(
                'member' => $this->getUser()->getId()
            ),
            array(
                "date_activity" => "DESC"
            )
        );

        $isActive = $user->getStatus() == Member::ACTIVE;

        return array(
            "showSetting" => $showSetting,
            'data' => $this->paginate($query, 1, 10),
            "isActive" => $isActive,

        );
    }

    /**
     * @Route("/member/change-lang/{_locale}/", name="member_change_locale", requirements = { "_locale" : "en|cn|fr|es" })
     */
    public function memberChangeLangAction($_locale)
    {
        $setting = $this->user()->getSetting();
        if (is_null($setting)) {
            $setting = new MemberSetting();
            $setting->setMember($this->user());
            $this->persist($setting);
        }

        $setting->setLanguage(strtoupper($_locale));
        $this->flush();

        return $this->changeLangAction($_locale);
    }

    /**
     * @Route("/change-lang/{_locale}/", name="change_locale", requirements = { "_locale" : "en|cn|fr|es" })
     */
    public function changeLangAction($_locale)
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set("_locale", $_locale);
        $request->setLocale($_locale);
        $response = new RedirectResponse($this->getRequest()->headers->get('referer'));
        return $response;
    }

    /**
     * @Route("/member/change-notify/{read}/{read_later}", name="change_notify")
     */
    public function changeNotifyAction($read, $read_later)
    {
        $checkNotify = $this->repo("JariffMemberBundle:MemberNotify")->findOneBy(array("member" => $this->user()->getId()));

        if ($checkNotify) {
            $checkNotify->setReaded($read);
            $checkNotify->setReadLater($read_later);
            $checkNotify->setLastUPdate(new \DateTime('now'));
            $this->persist($checkNotify);
            $this->flush();

        } else {
            $newNotify = new MemberNotify();
            $newNotify->setMember($this->user());
            $newNotify->setCategory("noticed dashboard");
            $newNotify->setIntervals(48);
            $newNotify->setReaded($read);
            $newNotify->setReadLater($read_later);
            $this->persist($newNotify);
            $this->flush();


        }

        if (!$read_later) {
            $response = $this->redirectUrl('membersetting');
        } else {
            $response = $this->redirectUrl('dashboard_member');
        }

        return $response;
    }
}

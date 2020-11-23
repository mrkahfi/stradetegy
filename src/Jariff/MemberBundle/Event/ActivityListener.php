<?php

namespace Jariff\MemberBundle\Event;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Jariff\DocumentBundle\Document\Activity;
use Jariff\MemberBundle\Entity\Member;
use Jariff\AdminBundle\Entity\Admin;

class ActivityListener
{
    private $sc;

    private $dm;

    public function setDm($dm)
    {
      $this->dm = $dm->getManager();
    }

    public function setSc($sc)
    {
      $this->sc = $sc;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        // menggenerate partial html content ikut mentrigger event ini juga,
        // di filter disini
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        // ada wilayah yg tidak memiliki firewall
        // yaitu root website, yg ada itu admin, member, dan cso
        $token   = $this->sc->getToken();
        $request = $event->getRequest();
        if (!is_null($token) && ($user = $token->getUser()) != 'anon.') {
            // di wilayah yg tidak berfirewall user nya == anon.
            $activity = new Activity();
            $activity->setUserId($user->getId());
            $activity->setGet($request->getRequestUri());
            $activity->setPost((empty($request->request->all()) ? '---' : serialize($request->request->all())));
            $activity->setIp($request->getClientIp());
            $activity->setUserAgent($request->server->get('HTTP_USER_AGENT'));
            if ($user instanceOf Member) {
                $activity->setRole('member');
            } elseif ($user instanceOf Admin) {
                $activity->setRole('admin');
            }


            $this->dm->persist($activity);
            $this->dm->flush();
            return;
        }

        // untuk halaman yg tidak ada user logged in nya,
        // set visited page di session untuk tracking guest user di pending controller
        $session  = $request->getSession();

        // semua landing pages direkam
        $uri = $request->getRequestUri();
        if (preg_match('/\.js$/', $uri) or 
            preg_match('/\.css$/', $uri) or 
            preg_match('/\.jpg$/', $uri) or 
            preg_match('/record-ajax$/', $uri)) {
            return;
        }

        $session->set('visitedPage', $session->get('visitedPage').'<br/>'.PHP_EOL.$request->getRequestUri());
    }
}
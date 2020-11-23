<?php

namespace Jariff\MemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\MemberBundle\Entity\MemberEmail;
use Jariff\MemberBundle\Entity\MemberEmailHistory;
use Jariff\AdminBundle\Entity\Inbound;

/**
 * MemberEmail controller.
 */
class MemberEmailController extends BaseController
{
    /**
     * @Route(
     *     "/public/url/{token}", 
     *     name="public_url_click"
     * )
     */
    public function clickAction(Request $request, $token)
    {
        $entity = $this->repo('JariffMemberBundle:MemberEmailAlias')->findOneByToken($token);
        $memberEmail = $entity->getMemberEmail();

        if ($entity) {
            // update view log nya yg langsung di memberEmail
            $memberEmail->setViewIpAddress($request->getClientIp());
            $memberEmail->setViewOs($request->server->getHeaders()['USER_AGENT']);
            $memberEmail->setViewDate(new \DateTime);

            // buat history nya
            $history = new MemberEmailHistory();
            $history->setViewIpAddress($request->getClientIp());
            $history->setViewOs($request->server->getHeaders()['USER_AGENT']);
            $history->setMemberEmail($memberEmail);
            $this->em()->persist($history);
            
            // buat inbound
            $member  = $memberEmail->getMember();
            $inbound = new Inbound();
            $inbound->setEmail($member->getEmail());
            $inbound->setSource('special_marketing');
            $inbound->setDescription('Member #'.$member->getNumber().' click url '.$entity->getUrl());
            $inbound->setMember($member);
            $this->em()->persist($inbound);

            $this->em()->flush();
            return $this->redirect($entity->getUrl());
        }
        
        // jika tidak ada url alias yg valid,
        // maka redirect ke website utama
        return $this->redirectUrl('dashboard');
    }

    /**
     * @Route(
     *     "/public/image/{token}/bg.jpg", 
     *     name="public_image_show"
     * )
     */
    public function showAction(Request $request, $token)
    {
        $entity = $this->repo('JariffMemberBundle:MemberEmail')->findOneByToken($token);

        if ($entity) {
            // update view log nya yg langsung di memberEmail
            $entity->setViewIpAddress($request->getClientIp());
            $entity->setViewOs($request->server->getHeaders()['USER_AGENT']);
            $entity->setViewDate(new \DateTime);

            // buat history nya
            $history = new MemberEmailHistory();
            $history->setViewIpAddress($request->getClientIp());
            $history->setViewOs($request->server->getHeaders()['USER_AGENT']);
            $history->setMemberEmail($entity);
            $this->em()->persist($history);
            
            // buat inbound
            $member  = $entity->getMember();
            $inbound = new Inbound();
            $inbound->setEmail($member->getEmail());
            // $inbound->setPhone($contact->getPhone());
            // $inbound->setBusiness($lead->getBusiness());
            // $inbound->setName($contact->getFirstName().' '.$contact->getLastName());
            $inbound->setSource('special_marketing');
            $inbound->setDescription('Member #'.$member->getNumber().' view the email #'.$entity->getId());
            $inbound->setMember($member);
            $this->em()->persist($inbound);

            $this->em()->flush();
        }
        
        $this->createImage();
        
    }

    private function createImage(){
        
        $width  = 10;
        $height = 10;
        
        $image  = ImageCreate($width, $height);    
        $white  = ImageColorAllocate($image, 255, 255, 255);
        
        ImageFill($image, 0, 0, $white);
        
        header("Content-Type: image/jpeg");
    
        ImageJpeg($image);
        
        ImageDestroy($image);
        
        exit();
        
    }
}

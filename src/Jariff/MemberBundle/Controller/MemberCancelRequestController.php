<?php

namespace Jariff\MemberBundle\Controller;

use Jariff\MemberBundle\Form\MemberCancelRequestType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\MemberBundle\Entity\MemberCancelRequest;
use Jariff\AdminBundle\Entity\Inbound;


/**
 * MemberEmail controller.
 */
class MemberCancelRequestController extends BaseController
{
    /**
     * @Route(
     *     "/member/cancel-request",
     *     name="member_cancel_request"
     * )
     *
     * @Template("JariffMemberBundle:MemberCancelRequest:index.html.twig")
     */
    public function index()
    {
        $entity = new MemberCancelRequest();

        $form = $this->createForm(new MemberCancelRequestType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(
     *     "/member/cancel-request-submit",
     *     name="member_cancel_request_submit"
     * )
     *
     * @Method("POST")
     *
     * @Template("JariffMemberBundle:MemberCancelRequest:index.html.twig")
     */
    public function submit(Request $request)
    {
        $entity = new MemberCancelRequest();

        $form = $this->createForm(new MemberCancelRequestType(), $entity, array(
            'action' => '',
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {

            $dataReason = $request->get('jariff_memberbundle_membercancelrequest')['reason'];

            $reasonChoice = '';
            $i = 1;
            foreach($dataReason as $dr){

                if ($dr == 'other') {
                    $other = $request->get('jariff_memberbundle_membercancelrequest')['other'];
                    $reasonChoice .= $i .'. '.$other.'<br/>';
//                    if (!empty($other))
//                        $entity->setReason($other);
//                    else
//                        return $this->errorRedirect('Other reason cannot empty', 'member_cancel_request');

                }else{
                    $reasonChoice .= $i .'. '.$dr.'<br/>';
                }
                $i++;
            }

            $entityInbound = new Inbound();

            $entityInbound->setEmail($this->getUser()->getEmail());
            $entityInbound->setName($this->getUser()->getName());
            $entityInbound->setMember($this->getUser());
            $entityInbound->setDescription('Subject : A user has requested cancel<br>Message : '.$reasonChoice);
            $entityInbound->setSource('User Cancelling Request');
            $this->persist($entityInbound);

            $this->flush();

            $entity->setMember($this->getUser());
            $entity->setMemberSubscription($this->user()->getLastSubscription());
            $entity->setReason($reasonChoice);
            $this->persist($entity);
            $this->flush();

            $user = $this->getUser();

            $dataMember = array(
                'subject' => "A user has requested cancel",
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'message' => 'A current user has requested their account be canceled. Please check Inbound for request, attach to Activities in client record and process.'
            );

            $templating = $this->get('templating');
            $mailer     = $this->get('mailer');
            $message = \Swift_Message::newInstance()
                ->setSubject("Request Cancelling From Member ".$user->getName()." ".$user->getEmail())
                ->setFrom('no-reply@stradetegy.com')
                ->setTo("info@stradetegy.com")
//                    ->setCc('sales@stradetegy.com')
//                    ->setBcc('ardianys@outlook.com')
                ->setBody($templating->render('JariffMemberBundle:Email:ticket.txt.twig', $dataMember))
                ->addPart($this->renderView('JariffMemberBundle:Email:ticket.html.twig', $dataMember), 'text/html')
            ;
            $mailer->send($message);

            $templating = $this->get('templating');
            $mailer     = $this->get('mailer');
            $message = \Swift_Message::newInstance()
                ->setSubject("Your cancellation request")
                ->setFrom('info@stradetegy.com')
                ->setTo($user->getEmail())
//                    ->setCc('sales@stradetegy.com')
//                    ->setBcc('ardianys@outlook.com')
                ->setBody($templating->render('JariffMemberBundle:Email:user_cancel.txt.twig', $dataMember))
             ->addPart($this->renderView('JariffMemberBundle:Email:user_cancel.html.twig', $dataMember), 'text/html')
             ;
            $mailer->send($message);

            return $this->successRedirect('Your submit has been sent', 'member_cancel_request');

        }

        return array(
            'form' => $form->createView()
        );
    }
}

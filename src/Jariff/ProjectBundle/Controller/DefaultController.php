<?php

namespace Jariff\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Jariff\AdminBundle\Entity\Inbound;

use Jariff\ProjectBundle\Form\SearchFormType;

class DefaultController extends BaseController
{
    /**
     * @Route(
     *     "/{_locale}",
     *     name="dashboard",
     *     defaults = { "_locale" : "en" },
     *     requirements = { "_locale" : "en|es|cn|fr" }
     * )
     * @Template("JariffProjectBundle:Default:index2.html.twig")
     */
    public function indexAction(Request $request, $_locale)
    {

        $request->setLocale($_locale);

        $data = $this->get('request')->get('search_form');
        
        return array(
            'data' => $data,
            );
    }

    /**
     * @Route(
     *     "/request/demo", 
     *     name="request_demo",
     *     options = {"expose"=true}
     * )
     * @Method("POST")
     */
    public function sendAction(Request $request)
    {

        $allParams = $request->request->all();
        $firstName = $allParams['first_name'];
        $lastName = $allParams['last_name'];
        $email = $allParams['demo_email'];
        $company = $allParams['company_name'];
        $phone = $allParams['phone_code'].$allParams['phone'];
        $country = $allParams['country'];
        $workTime = $allParams['work_time'];
        $comment = $allParams['demo_message'];


        $em = $this->getDoctrine()->getManager();

        $entity = new Inbound();

        $entity->setPhone($phone);
        $entity->setBusiness($company);
        $entity->setName($firstName." ".$lastName);
        $entity->setCountry($country);
        $entity->setIpAddress($request->getClientIp());
        $entity->setVisitedPage($request->getSession()->get('visitedPage'));
        $entity->setEmail($email);
        $entity->setSource('demo_request');
        $entity->setStatus('new');

        $entity->setDescription("Subject : {$firstName} $lastName Would Like a Demo $workTime<br><br>".
            "Work Time: ". $workTime." <br><br>".
            "Comment:<br><br>".
            $comment);
        $em->persist($entity);

        $em->flush();

        try {
            $from = $email;
            $to = 'info@stradetegy.com';
            // $to = 'yanmi.inc@gmail.com';
            $replyTo = $email;
            $subject = "$firstName $lastName Would Like a Demo $workTime";
            $body = "<html><head></head><body>\n<br><b><font size='6'>$firstName $lastName</font></b><font color='#949494' size='6'> started the conversation</font>" .
                "\n<br>\n<br><b>Name  </b>: $firstName $lastName\n<br>" . 
            "<b>Company </b>: $company\n<br>".
            "<b>Phone </b>: $phone\n<br>".
            "<b>Email </b>: $email\n<br>".
            "<b>Country </b>: $country\n<br>\n<br>".
            "<b>Requested Demo Date/Time </b>: $workTime\n<br>\n<br>".
            "<b>Comment </b>:\n<br>".
            $comment. "\n<br>\n<br>\n<br>\n<br></body></html>";

            $newObj = new \stdClass();
            $newObj->message = 'succeed';
            $newJson = json_encode($newObj);
            $response = new Response($newJson);
            $response->headers->set('Content-Type', 'application/json');
            
            $this->get('mail_helper')->sendEmail($from, $to, $replyTo, $body, $subject);

        } catch (\Exception $e) {
            $newObj = new \stdClass();
            $newObj->message = 'failed';
            $newJson = json_encode($newObj);
            $response = new Response($newJson);
            $response->headers->set('Content-Type', 'application/json');
        }
        return $response;
    }

    /**
     * @Route(
     *     "/contact-send/", 
     *     name="contact_send",
     *     options = {"expose"=true}
     * )
     * @Method("POST")
     */
    public function contactAction(Request $request)
    {

        $allParams = $request->request->all();
        $name = $allParams['name_contact'];
        $phone = $allParams['phone_contact'];
        $email = $allParams['email_contact'];
        $subject = $allParams['subject_contact'];
        $message = $allParams['message_contact'];
        $service = $allParams['service_contact'];


        $em = $this->getDoctrine()->getManager();

        $entity = new Inbound();

        $entity->setPhone($phone);
        $entity->setName($name);
        $entity->setIpAddress($request->getClientIp());
        $entity->setVisitedPage($request->getSession()->get('visitedPage'));
        $entity->setEmail($email);
        $entity->setSource('contact_form');
        $entity->setStatus('new');

        $entity->setDescription("Subject : $subject".
            "Message:<br><br>".
            $message);
        $em->persist($entity);

        $em->flush();

        try {
            $from = $email;
            $to = 'info@stradetegy.com';
            // $to = 'yanmi.inc@gmail.com';
            $replyTo = $email;
            $subject = "CONTACT from $name";
            $body = "Name: $name\n" . 
            "Company: $company\n" .
            "$service\n" .
            "Phone: $phone\n\n".
            $message;

            $newObj = new \stdClass();
            $newObj->message = 'succeed';
            $newJson = json_encode($newObj);
            $response = new Response($newJson);
            $response->headers->set('Content-Type', 'application/json');
            
            $this->get('mail_helper')->sendEmail($from, $to, $replyTo, $body, $subject);

        } catch (\Exception $e) {
            $newObj = new \stdClass();
            $newObj->message = 'failed';
            $newJson = json_encode($newObj);
            $response = new Response($newJson);
            $response->headers->set('Content-Type', 'application/json');
        }
        return $response;
    }

}

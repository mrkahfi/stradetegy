<?php 

namespace Jariff\AdminBundle\Controller;


use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class SecurityController extends BaseController
{
    /**
     * @Route("/admin-login", name="admin_login")
     * @Template
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            );
    }
    
    /**
     * @Route("/admin-reset", name="admin_reset")
     * @Template()
     */
    public function resetAction()
    {
        return array();
    }

    /**
     * @Route("/reset/password", name="admin_reset_password")
     * @Template()
     */
    public function resetpasswordAction()
    {
        $request = $this->getRequest();
        $form = $this->createFormBuilder(array())
            ->add('username', 'text', array('required' => true))
            ->getForm();
        if($request->isMethod('POST')){
            $form->bind($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $client = $this->repo('JariffAdminBundle:Admin')->findOneByUsername($data['username']);
                $message = \Swift_Message::newInstance()
                    ->setSubject('Reset password Upulsa.com')
                    ->setFrom($this->container->getParameter('email'))
                    ->setTo(array($client->getProfile()->getEmail()))
                    ->setBody(
                        $this->renderView('JariffAdminBundle:Email:reset.html.twig', array('data' => $client), 'text/html'))
                    ->addPart(
                        $this->renderView('JariffAdminBundle:Email:reset.txt.twig', array('data' => $client)))
                ;
                $this->get('mailer')->send($message);
                $this->success('Petunjuk reset password telah dikirim ke email anda '.Util::mask($client->getProfile()->getEmail()));
            }
        }
        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/reset/password2", name="admin_reset_password2")
     * @Template()
     */
    public function resetpassword2Action()
    {
        $request = $this->getRequest();
        $form = $this->createFormBuilder(array())
            ->add('handphone', 'text', array('required' => true))
            ->add('pin', 'text', array('required' => true))
            ->getForm();
        if($request->isMethod('POST')){
            $form->bind($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $client = $this->repo('JariffSyncBundle:Pin')->findOneByPhoneAndPin($data['handphone'], $data['pin']);

                $message = \Swift_Message::newInstance()
                    ->setSubject('Reset password Upulsa.com')
                    ->setFrom($this->container->getParameter('email'))
                    ->setTo(array($client->getEmail()))
                    ->setBody(
                        $this->renderView('JariffAdminBundle:Email:reset.txt.twig', array('data' => $client)))
                    ->addPart(
                        $this->renderView('JariffAdminBundle:Email:reset.html.twig', array('data' => $client), 'text/html'))
                ;
                $this->get('mailer')->send($message);

                $this->success('Petunjuk reset password telah dikirim ke email anda '.Util::mask($client->getEmail()));
            }
        }
        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/reset/password/email/{username}/{email}/{password}", name="admin_reset_password_inemail")
     * @Template()
     */
    public function resetpasswordinemailAction($username,$email,$password)
    {
        $request = $this->getRequest();
        $client = $this->repo('JariffAdminBundle:Admin')->findOneByUsername($username);
        if(
            !$client or
            $client->getProfile()->getEmail() != $email or
            $client->getPassword() != $password
        ){
            $this->error('data salah');
        }
        $form = $this->createFormBuilder(array())
            ->add('password', 'repeated', array(
                'type'            => 'password',
                'options'         => array(),
                'invalid_message' => 'Password tidak sama',
                'first_options'   => array(
                    'label' => 'Password baru',
                    'attr' => array(
                        'title' => 'Password baru untuk login ke client area Anda.<br><br> Minimal 4 karakter.<br><br> Password tidak boleh sama dengan password email Anda.',
                    )
                ),
                'second_options' => array(
                    'label' => 'Ulangi Password Anda',
                    'attr' => array(
                        'title' => 'Ketik sekali lagi password Anda.<br><br>  Ini untuk memastikan bahwa Anda tidak keliru menulis password.',
                    )
                ),
            ))
            ->getForm();
        if($request->isMethod('POST')){
            $form->bind($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $data['client'] = $client;

                $newhash = $this->get('jariff_password_encoder_client')->encodePassword($data['password']);

                $client->setPassword($newhash);
                $this->em()->flush();

                $message = \Swift_Message::newInstance()
                    ->setSubject('Password berhasil diubah Upulsa.com')
                    ->setFrom($this->container->getParameter('email'))
                    ->setTo(array($client->getProfile()->getEmail()))
                    ->setBody(
                        $this->renderView('JariffAdminBundle:Email:newpassword.html.twig', array('data' => $data), 'text/html'))
                    ->addPart(
                        $this->renderView('JariffAdminBundle:Email:newpassword.txt.twig', array('data' => $data)))                        
                ;
                $this->get('mailer')->send($message);
                $this->success('Password berhasil diganti');
            }
        }
        return array(
            'client' => $client,
            'form' => $form->createView(),
        );
    }
}
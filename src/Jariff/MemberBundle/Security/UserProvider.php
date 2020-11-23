<?php 

namespace Jariff\MemberBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Jariff\MemberBundle\Entity\Member;
use Jariff\MemberBundle\Security\MemberBannedException;
use Jariff\AdminBundle\Entity\Inbound;

class UserProvider implements UserProviderInterface
{
    private $em;

    private $container;

    public function setEm($em){
        $this->em = $em;
    }

    public function setContainer($container){
        $this->container = $container;
    }

    public function loadUserByUsername($username)
    {
        $session = $this->container->get('request')->getSession();
        // $session->start();
        $entity = $this->em->getRepository('JariffMemberBundle:Member')->findOneByEmail($username);
        if ($entity) {
            if ($entity->getStatus() == Member::ACTIVE || $entity->getStatus() == Member::CANCELLED || $entity->getStatus() == Member::SUSPENDED || $entity->getStatus() == Member::EXPIRED) {
                $entity->setLastLoginDate(new \DateTime());
                $entity->setLastLoginIp($this->container->get('request')->getClientIp());

                // hapus file session user yg masih aktif, otomatis akan melogout user yg lain
                // user yg baru saja login session id nya di set dengan session id yg terbaru di DefaultController
                // karena di sf2, setelah user berhasil di authentikasi, dia akan dikasih session id yg baru untuk
                // menghindari dihack. kalau session id database nya kita set disini, tidak bisa.
                $sessionFile = $this->container->getParameter('kernel.cache_dir').'/sessions/sess_'.$entity->getSessionId();
                if (is_file($sessionFile)) {
                    unlink($sessionFile);
                };
                $this->em->flush();
                return $entity;
            } else {
                $inbound = new Inbound();
                $inbound->setEmail($entity->getEmail());
                $inbound->setPhone($entity->getPhone());
                $inbound->setBusiness($entity->getCompany());
                $inbound->setName($entity->getFirstName().' '.$entity->getLastName());
                $inbound->setCountry($entity->getCountry());
                $inbound->setIpAddress($this->container->get('request')->getClientIp());
                $inbound->setVisitedPage($session->get('landingPage'));
                $inbound->setSource('returning_user');
                $inbound->setDescription('Member #'.$entity->getNumber().' with status '.$entity->getStatusString().' tried to sign in');
                $inbound->setMember($entity);

                $this->em->persist($inbound);
                $this->em->flush();
                
                $body = "<h2></h2>";
                $subject = "";
                switch ($entity->getStatus()) {
                    case Member::CANCELLED:
                        $subject = 'Subject: ATTENTION! Canceled user tried to sign in';
                        $body = $body.
                                        "\n<br>\n<br>\n<br>A <b>cancelled</b> user just tried to sign in! Please check the account/payment information to identify the issue. Then call the user immediately to help them update their payment information or offer other payment methods. Follow up with email recap.";
                        break;
                    case Member::SUSPENDED:
                        $subject = 'Subject: ATTENTION! Suspended user tried to sign in';
                        $body = $body.
                                        "\n<br>\n<br>\n<br>A <b>suspended</b> user just tried to sign in! Please check the account/payment information to identify the issue. Then call the user immediately to help them update their payment information or offer other payment methods. Follow up with email recap.";
                        break;
                    case Member::EXPIRED:
                        $subject = 'Subject: ATTENTION! Expired user tried to sign in';
                        $body = $body.
                                        "\n<br>\n<br>\n<br>An <b>expired</b> user just tried to sign in! Please check the account/payment information to identify a possible issue. Then call the user immediately to help them reactivate their account. Follow up with email recap.";
                        break;
                }
                $body = $body .
                                "\n<br>\n<br><b>Account Number</b>: ".$entity->getNumber().
                                "\n<br><b>Account Name</b>: ".$entity->getFirstName()." ".$entity->getLastName().
                                "\n<br><b>Account Email</b>: ".$entity->getEmail().
                                "\n<br>\n<br>";

                $body = "<html><head></head><body>".$body."</body></html>";
                    
                $this->container->get('mail_helper')->sendEmail($entity->getEmail(), 'info@stradetegy.com', $entity->getEmail(), $body, $subject);
                // $this->container->get('mail_helper')->sendEmail($entity->getEmail(), 'yanmi.inc@gmail.com', $entity->getEmail(), $body, $subject);

                throw new \Exception($entity->getStatusString());
            } 
        }
        throw new UsernameNotFoundException(sprintf('User "%s" tidak ada.', $username));
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Member) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        return $this->em->getRepository('JariffMemberBundle:Member')->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $class === 'Jariff\MemberBundle\Entity\Member';
    }
}
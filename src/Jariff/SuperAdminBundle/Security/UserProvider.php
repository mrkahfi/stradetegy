<?php 

namespace Jariff\SuperAdminBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Jariff\SuperAdminBundle\Entity\SuperAdmin;

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
    
    public function loadUserByUsername($email)
    {
        $entity = $this->em->getRepository('JariffSuperAdminBundle:SuperAdmin')->findOneByEmail($email);

        if ($entity) {

            if($entity->isBanned())
                throw new \Exception(sprintf('Admin with email "%s" was banned, please contact Customer Support', $username));

            $entity->setLastLoginDate(new \DateTime());
            $entity->setLastLoginIp($this->container->get('request')->getClientIp());
            $this->em->flush();
            
            return $entity;
        }

        throw new UsernameNotFoundException(sprintf('Username with email "%s" does not exist.', $email));
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof SuperAdmin) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->em->getRepository('JariffSuperAdminBundle:SuperAdmin')->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $class === 'Jariff\SuperAdminBundle\Entity\SuperAdmin';
    }
}
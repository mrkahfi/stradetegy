<?php

namespace Jariff\MemberBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MemberSubscriptionRepository extends EntityRepository
{
    public function findActive($day){
    	$today = new \DateTime();
    	$today->add(new \DateInterval('P'.$day.'D'));
        return $this->getEntityManager()->createQuery('
            SELECT m, mp
            FROM JariffMemberBundle:Member m
            LEFT JOIN m.profile mp
            WHERE mp.dateExpired like \''.$today->format('Y-m-d').'%\'
            ORDER BY mp.dateExpired DESC
        ')
        ->getResult();
    }
}

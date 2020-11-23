<?php

namespace Jariff\MemberBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MemberRepository extends EntityRepository
{
    public function findExpiring($day){
        $today = new \DateTime();
        $today->add(new \DateInterval('P'.$day.'D'));
        return $this->getEntityManager()->createQuery('
            SELECT m
            FROM JariffMemberBundle:Member m
            LEFT JOIN m.subscription ms
            WHERE m.expiredDate like \''.$today->format('Y-m-d').'%\'
            AND ms.active = true 
            ORDER BY m.expiredDate DESC
        ')
        ->getResult();
    }
    public function findTotalToday(){
    	$today = new \DateTime();
        return count( $this->getEntityManager()->createQuery('
            SELECT m
            FROM JariffMemberBundle:Member m
            WHERE m.createdAt like \''.$today->format('Y-m-d').'%\'
        ')
        ->getResult() );
    }
}

<?php

namespace Jariff\MemberBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CountryRepository extends EntityRepository
{
	public function getCountriesByMember($member)
	{
		$em = $this->getEntityManager()->getConfiguration();

	    // $qb = $em->getRepository()->createQueryBuilder('c');
	    // $qb->join('c.subscriptions', 's')
	    //    ->where($qb->expr()->eq('s.id', $subscriptions));

		return $this->getEntityManager()->createQuery('
			SELECT c, s
			FROM JariffMemberBundle:Country c
			LEFT JOIN c.subscriptions s
			WHERE s.member = :member
			')
		->setParameter('member', $member)
		->getResult();
	}
}

<?php

namespace Jariff\MemberBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MemberActivityFeedRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('date_activity' => 'DESC'));
    }
}

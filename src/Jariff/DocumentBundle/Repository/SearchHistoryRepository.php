<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 1/16/14
 * Time: 12:00 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\DocumentBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class SearchHistoryRepository extends DocumentRepository {

    public function findAllOrderedByDate($username)
    {
        return $this->createQueryBuilder()
//            ->field('username')->equals($username)
            ->sort('date_search', 'DESC')
            ->getQuery()
            ->execute();
    }
}
<?php

namespace Jariff\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\MemberBundle\Entity\PaymentHistory;

/**
 * @Route("/admin/member")
 */
class MemberHistoryController extends BaseController
{
    /**
     * @Route(
     *     "/{id}/history/json-index",
     *     name = "admin_member_history_json_index",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     */
    public function jsonIndexAction(Request $request, $id)
    {

        $qb = $this->repo('JariffMemberBundle:MemberHistory')
            ->createQueryBuilder('m')
            ->select('m')
            ->andWhere('m.member = '.$id);

        // sorting
        $sorters = json_decode($request->get('pq_sort'));
        foreach ($sorters as $sorter){
            $dir = ($sorter->dir == "up") ? 'asc' : 'desc';
            $qb->addOrderBy($sorter->dataIndx, $dir);
        }
        // end sortingMemberEmailAlias
        // filtering
        if (($pq_filter = $request->get('pq_filter')) !== null) {
            $filterObj = json_decode($pq_filter);
            foreach ($filterObj->data as $filter) {
                $qb->andWhere($filter->dataIndx.' like \'%'.$filter->value.'%\'');
            }
        }
        // end filtering
        
        // get total amount
        $entities = $qb->getQuery()->getResult();
        // prepare output data stats
        $identify = array(
            'totalRecords' => count($entities),
        );

        // paging
        $pq_curpage = ($request->get('pq_curpage') != '') ? intval($request->get('pq_curpage')) : 1;
        $pq_rpp     = ($request->get('pq_rpp') != '') ? intval($request->get('pq_rpp')) : 20;
        $qb->setFirstResult(($pq_curpage * $pq_rpp) - $pq_rpp)
            ->setMaxResults($pq_rpp);
        // end paging

        // get the result
        $entities = $qb->getQuery()->getResult();

        // prepare output data stats
        $identify['curPage'] = $pq_curpage;

        // prepare output data content
        $data = array();
        foreach ($entities as $r) {
            $data[] = array(
                'm.id'          => $r->getId(),
                'm.admin'       => $r->getAdmin()->getName(),
                'm.column'      => $r->getColumn(),
                'm.date'        => $r->getDate()->format('Y-m-d H:i'),
                'm.description' => $r->getDescription(),
                'm.newValue'    => $r->getNewValue(),
                'm.oldValue'    => $r->getOldValue(),
                'm.table'       => $r->getTable(),
            );
        }

        $merge = array_merge($identify, array('data' => $data));

        return $this->json($merge);
    }

    /**
     * @Route(
     *     "/{id}/history", 
     *     name="admin_member_history_show"
     * )
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $member = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$member) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }
        
        return array(
            'member'      => $member,
        );
    }
}

<?php

namespace Jariff\SuperAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/super-admin/member")
 */
class LeadHistoryController extends BaseController
{
    /**
     * @Route(
     *     "/{id}/lead-history/json-index",
     *     name = "super_admin_lead_history_json_index",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     */
    public function jsonIndexAction(Request $request, $id)
    {

        $qb = $this->repo('JariffAdminBundle:LeadHistory')
            ->createQueryBuilder('lh')
            ->select('lh')
            ->andWhere('lh.lead = '.$id);

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
                'lh.id'          => $r->getId(),
                'lh.admin'       => $r->getAdmin()->getName(),
                'lh.column'      => $r->getColumn(),
                'lh.date'        => $r->getDate()->format('Y-m-d H:i'),
                'lh.description' => $r->getDescription(),
                'lh.newValue'    => $r->getNewValue(),
                'lh.oldValue'    => $r->getOldValue(),
                'lh.table'       => $r->getTable(),
            );
        }

        $merge = array_merge($identify, array('data' => $data));

        return $this->json($merge);
    }

    /**
     * @Route(
     *     "/{id}/lead-history", 
     *     name="admin_lead_history_show"
     * )
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $member = $this->repo('JariffMemberBundle:Member')->find($id);
        $entity = $this->repo('JariffAdminBundle:Lead')->find($id);

        if (!$entity or !$member) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        if (!$member->getLead()) {
            return $this->errorRedirect('This user doesn\'t have lead', 'admin_member_profile_show', array('id' => $member->getId()));
        }
        
        return array(
            'entity' => $member->getLead(),
            'member' => $member,
        );
    }
}

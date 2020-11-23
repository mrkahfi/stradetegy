<?php

namespace Jariff\SuperAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\AdminBundle\Entity\LeadActivity;
use Jariff\AdminBundle\Form\LeadActivityType;

/**
 * LeadActivity controller.
 *
 * @Route("/super-admin/lead/activity")
 */
class LeadActivityController extends BaseController
{
    public function checkComplete(LeadActivity $entity)
    {
        $uow = $this->em()->getUnitOfWork();
        var_dump($uow->isScheduledForUpdate($entity));
        if($uow->isScheduledForUpdate($entity)){
            $origin = $uow->getOriginalEntityData($entity);
            if ($origin->getStatus() != 'Complete' && $entity->getStatus() == 'Complete') {
                return new \DateTime();
            }
        } else
        if($uow->isScheduledForInsert($entity)){
            if ($entity->getStatus() == 'Complete') {
                return new \DateTime();
            }
        }
        return null;
    }

    /**
     * @Route(
     *     "/json-index/{id}",
     *     name = "super_admin_lead_activity_json_index",
     *     requirements = {
     *       "id"  : "\d+"
     *     },
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     */
    public function jsonIndexAction(Request $request, $id)
    {

        $qb = $this->repo('JariffAdminBundle:LeadActivity')
            ->createQueryBuilder('activity')
            ->select('activity')
            ->leftJoin('activity.assigned', 'assigned')
            ->leftJoin('activity.owner', 'owner')
            ->leftJoin('activity.result', 'result')
            ->leftJoin('activity.type', 'type')
            ->where('activity.lead = '.$id);

        // sorting
        $sorters = json_decode($request->get('pq_sort'));
        foreach ($sorters as $sorter){
            $dir = ($sorter->dir == "up") ? 'asc' : 'desc';
            $qb->addOrderBy('activity.'.$sorter->dataIndx, $dir);
        }
        // end sortingMemberEmailAlias
        // filtering
        if (($pq_filter = $request->get('pq_filter')) !== null) {
            $filterObj = json_decode($pq_filter);
            $filters   = $filterObj->data;
             
            foreach ($filters as $filter) {            
                $dataIndx = $filter->dataIndx;  
                $qb->andWhere('activity.'.$dataIndx.' like :'.$dataIndx)
                   ->setParameter($dataIndx, '%'.$filter->value.'%');
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
                'id'            => $r->getId(),
                'priority'      => $r->getPriority(),
                'dateCreate'    => is_null($r->getDateCreate())?'':$r->getDateCreate()->format('Y/m/d'),
                'owner'         => is_null($r->getOwner())?'':$r->getOwner()->getName(),
                'dateTask'      => is_null($r->getDateTask())?'':$r->getDateTask()->format('Y/m/d'),
                'assigned'      => is_null($r->getAssigned())?'':$r->getAssigned()->getName(),
                'dateScheduled' => is_null($r->getDateScheduled())?'':$r->getDateScheduled()->format('Y/m/d'),
                'type'          => is_null($r->getType())?'':$r->getType()->getName(),
                'description'   => $r->getDescription(),
                'result'        => is_null($r->getResult())?'':$r->getResult()->getName(),
                'status'        => $r->getStatus(),
                'dateCompleted' => is_null($r->getDateCompleted())?'':$r->getDateCompleted()->format('Y/m/d'),
            );
        }

        $merge = array_merge($identify, array('data' => $data));

        return $this->json($merge);
    }

    /**
     * Creates a new LeadActivity entity.
     *
     * @Route(
     *   "/create/{id}",
     *   name     = "admin_lead_activity_create",
     *   requirements = {
     *     "id"  : "\d+",
     *     "type": "event|task"
     *   }
     * )
     * @Method("POST")
     * @Template("JariffAdminBundle:LeadActivity:new.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $entity = new LeadActivity();
        $form   = $this->createCreateForm($entity, $id);
        $lead   = $this->repo('JariffAdminBundle:Lead')->find($id);
        $entity->setLead($lead);
        // $entity->setDateCompleted($this->checkComplete($entity));
        $form->handleRequest($request);

        $entity->setOwner($this->user());

        if ($form->isValid()) {
            $this->persist($entity);
            $this->flush();
            $this->success('Data saved');

            return $this->redirectUrl('admin_lead_show', array('id' => $lead->getId()));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a LeadActivity entity.
    *
    * @param LeadActivity $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(LeadActivity $entity, $lead_id)
    {
        $form = $this->createForm(new LeadActivityType(), $entity, array(
            'action' => $this->generateUrl('admin_lead_activity_create', array('id' => $lead_id)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new LeadActivity entity.
     *
     * @Route(
     *   "/add/{type}/{id}",
     *   name     = "lead_activity_new",
     *   requirements = {
     *     "id"  : "\d+",
     *     "type": "event|task"
     *   }
     * )
     * @Method("GET")
     * @Template()
     */
    public function newAction($id, $type)
    {
        $lead = $this->repo('JariffAdminBundle:Lead')->find($id);
        $entity = new LeadActivity();
        $entity->setLead($lead);

        if ($type == 'event') {
            $entity->setEvent();
        }
        
        $form   = $this->createCreateForm($entity, $id);

        return array(
            'entity' => $entity,
            'lead'   => $lead,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing LeadActivity entity.
     *
     * @Route(
     *     "/edit/{id}", 
     *     name="admin_lead_activity_edit",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     * @Template("JariffAdminBundle:LeadActivity:new.html.twig")
     */
    public function editAction($id)
    {
        $entity = $this->repo('JariffAdminBundle:LeadActivity')->find($id);

        if ($entity->getStatus() == 'Complete') {
            return $this->errorRedirect('Activity already completed, editing disabled.');
        }

        if (!$entity) {
            return $this->errorRedirect('Invalid data.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a LeadActivity entity.
    *
    * @param LeadActivity $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(LeadActivity $entity)
    {
        $form = $this->createForm(new LeadActivityType(), $entity, array(
            'action' => $this->generateUrl('admin_lead_activity_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing LeadActivity entity.
     *
     * @Route("/{id}", name="admin_lead_activity_update")
     * @Method("PUT")
     * @Template("JariffAdminBundle:LeadActivity:new.html.twig")
     */
    public function updateAction(Request $request, $id)
    {

        $entity = $this->repo('JariffAdminBundle:LeadActivity')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Invalid data.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);


        if ($editForm->isValid()) {

            $completed = false;
            $uow    = $this->em()->getUnitOfWork();
            $uow->computeChangeSets();
            if($uow->isScheduledForUpdate($entity)){
                $changeset = $uow->getEntityChangeSet($entity);
                if ( array_key_exists('status', $changeset) && $changeset['status'][0] != 'Complete' && $changeset['status'][1] == 'Complete') {
                    // update terhadap managed entity tidak bisa dilakukan disini karena akan mengoverwrite data yg sebelumnya disubmit
                    // solusinya ya update date completed nya setelah flush pertama,
                    $completed = true;
                }
            }

            $this->flush();
            if ($completed) {
                $entity->setDateCompleted(new \DateTime());
                $this->flush();
            }
            $this->success('Update successful');

            return $this->redirectUrl('admin_lead_show', array('id' => $entity->getLead()->getId()));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a LeadActivity entity.
     *
     * @Route("/{id}", name="admin_lead_activity_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity = $this->repo('JariffAdminBundle:LeadActivity')->find($id);

            if (!$entity) {
                return $this->errorRedirect('Invalid data.');
            }

            $this->remove($entity);
            $this->flush();
            $this->success('Update successful');
        }

        return $this->redirectUrl('lead');
    }

    /**
     * Creates a form to delete a LeadActivity entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_lead_activity_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

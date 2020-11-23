<?php

namespace Jariff\SuperAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\MemberBundle\Entity\Payment;
use Jariff\AdminBundle\Form\PaymentType;

/**
 * Payment controller.
 *
 * @Route("/super-admin/member")
 */
class PaymentController extends BaseController
{

    /**
     * @Route(
     *     "{id}/payment/json-index",
     *     name = "super_admin_payment_json_index",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     *
     */
    public function jsonIndexAction(Request $request, $id)
    {

        $qb = $this->repo('JariffMemberBundle:Payment')
            ->createQueryBuilder('payment')
            ->select('payment, a, pm, mp')
            ->leftJoin('payment.admin', 'a')
            ->leftJoin('payment.member', 'pm')
            ->leftJoin('pm.profile', 'mp')
            ->where('payment.member.id = '.$id);

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
                'p.id' => $r->getId(),
                'p.amount' => $r->getAmount(),
                'p.date'   => $r->getDate()->format('Y/m/d'),
                'p.type'   => $r->getType(),
                'p.token'   => $r->getToken(),
                'p.note'   => $r->getNote(),
                'mp.firstName'   => (is_null($r->getMember()))?'':$r->getMember()->getProfile()->getFirstName(),
                'mp.lastName'   => (is_null($r->getMember()))?'':$r->getMember()->getProfile()->getLastName(),
                'a.name'   => (is_null($r->getAdmin()))?'':$r->getAdmin()->getName(),
            );
        }

        $merge = array_merge($identify, array('data' => $data));

        return $this->json($merge);
    }
    
    /**
     * Creates a new Payment entity.
     *
     * @Route("/", name="super_admin_payment_create")
     * @Method("POST")
     * @Template("JariffMemberBundle:Payment:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Payment();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setAdmin($this->getUser());
            $this->em()->persist($entity);
            $this->flush();
            $this->success('New payment data saved');

            return $this->redirectUrl('admin_payment_index');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Payment entity.
    *
    * @param Payment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Payment $entity)
    {
        $form = $this->createForm(new PaymentType(), $entity, array(
            'action' => $this->generateUrl('admin_payment_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Payment entity.
     *
     * @Route(
     *     "/payment/new/{id}",
     *     name = "super_admin_member_payment_new",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request, $id)
    {

        $invoice = $this->repo('JariffMemberBundle:Invoice')->find($id);

        $entity = new Payment();
        $entity->setInvoice($invoice);
        $entity->setMember($invoice->getMember());
        $entity->setAmount($invoice->getAmount());
        $form   = $this->createCreateForm($entity);

        return array(
                'member' => $invoice->getMember(),
                'invoice' => $invoice,
                'entity' => $entity,
                'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Payment entity.
     *
     * @Route(
     *     "/{id}/payment", 
     *     name="admin_member_payment_show"
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
            'member' => $member,
        );
    }

    /**
     * Displays a form to edit an existing Payment entity.
     *
     * @Route("/{id}/payment/edit", name="super_admin_payment_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->repo('JariffMemberBundle:Payment')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_payment_index');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Payment entity.
    *
    * @param Payment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Payment $entity)
    {
        $form = $this->createForm(new PaymentType(), $entity, array(
            'action' => $this->generateUrl('payment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Payment entity.
     *
     * @Route("/{id}/payment/update", name="super_admin_payment_update")
     * @Template("JariffMemberBundle:Payment:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->repo('JariffMemberBundle:Payment')->find($id);

        if (!$entity) {
                return $this->errorRedirect('Unable to find requested data.', 'admin_payment_index');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->flush();

            return $this->redirectUrl('payment_edit', array('id' => $id));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Payment entity.
     *
     * @Route("/{id}", name="super_admin_payment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity = $this->repo('JariffMemberBundle:Payment')->find($id);

            if (!$entity) {
                return $this->errorRedirect('Unable to find requested data.', 'admin_payment_index');
            }

            $this->remove($entity);
            $this->flush();
        }

        return $this->redirectUrl('payment');
    }

    /**
     * Creates a form to delete a Payment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('payment_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

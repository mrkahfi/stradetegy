<?php

namespace Jariff\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\MemberBundle\Entity\Invoice;
use Jariff\AdminBundle\Form\InvoiceType;

/**
 * Invoice controller.
 *
 * @Route("/admin/member")
 */
class InvoiceController extends BaseController
{
    /**
     * @Route(
     *     "/{id}/invoice/json-index", 
     *     name = "admin_member_invoice_json_index",
     *     options = {"expose"=true}
     * )n
     * @Method("GET")
     */
    public function jsonIndexAction(Request $request, $id)
    {

        $qb = $this->repo('JariffMemberBundle:Invoice')
            ->createQueryBuilder('invoice')
            ->select('invoice')
            ->andWhere('invoice.member = '.$id)
            ->leftJoin('invoice.subscription', 'subscription')
            // ->leftJoin('invoice.subscription', 'subscription', Expr\Join::WITH, 'activity.status = \'Complete\'')
            ;

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
                'invoice.id'           => $r->getId(),
                'invoice.amount'       => $r->getAmount(),
                'invoice.number'       => $r->getNumber(),
                'invoice.billToName'   => $r->getBillToName(),
                'invoice.billToAdress' => $r->getBillToAdress(),
                'invoice.billToEmail'  => $r->getBillToEmail(),
                'invoice.billToPhone'  => $r->getBillToPhone(),
                'invoice.date'         => $r->getDate()->format('Y-m-d H:i'),
                'invoice.description'  => $r->getDescription(),
                'invoice.payment'      => is_null($r->getPayment())?'':$r->getPayment()->getAmount(),
                'invoice.sales'        => is_null($r->getSales())?'':$r->getSales()->getName(),
                'invoice.subscription' => $r->getSubscription()->getNumber(),
                'invoice.type'       => $r->getType(),
            );
        }

        $merge = array_merge($identify, array('data' => $data));

        return $this->json($merge);
    }

    /**
     * Creates a new Invoice entity.
     *
     * @Route("/", name="invoice_create")
     * @Method("POST")
     * @Template("JariffMemberBundle:Invoice:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Invoice();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $entity->setAmount($entity->getSubscription()->getTotal());

        if ($form->isValid()) {


            $invoices = $this->repo('JariffMemberBundle:Invoice')->findByMember($entity->getMember());
            // set number untuk subscription ini
            $entity->setNumber($entity->getMember()->getNumber().'-'.(count($invoices) + 1));

            $this->persist($entity);
            $this->flush();
            $this->success('Ok');

            return $this->redirectUrl('admin_member_invoice_show', array('id' => $entity->getMember()->getId()));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Invoice entity.
    *
    * @param Invoice $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Invoice $entity)
    {
        $form = $this->createForm(new InvoiceType(), $entity, array(
            'action' => $this->generateUrl('invoice_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Invoice entity.
     *
     * @Route(
     *     "/{id}/invoice/new",
     *     name = "admin_member_invoice_new",
     *     requirements = {
     *       "id"  : "\d+"
     *     }
     * )
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $member = $this->repo('JariffMemberBundle:Member')->find($id);
        
        $entity = new Invoice();
        $entity->setMember($member);
        $entity->setBillToName($member->getCompany()->getBillToName());
        $entity->setBillToAdress($member->getCompany()->getBillToAdress());
        $entity->setBillToEmail($member->getCompany()->getBillToEmail());
        $entity->setBillToPhone($member->getCompany()->getBillToPhone());
        $entity->setDate(new \DateTime());
        $entity->setSales($this->user());
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'member' => $member,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Payment entity.
     *
     * @Route(
     *     "/{id}/invoice", 
     *     name="admin_member_invoice_show"
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
     * Displays a form to edit an existing Invoice entity.
     *
     * @Route("/{id}/edit", name="invoice_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->repo('JariffMemberBundle:Invoice')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
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
    * Creates a form to edit a Invoice entity.
    *
    * @param Invoice $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Invoice $entity)
    {
        $form = $this->createForm(new InvoiceType(), $entity, array(
            'action' => $this->generateUrl('invoice_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Invoice entity.
     *
     * @Route("/{id}", name="invoice_update")
     * @Method("PUT")
     * @Template("JariffMemberBundle:Invoice:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {

        $entity = $this->repo('JariffMemberBundle:Invoice')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->flush();
            $this->success('Ok');

            return $this->redirect($this->generateUrl('invoice_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Invoice entity.
     *
     * @Route("/{id}", name="invoice_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity = $this->repo('JariffMemberBundle:Invoice')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Invoice entity.');
            }

            $this->em()->remove($entity);
            $this->flush();
            $this->success('Ok');
        }

        return $this->redirect($this->generateUrl('invoice'));
    }

    /**
     * Creates a form to delete a Invoice entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('invoice_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

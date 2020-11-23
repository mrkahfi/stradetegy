<?php

namespace Jariff\SuperAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\MemberBundle\Entity\MemberARB;
use Jariff\AdminBundle\Form\MemberARBType;

/**
 * MemberARB controller.
 *
 * @Route("/super-admin/arb")
 */
class MemberARBController extends BaseController
{
    /**
     * @Route(
     *     "/{id}/json-index",
     *     name = "super_admin_arb_json_index",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     *
     */
    public function jsonIndexAction(Request $request, $id)
    {

        $qb = $this->repo('JariffMemberBundle:MemberARB')
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
                'm.id'            => $r->getId(),
                'm.token'            => $r->getToken(),
                'm.chargeDate' => $r->getChargeDate()->format('Y-m-d H:i'),
                'm.amount'         => $r->getAmount(),
                'm.successful'         => $r->getSuccessful(),
                'm.processed'         => $r->getProcessed(),
            );
        }

        $merge = array_merge($identify, array('data' => $data));

        return $this->json($merge);
    }

    /**
     * Lists all MemberARB entities.
     *
     * @Route("/", name="super_admin_arb_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JariffMemberBundle:MemberARB')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new MemberARB entity.
     *
     * @Route("/", name="super_admin_arb_create")
     * @Method("POST")
     * @Template("JariffMemberBundle:MemberARB:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new MemberARB();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('arb_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a MemberARB entity.
    *
    * @param MemberARB $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(MemberARB $entity)
    {
        $form = $this->createForm(new MemberARBType(), $entity, array(
            'action' => $this->generateUrl('arb_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MemberARB entity.
     *
     * @Route("/new", name="super_admin_arb_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new MemberARB();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a MemberARB entity.
     *
     * @Route(
     *     "/show/{token}", 
     *     name="admin_arb_show"
     * )
     * @Method("GET")
     * @Template()
     */
    public function showAction($token)
    {
        $entity = $this->repo('JariffMemberBundle:Member')->findOneByToken($token);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_active_index');
        }
        
        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing MemberARB entity.
     *
     * @Route(
     *     "/edit/{token}", 
     *     name="admin_arb_edit",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     * @Template()
     */
    public function editAction($token)
    {
        $entity = $this->repo('JariffMemberBundle:MemberARB')->findOneByToken($token);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_active_index');
        }
        

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($token);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a MemberARB entity.
    *
    * @param MemberARB $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MemberARB $entity)
    {
        $form = $this->createForm(new MemberARBType(), $entity, array(
            'action' => $this->generateUrl('arb_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing MemberARB entity.
     *
     * @Route("/{id}", name="super_admin_arb_update")
     * @Method("PUT")
     * @Template("JariffMemberBundle:MemberARB:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->repo('JariffMemberBundle:Member')->findOneByToken($token);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_active_index');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('arb_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a MemberARB entity.
     *
     * @Route("/delete/{token}", name="super_admin_arb_delete")
     */
    public function deleteAction(Request $request, $token)
    {
        $entity = $this->repo('JariffMemberBundle:Member')->findOneByToken($token);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_active_index');
        }

        $form = $this->createDeleteForm($token);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JariffMemberBundle:MemberARB')->find($token);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MemberARB entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('arb'));
    }

    /**
     * Creates a form to delete a MemberARB entity by token.
     *
     * @param mixed $token The entity token
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($token)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_arb_delete', array('token' => $token)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

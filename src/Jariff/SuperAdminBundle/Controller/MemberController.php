<?php

namespace Jariff\SuperAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;

use Jariff\MemberBundle\Entity\MemberHistory;
use Doctrine\ORM\EntityRepository;
use Jariff\MemberBundle\Entity\Member;

/**
 * @Route("/super-admin/member")
 * Member controller.
 */
class MemberController extends BaseController
{
    /**
     * @Route(
     *     "/{id}/reset-password/update",
     *     name = "super_admin_member_reset_password_update",
     *     requirements = {
     *       "id"  : "\d+"
     *     }
     * )
     * @Template("JariffMemberBundle:MemberSubscription:resetPassword.html.twig")
     */
    public function resetPasswordUpdateAction(Request $request, $id)
    {
        $member = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$member) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        $form = $this->createResetForm($member);
        $form->handleRequest($request);

        $member->setPassword($this->get('jariff_password_encoder')->encodePassword($member->getPassword(), $member->getSalt()));

        if ($form->isValid()) {

            $uow = $this->em()->getUnitOfWork();
            $uow->computeChangeSets();
            $changesets = $uow->getEntityChangeSet($member);
            foreach ($changesets as $key => $value) {
                $history = new MemberHistory();
                $history->setColumn($key);
                $history->setNewValue($value[1]);
                $history->setOldValue($value[0]);
                $history->setTable('member');
                $history->setAdmin($this->user());
                $history->setMember($member);

                $this->persist($history);
            }

            $this->success('Ok');
            $this->flush();
            return $this->redirectUrl('admin_member_profile_show', array('id' => $id));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    private function createResetForm(Member $member)
    {
        return $this->createFormBuilder($member, array('data_class' => 'Jariff\MemberBundle\Entity\Member'))
            ->setAction($this->generateUrl('admin_member_reset_password_update', array('id' => $member->getId())))
            ->setMethod('PUT')
            ->add('password', 'repeated', array(
                'type'            => 'password',
                'invalid_message' => 'The password fields must match.',
                'options'         => array('attr' => array('class' => 'password-field')),
                'required'        => true,
                'first_options'   => array('label' => 'Password'),
                'second_options'  => array('label' => 'Repeat Password'),
            ))
            ->add('submit', 'submit', array('label' => 'Save'))
            ->getForm()
        ;
    }

    /**
     * @Route(
     *     "/{id}/reset-password",
     *     name = "super_admin_member_reset_password",
     *     requirements = {
     *       "id"  : "\d+"
     *     }
     * )
     * @Method("GET")
     * @Template()
     */
    public function resetPasswordAction($id)
    {
        $member = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$member) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        $form = $this->createResetForm($member);

        return array(
            'member' => $member,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Route(
     *     "/cc/{id}", 
     *     name="admin_member_cc_new",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     * @Template()
     */
    public function ccAction($id)
    {
        $entity = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        return array('entity' => $entity);
    }

    /**
     * @Route(
     *     "/activities/{id}", 
     *     name="admin_member_activities",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     * @Template()
     */
    public function activitiesAction($id)
    {
        $entity = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        return array('entity' => $entity);
    }

    /**
     * @Route(
     *     "/client-history/{id}", 
     *     name="admin_member_client_history",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     * @Template()
     */
    public function clientHistoryAction($id)
    {
        $entity = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        return array('entity' => $entity);
    }

    /**
     * @Route(
     *     "/representative-history/{id}", 
     *     name="admin_member_representative_history",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     * @Template()
     */
    public function representativeHistoryAction($id)
    {
        $entity = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        return array('entity' => $entity);
    }
    
    /**
     * @Route(
     *     "/json-index",
     *     name = "super_admin_member_json_index",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     *
     */
    public function jsonIndexAction(Request $request)
    {

        $qb = $this->repo('JariffMemberBundle:Member')
            ->createQueryBuilder('m')
            ->select('m')
            ->leftJoin('m.profile', 'mp')
            ->leftJoin('m.subscription', 'ms');

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
                'id'            => $r->getId(),
                'm.email'         => $r->getEmail(),
                'm.token'         => $r->getToken(),
                'm.lastLoginDate' => (is_null($r->getLastLoginDate()))?'':$r->getLastLoginDate()->format('Y-m-d H:i'),
                'mp.firstName'    => $r->getProfile()->getFirstName(),
                'mp.lastName'     => $r->getProfile()->getLastName(),
            );
        }

        $merge = array_merge($identify, array('data' => $data));

        return $this->json($merge);
    }

    /**
     * Lists all Member entities.
     *
     * @Route("/index", name="super_admin_member_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $format = $request->query->get('type');

        if($format == 'ajax'){
            return $this->render(
                'JariffAdminBundle:Member:index-detail.html.twig',
                array(
                )
            );
        }

        return $this->render(
                'JariffAdminBundle:Member:index.html.twig',
            array(
            )
        );
    }
    
    /**
     * Creates a new Member entity.
     *
     * @ Route("/create", name="member_create")
     * @Method("POST")
     * @Template("JariffMemberBundle:Member:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Member();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('member_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Member entity.
    *
    * @param Member $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Member $entity)
    {
        $form = $this->createForm(new MemberType(), $entity, array(
            'action' => $this->generateUrl('member_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Member entity.
     *
     * @ Route("/new", name="member_newe")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Member();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Member entity.
     *
     * @Route(
     *     "/show/{id}", 
     *     name="admin_member_show",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     */
    public function showAction(Request $request, $id)
    {
        // $entity = $this->repo('JariffMemberBundle:Member')->findOneByToken($token);
        $entity = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        $format = $request->query->get('type');

        if($format == 'ajax'){
            return $this->render(
                'JariffAdminBundle:Member:show-detail.html.twig',
                array(
                    'entity'      => $entity,
                )
            );
        }

        return $this->render(
                'JariffAdminBundle:Member:show.html.twig',
            array(
                'entity'      => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing Member entity.
     *
     * @ Route("/edit/{id}", name="member_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffMemberBundle:Member')->find($id);

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
    * Creates a form to edit a Member entity.
    *
    * @param Member $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Member $entity)
    {
        $form = $this->createForm(new MemberType(), $entity, array(
            'action' => $this->generateUrl('member_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Member entity.
     *
     * @ Route("/update/{id}", name="member_update")
     * @Method("PUT")
     * @Template("JariffMemberBundle:Member:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffMemberBundle:Member')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('member_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Member entity.
     *
     * @ Route("/delete/{id}", name="member_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JariffMemberBundle:Member')->find($id);

            if (!$entity) {

            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('member'));
    }

    /**
     * Creates a form to delete a Member entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('member_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

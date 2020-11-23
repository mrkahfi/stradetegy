<?php

namespace Jariff\SuperAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\MemberBundle\Entity\MemberAttachment;
use Jariff\AdminBundle\Form\MemberAttachmentType;

/**
 * MemberAttachment controller.
 *
 * @Route("/super-admin/attachment")
 */
class MemberAttachmentController extends BaseController
{
    /**
     * Creates a new MemberAttachment entity.
     *
     * @Route("/create", name="super_admin_attachment_create")
     * @Route(
     *     "/create/{type}",
     *     name = "super_admin_attachment_create",
     *     requirements = {
     *       "type"  : "subscription|payment"
     *     }
     * )
     * @Template("JariffAdminBundle:MemberAttachment:new.html.twig")
     */
    public function createAction(Request $request, $type)
    {
        $entity = new MemberAttachment();
        $entity->setCategory($type);
        $form   = $this->createCreateForm($entity, $type);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_member_profile_show', array('id' => $entity->getMember()->getId())));
        }

        return array(
            'entity' => $entity,
            'member' => $form->getData()->getMember(),
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a MemberAttachment entity.
    *
    * @param MemberAttachment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(MemberAttachment $entity, $type)
    {
        $form = $this->createForm(new MemberAttachmentType(), $entity, array(
            'action' => $this->generateUrl('admin_attachment_create', array('type' => $type)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MemberAttachment entity.
     *
     * @Route(
     *     "/new/{type}/{id}",
     *     name = "super_admin_attachment_new",
     *     requirements = {
     *       "type"  : "subscription|payment",
     *       "id"  : "\d+"
     *     }
     * )
     * @Method("GET")
     * @Template()
     */
    public function newAction($type, $id)
    {
        $member = $this->repo('JariffMemberBundle:Member')->find($id);

        $entity = new MemberAttachment();
        $entity->setCategory($type);
        $entity->setMember($member);
        $form   = $this->createCreateForm($entity, $type);

        return array(
            'entity' => $entity,
            'member' => $member,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a MemberAttachment entity.
     *
     * @Route("/shoe/{id}", name="super_admin_attachment_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffMemberBundle:MemberAttachment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MemberAttachment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing MemberAttachment entity.
     *
     * @Route("/{id}/edit", name="super_admin_attachment_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffMemberBundle:MemberAttachment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MemberAttachment entity.');
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
    * Creates a form to edit a MemberAttachment entity.
    *
    * @param MemberAttachment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MemberAttachment $entity)
    {
        $form = $this->createForm(new MemberAttachmentType(), $entity, array(
            'action' => $this->generateUrl('attachment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing MemberAttachment entity.
     *
     * @Route("/{id}", name="super_admin_attachment_update")
     * @Method("PUT")
     * @Template("JariffAdminBundle:MemberAttachment:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffMemberBundle:MemberAttachment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MemberAttachment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('attachment_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a MemberAttachment entity.
     *
     * @Route("/{id}", name="super_admin_attachment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JariffMemberBundle:MemberAttachment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MemberAttachment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('attachment'));
    }

    /**
     * Creates a form to delete a MemberAttachment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('attachment_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

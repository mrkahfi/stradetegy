<?php

namespace Jariff\MemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\MemberBundle\Entity\MemberProfile;
use Jariff\MemberBundle\Form\MemberProfileType;
use Jariff\ProjectBundle\Controller\BaseController;

/**
 * MemberProfile controller.
 *
 * @Route("/member/profile")
 */
class MemberProfileController extends BaseController
{

    /**
     * Lists all MemberProfile entities.
     *
     * @Route("/", name="profile")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JariffMemberBundle:MemberProfile')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new MemberProfile entity.
     *
     * @Route("/", name="profile_create")
     * @Method("POST")
     * @Template("JariffMemberBundle:MemberProfile:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new MemberProfile();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('profile_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a MemberProfile entity.
    *
    * @param MemberProfile $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(MemberProfile $entity)
    {
        $form = $this->createForm(new MemberProfileType(), $entity, array(
            'action' => $this->generateUrl('profile_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MemberProfile entity.
     *
     * @Route("/new", name="profile_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new MemberProfile();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a MemberProfile entity.
     *
     * @Route("/profile-show", name="profile_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffMemberBundle:MemberProfile')->findOneBy(array('member' => $this->getUser()->getId()));

        if (!$entity) {
            throw $this->createNotFoundException('tesss Unable to find MemberProfile entity.');
        }

        $deleteForm = $this->createDeleteForm($entity->getId());

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing MemberProfile entity.
     *
     * @Route("/edit", name="profile_edit")
     *
     * @Template()
     */
    public function editAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffMemberBundle:MemberProfile')->findOneBy(array('member' => $this->getUser()));

        if (!$entity) {
            throw $this->createNotFoundException('test Unable to find MemberProfile entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity->getId());

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a MemberProfile entity.
    *
    * @param MemberProfile $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MemberProfile $entity)
    {
        $form = $this->createForm(new MemberProfileType(), $entity, array(
            'action' => $this->generateUrl('profile_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing MemberProfile entity.
     *
     * @Route("/profile-update", name="profile_update")
     * @Method("PUT")
     * @Template("JariffMemberBundle:MemberProfile:edit.html.twig")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffMemberBundle:MemberProfile')->findOneByMember($this->getUser()->getId());

        if (!$entity) {
            throw $this->createNotFoundException('test1 Unable to find MemberProfile entity.');
        }

        $deleteForm = $this->createDeleteForm($entity->getId());
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('profile_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a MemberProfile entity.
     *
     * @Route("/{id}", name="profile_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JariffMemberBundle:MemberProfile')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('terr Unable to find MemberProfile entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('profile'));
    }

    /**
     * Creates a form to delete a MemberProfile entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('profile_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

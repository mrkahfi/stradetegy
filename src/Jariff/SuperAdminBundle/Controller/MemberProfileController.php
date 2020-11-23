<?php

namespace Jariff\SuperAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\MemberBundle\Entity\MemberProfile;
use Jariff\AdminBundle\Form\MemberProfileType;
use Jariff\MemberBundle\Entity\MemberHistory;

/**
 * @Route("/super-admin/member")
 */
class MemberProfileController extends BaseController
{
    /**
     * @Route(
     *     "/{id}/profile", 
     *     name="admin_member_profile_show",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        // var_dump($this->get('jariff_type_salutation'));die();
        $member = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$member) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        return array(
            'member' => $member,
            'entity' => $member->getProfile(),
        );
    }

    /**
     * Displays a form to edit an existing MemberProfile entity.
     *
     * @Route("/{id}/profile/edit", name="super_admin_member_profile_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $member = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$member) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        $editForm = $this->createEditForm($member->getProfile());

        return array(
            'member' => $member,
            'entity' => $member->getProfile(),
            'form'   => $editForm->createView(),
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
            'action' => $this->generateUrl('admin_member_profile_update', array('id' => $entity->getMember()->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    
    /**
     * Edits an existing MemberProfile entity.
     *
     * @Route("/{id}/profile/update", name="super_admin_member_profile_update")
     * @Method("PUT")
     * @Template("JariffMemberBundle:MemberProfile:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        $editForm = $this->createEditForm($entity->getProfile());
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $uow = $this->em()->getUnitOfWork();
            $uow->computeChangeSets();
            $changesets = $uow->getEntityChangeSet($entity->getProfile());
            foreach ($changesets as $key => $value) {
                $history = new MemberHistory();
                $history->setColumn($key);
                $history->setDescription($this->user()->getName().' update member_profile '.$key.' from "'.$value[0].'" become "'.$value[1].'"');
                $history->setNewValue($value[1]);
                $history->setOldValue($value[0]);
                $history->setTable('member_profile');
                $history->setAdmin($this->user())    ;
                $history->setMember($entity);

                $this->persist($history);
            }

            $this->success('Ok');
            $this->flush();
            return $this->redirectUrl('admin_member_profile_show', array('id' => $entity->getId()));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }
}

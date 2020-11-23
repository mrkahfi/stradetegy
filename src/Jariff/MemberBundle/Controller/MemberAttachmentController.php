<?php

namespace Jariff\MemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\MemberBundle\Entity\MemberAttachment;
use Jariff\MemberBundle\Form\MemberAttachmentType;

/**
 * MemberAttachment controller.
 *
 * @Route("/attachment")
 */
class MemberAttachmentController extends Controller
{

    /**
     * Lists all MemberAttachment entities.
     *
     * @Route("/", name="attachment")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JariffMemberBundle:MemberAttachment')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a MemberAttachment entity.
     *
     * @Route("/{id}", name="attachment_show")
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
}

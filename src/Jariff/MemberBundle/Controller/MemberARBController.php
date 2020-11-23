<?php

namespace Jariff\MemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\MemberBundle\Entity\MemberARB;
use Jariff\MemberBundle\Form\MemberARBType;

/**
 * MemberARB controller.
 *
 * @Route("/arb")
 */
class MemberARBController extends Controller
{

    /**
     * Lists all MemberARB entities.
     *
     * @Route("/", name="arb")
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
     * Finds and displays a MemberARB entity.
     *
     * @Route("/{id}", name="arb_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffMemberBundle:MemberARB')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MemberARB entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }
}

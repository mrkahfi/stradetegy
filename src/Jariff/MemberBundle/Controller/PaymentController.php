<?php

namespace Jariff\MemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\MemberBundle\Entity\Payment;
use Jariff\MemberBundle\Form\PaymentType;

/**
 * Payment controller.
 *
 * @Route("/payment")
 */
class PaymentController extends BaseController
{

    /**
     * Lists all Payment entities.
     *
     * @Route("/", name="payment")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $entities = $this->em()->repo('JariffMemberBundle:Payment')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Payment entity.
     *
     * @Route("/{id}", name="payment_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {

        $entity = $this->em()->repo('JariffMemberBundle:Payment')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Data invalid');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }
}

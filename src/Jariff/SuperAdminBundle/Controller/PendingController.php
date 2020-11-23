<?php

namespace Jariff\SuperAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


use Symfony\Component\Form\FormError;

use Doctrine\ORM\EntityRepository;
use Jariff\MemberBundle\Entity\Pending;
use Jariff\AdminBundle\Form\PendingType;

use Jariff\MemberBundle\Entity\Member;

/**
 * @Route("/super-admin/pending")
 * Pending controller.
 */
class PendingController extends BaseController
{
    /**
     * activate a Pending entity.
     *
     * @Route(
     *     "/activate/{token}", 
     *     name="admin_pending_activate",
     *     options = {"expose"=true}
     * )
     * @Template()
     */
    public function activateAction(Request $request, $token)
    {
        $entity = $this->repo('JariffMemberBundle:Pending')->findOneByToken($token);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_pending_index');
        }

        $form = $this->createFormBuilder(array())
            ->add(
                    'payment',
                    'entity',
                    array(
                        'required'  => true,
                        'class'     => 'JariffMemberBundle:Payment',
                        'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('p')->where('p.member is null');
                        },
                        'attr' => array(
                            'class' => 'select2',
                            'style' => 'width:90%'
                        ),
                        'label' => 'Choose not claimed payment below :'
                    ))
            ->add('activate', 'submit', array('label' => 'Activate', 'attr' => array( 'class' => 'btn btn-large btn-primary')))
           ->setMethod('POST')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $post = $form->getData();
            $payment = $this->repo('JariffMemberBundle:Payment')->find($post['payment']);

            if($payment->getAmount() < $entity->getTotalPrice()){
                $form->addError(new FormError('Insufficent amount of money, user have to pay '.$entity->getTotalPrice().'$ but your selected payment just '.$payment->getAmount().'$'));
            }
            if($payment->getType() != $entity->getPayment()){
                $form->addError(new FormError('User choose to pay with '.$entity->getPayment().', but your payment data is '.$payment->getType()));
            }
        }

        if ($form->isValid()) {
            $activation = $this->get('jariff_activation');
            try {
                if ($activation->activate($entity->getId(), $payment->getId())) {
                    $this->em()->flush();
                    return $this->redirect($this->generateUrl('admin_active_index'));
                } else {
                    throw new \Exception('Activation failed');
                }
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }

        return array(
                'entity' => $entity,
                'form'   => $form->createView(),
            );
    }

    /**
     * @Route(
     *     "/json-index",
     *     name = "super_admin_pending_json_index",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     *
     */
    public function jsonIndexAction(Request $request)
    {

        $qb = $this->repo('JariffMemberBundle:Pending')
            ->createQueryBuilder('p')
            ->select('p');

        // sorting
        $sorters = json_decode($request->get('pq_sort'));
        foreach ($sorters as $sorter){
            $dir = ($sorter->dir == "up") ? 'asc' : 'desc';
            $qb->addOrderBy('p.'.$sorter->dataIndx, $dir);
        }
        // end sortingMemberEmailAlias
        // filtering
        if (($pq_filter = $request->get('pq_filter')) !== null) {
            $filterObj = json_decode($pq_filter);
            $filters   = $filterObj->data;
             
            foreach ($filters as $filter) {            
                $dataIndx = $filter->dataIndx;  
                $qb->andWhere('p.'.$dataIndx.' like :'.$dataIndx)
                   ->setParameter($dataIndx, '%'.$filter->value.'%');
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
                'email'            => $r->getEmail(),
                'salutation'       => $r->getSalutation(),
                'firstName'        => $r->getFirstName(),
                'lastName'         => $r->getLastName(),
                'companyName'      => $r->getCompanyName(),
                'country'          => $r->getCountry(),
                'phone'            => $r->getPhone(),
                'dateRegistration' => $r->getDateRegistration()->format('Y/m/d'),
                'payment'          => $r->getPayment(),
                'ccType'           => $r->getCcType(),
                'number'           => $r->getNumber(),
                'expired'          => $r->getExpired()->format('Y/m'),
                'secureCode'       => $r->getSecureCode(),
                'everythingPlan'   => $r->getEverythingPlan(),
                'customPlan'       => $r->getCustomPlan(),
                'history'          => $r->getHistory(),
                'search'           => $r->getSearch(),
                'download'         => $r->getDownload(),
                'bigPicture'       => $r->getBigPicture(),
                'paymentTerm'      => $r->getPaymentTerm(),
                'month'            => $r->getMonth(),
                'token'            => $r->getToken(),
            );
        }

        $merge = array_merge($identify, array('data' => $data));

        return $this->json($merge);
    }

    /**
     * Lists all Pending entities.
     *
     * @Route("/index", name="super_admin_pending_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $format = $request->query->get('type');

        if($format == 'ajax'){
            return $this->render(
                'JariffAdminBundle:Pending:index-detail.html.twig',
                array(
                )
            );
        }

        return $this->render(
                'JariffAdminBundle:Pending:index.html.twig',
            array(
            )
        );
    }
    
    /**
     * Creates a new Pending entity.
     *
     * @ Route("/create", name="super_admin_pending_create")
     * @Method("POST")
     * @Template("JariffMemberBundle:Pending:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Pending();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('pending_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Pending entity.
    *
    * @param Pending $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Pending $entity)
    {
        $form = $this->createForm(new PendingType(), $entity, array(
            'action' => $this->generateUrl('pending_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Pending entity.
     *
     * @ Route("/new", name="super_admin_pending_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Pending();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Pending entity.
     *
     * @Route(
     *     "/show/{token}", 
     *     name="admin_pending_show",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     */
    public function showAction(Request $request, $token)
    {
        $entity = $this->repo('JariffMemberBundle:Pending')->findOneByToken($token);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_pending_index');
        }

        $format = $request->query->get('type');

        if($format == 'ajax'){
            return $this->render(
                'JariffAdminBundle:Pending:show-detail.html.twig',
                array(
                    'entity'      => $entity,
                )
            );
        }

        return $this->render(
                'JariffAdminBundle:Pending:show.html.twig',
            array(
                'entity'      => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing Pending entity.
     *
     * @Route(
     *     "/edit/{token}", 
     *     name="admin_pending_edit",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     * @Template()
     */
    public function editAction($token)
    {
        $entity = $this->repo('JariffMemberBundle:Pending')->findOneByToken($token);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_pending_index');
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
    * Creates a form to edit a Pending entity.
    *
    * @param Pending $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Pending $entity)
    {
        $form = $this->createForm(new PendingType(), $entity, array(
            'action' => $this->generateUrl('admin_pending_update', array('token' => $entity->getToken())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Pending entity.
     *
     * @Route("/update/{token}", name="super_admin_pending_update")
     * @Method("PUT")
     * @Template("JariffAdminBundle:Pending:edit.html.twig")
     */
    public function updateAction(Request $request, $token)
    {
        $entity = $this->repo('JariffMemberBundle:Pending')->findOneByToken($token);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_pending_index');
        }

        $deleteForm = $this->createDeleteForm($token);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->em()->flush();
            $this->success("Update successful");
            return $this->redirect($this->generateUrl('admin_pending_index'));
            // return $this->redirect($this->generateUrl('admin_pending_edit', array('token' => $token)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Pending entity.
     *
     * @Route("/delete/{token}", name="super_admin_pending_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $token)
    {
        $form = $this->createDeleteForm($token);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $this->repo('JariffMemberBundle:Pending')->findOneByToken($token);

            if (!$entity) {
                return $this->errorRedirect('Unable to find requested data.', 'admin_pending_index');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_pending_index'));
    }

    /**
     * Creates a form to delete a Pending entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($token)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_pending_delete', array('token' => $token)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

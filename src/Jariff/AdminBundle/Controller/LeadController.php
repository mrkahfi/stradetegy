<?php

namespace Jariff\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\Query\Expr;

use Jariff\AdminBundle\Entity\Lead;
use Jariff\AdminBundle\Entity\LeadContact;
use Jariff\AdminBundle\Entity\LeadHistory;
use Jariff\AdminBundle\Form\LeadType;

use Jariff\MemberBundle\Entity\Member;
use Jariff\MemberBundle\Entity\MemberProfile;

/**
 * @Route("/admin/lead")
 */
class LeadController extends BaseController
{

    /**
     * Displays a form to convert an existing Lead entity.
     *
     * @Route("/{id}/convert-to-client", name="admin_lead_convert")
     * @Template()
     */
    public function convertAction($id)
    {

        $entity = $this->repo('JariffAdminBundle:Lead')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Invalid lead data', 'admin_lead_index');
        }

        if ($entity->getMember()) {
            return $this->errorRedirect('Lead already converted to client', 'admin_lead_index');
        }

        // copy to new member
        $member = new Member();

        // set the password
        $member->setPassword($this->get('jariff_password_encoder')->encodePassword($member->getPassword(), $member->getSalt()));

        if (!$entity->getContact()) {
            return $this->errorRedirect('This lead\'s doesn\'t have contact', 'admin_lead_edit', array('id' => $entity->getId()));
        }

        // check the email
        if (!$entity->getContact()->first()->getEmail()) {
            return $this->errorRedirect('This lead\'s first contact doesn\'t have email address', 'admin_lead_edit', array('id' => $entity->getId()));
        }

        // check the phone
        if (!$entity->getContact()->first()->getPhone()) {
            return $this->errorRedirect('This lead\'s first contact doesn\'t have phone number', 'admin_lead_edit', array('id' => $entity->getId()));
        }

        $member->setEmail($entity->getContact()->first()->getEmail());
        $member->setNumber($entity->getNumber());

        // set member profile
        $profile = new MemberProfile();
        $profile->setPhone($entity->getContact()->first()->getPhone());
        $profile->setFirstName($entity->getContact()->first()->getFirstName());
        $profile->setLastName($entity->getContact()->first()->getLastName());
        $profile->setCompanyName($entity->getBusiness());
        // $profile->setSalutation();
        $profile->setEmail($entity->getContact()->first()->getEmail());
        $profile->setState($entity->getContact()->first()->getCity());
        $profile->setStatus('null');
        $profile->setDateRegistration($entity->getDateCreate());
        $profile->setManager($this->user());
        $profile->setCountry($entity->getContact()->first()->getCountry());

        $member->setProfile($profile);
        $member->setLead($entity);

        // karena sudah ada lead, kelihatannya member origin tidak lagi diperlukan
        // $member->setOrigin();

        $this->persist($member);
        try {
            $history = new LeadHistory();
            $history->setColumn('member');
            $history->setNewValue($member->getNumber());
            $history->setOldValue('null');
            $history->setTable('lead');
            $history->setAdmin($this->user());
            $history->setLead($entity);
            $this->persist($history);

            $this->flush();
            return $this->successRedirect('Ok. New member created, please check again.', 'admin_member_profile_edit', array('id' => $member->getId()));
        } catch (Exception $e) {
            var_dump($e->getMessage());die();
            return $this->errorRedirect('Error', 'admin_lead_index');
        }
    }

    /**
     * @Route(
     *     "/json-index",
     *     name = "admin_lead_json_index",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     */
    public function jsonIndexAction(Request $request)
    {

        $qb = $this->repo('JariffAdminBundle:Lead')
            ->createQueryBuilder('lead')
            ->select('lead')
            ->leftJoin('lead.member', 'member')
            ->leftJoin('lead.contact', 'contact')
            ->leftJoin('lead.sales', 'sales')
            ->leftJoin('lead.activity', 'activity', Expr\Join::WITH, 'activity.status = \'Complete\'')
            ->where('lead.member is null')
            ;

        // sorting
        $sorters = json_decode($request->get('pq_sort'));
        foreach ($sorters as $sorter){
            $dir = ($sorter->dir == "up") ? 'asc' : 'desc';
            $qb->addOrderBy('lead.'.$sorter->dataIndx, $dir);
        }
        // end sortingMemberEmailAlias
        // filtering
        if (($pq_filter = $request->get('pq_filter')) !== null) {
            $filterObj = json_decode($pq_filter);
            $filters   = $filterObj->data;
             
            foreach ($filters as $filter) {            
                $dataIndx = $filter->dataIndx;  
                $qb->andWhere('lead.'.$dataIndx.' like :'.$dataIndx)
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
            $contactName     = '---';
            $contactCallTime = '---';
            if ($r->getContact()->first()) {
                $contactName = $r->getContact()->first()->getFirstName().', '.$r->getContact()->first()->getLastName();
                if ($r->getContact()->first()->getCallTime()) {
                    $contactCallTime = $r->getContact()->first()->getCallTime()->getName();
                }
            }
            $data[] = array(
                'id'                     => $r->getId(),
                'business'               => $r->getBusiness(),
                'dateCreate'             => $r->getDateCreate()->format('Y/m/d'),
                'flag.name'              => is_null($r->getFlag())?'':$r->getFlag()->getName(),
                'contact.name'           => $contactName,
                'sales.name'             => empty($r->getSales()->first())?'':$r->getSales()->first()->getSales()->getName(),
                'activity.dateCompleted' => empty($r->getActivity()->last())?'':(empty($r->getActivity()->last()->getDateCompleted())?'':$r->getActivity()->last()->getDateCompleted()->format('Y-m-d')),
                'contact.callTime'       => $contactCallTime,
                'lead.stage'             => is_null($r->getStage())?'':$r->getStage()->getName(),
            );
        }

        $merge = array_merge($identify, array('data' => $data));

        return $this->json($merge);
    }

    /**
     * Lists all Lead entities.
     *
     * @Route("/index", name="admin_lead_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array(
        );
    }

    /**
     * Creates a new Lead entity.
     *
     * @Route("/create", name="admin_lead_create")
     * @Template("JariffAdminBundle:Lead:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Lead();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->persist($entity);
            $this->flush();
            $this->success('Data saved');

            return $this->redirect($this->generateUrl('lead_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Lead entity.
    *
    * @param Lead $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Lead $entity)
    {
        $form = $this->createForm(new LeadType(), $entity, array(
            'action' => $this->generateUrl('admin_lead_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Lead entity.
     *
     * @Route("/new", name="admin_lead_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Lead();
        // $contact = new LeadContact();
        // $entity->addContact($contact);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Lead entity.
     *
     * @Route(
     *     "/{id}/show", 
     *     name="admin_lead_show",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->repo('JariffAdminBundle:Lead')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Invalid data');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing Lead entity.
     *
     * @Route("/{id}/edit", name="admin_lead_edit")
     * @Template("JariffAdminBundle:Lead:new.html.twig")
     */
    public function editAction($id)
    {

        $entity = $this->repo('JariffAdminBundle:Lead')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Invalid data');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Lead entity.
    *
    * @param Lead $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Lead $entity)
    {
        $form = $this->createForm(new LeadType(), $entity, array(
            'action' => $this->generateUrl('admin_lead_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Lead entity.
     *
     * @Route("/update/{id}", name="admin_lead_update")
     * @Template("JariffAdminBundle:Lead:new.html.twig")
     */
    public function updateAction(Request $request, $id)
    {

        $entity = $this->repo('JariffAdminBundle:Lead')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Invalid data');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $uow = $this->em()->getUnitOfWork();
            $uow->computeChangeSets();
            $changesets = $uow->getEntityChangeSet($entity);
            foreach ($changesets as $key => $value) {
                $history = new LeadHistory();
                $history->setColumn($key);
                $history->setNewValue($value[1]);
                $history->setOldValue($value[0]);
                $history->setTable('lead');
                $history->setAdmin($this->user());
                $history->setLead($entity);
                $this->persist($history);
            }

            $this->flush();
            $this->success('Data saved');

            return $this->redirect($this->generateUrl('admin_lead_edit', array('id' => $id)));
        }
        $this->error('Error');

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
}

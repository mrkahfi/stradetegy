<?php

namespace Jariff\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\TextColumn;

use Jariff\AdminBundle\Entity\LeadContact;
use Jariff\AdminBundle\Entity\Lead;

/**
 * @Route("/admin/inbound")
 */
class InboundController extends BaseController
{
    /**
     * @Route(
     *     "/json-index",
     *     name = "admin_inbound_json_index",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     */
    public function jsonIndexAction(Request $request)
    {

        $qb = $this->repo('JariffAdminBundle:Inbound')
            ->createQueryBuilder('i')
            ->select('i')
            ->where('i.lead is null');

        // sorting
        $sorters = json_decode($request->get('pq_sort'));
        foreach ($sorters as $sorter){
            $dir = ($sorter->dir == "up") ? 'asc' : 'desc';
            $qb->addOrderBy($sorter->dataIndx, $dir);
        }
        // end sorting

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
                'i.business'    => $r->getBusiness(),
                'i.dateCreate'  => is_null($r->getDateCreate()) ? '' : $r->getDateCreate()->format('Y-m-d H:i:s'),
                'i.dateFinish'  => is_null($r->getDateFinish()) ? '' : $r->getDateFinish()->format('Y-m-d H:i:s'),
                'i.dateUpdate'  => is_null($r->getDateUpdate()) ? '' : $r->getDateUpdate()->format('Y-m-d H:i:s'),
                'i.description' => $r->getDescription(),
                'i.email'       => $r->getEmail(),
                'i.flag'        => $r->getFlag(),
                'i.ipAddress'   => $r->getIpAddress(),
                'i.lead'        => is_null($r->getLead()) ? 'null' : $r->getLead()->getNumber(),
                'i.phone'       => $r->getPhone(),
                'i.queue'       => $r->getQueue(),
                'i.source'      => $r->getSource(),
                'i.timeLapsed'  => $r->getTimeLapsed(),
                // 'i.visitedPage' => $r->getVisitedPage(),
            );
        }

        $merge = array_merge($identify, array('data' => $data));

        return $this->json($merge);
    }

    /**
     * @Route(
     *     "/index", 
     *     name="admin_inbound_index"
     * )
     * @Template()
     */
    public function indexAction()
    {
        $source = new Entity('JariffAdminBundle:Inbound');
        $tableAlias = $source->getTableAlias();
        $source->manipulateQuery(
            function ($query) use ($tableAlias)
            {
                $query->andWhere($tableAlias . '.lead is null');
            }
        );

        $grid = $this->get('grid');
        $rowAction = new RowAction('Show', 'admin_inbound_show', false, '_self', array('class' => 'grid_delete_action'));
        $grid->addRowAction($rowAction);

        $grid->setSource($source);

        // $MyTypedColumn = new TextColumn(array('id' => 'business', 'field' => 'business', 'source' => true));
        // $grid->addColumn($MyTypedColumn);
        return $grid->getGridResponse('JariffAdminBundle:Inbound:index.html.twig');
    }

    /**
     * @Route(
     *     "/{id}/attach", 
     *     name="admin_inbound_attach"
     * )
     * @Method("GET")
     * @Template()
     */
    public function attachAction($id)
    {
        $inbound = $this->repo('JariffAdminBundle:Inbound')->find($id);

        if (!$inbound) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_inbound_index');
        }

        $this->success('Please select lead from table then click attach icon in lead you want to be attached');
        
        return array(
            'inbound'      => $inbound,
        );
    }

    /**
     * @Route(
     *     "/{id}/attach-to-lead/{lead}", 
     *     name="admin_inbound_attach_to_lead",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     * @Template()
     */
    public function attachToLeadAction($id, $lead)
    {
        $inbound = $this->repo('JariffAdminBundle:Inbound')->find($id);
        if (!$inbound) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_inbound_index');
        }

        $lead = $this->repo('JariffAdminBundle:Lead')->find($lead);
        if (!$lead) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_inbound_index');
        }

        if ($inbound->getLead()) {
            return $this->errorRedirect('Inbound already attached to Lead.', 'admin_inbound_index');
        }

        $inbound->setLead($lead);
        $inbound->setQueue('sales');
        $this->flush();
        
        return $this->successRedirect('Ok. Inbound attached to Lead.', 'admin_lead_show', array('id' => $lead->getId()));
    }


    /**
     * @Route(
     *     "/{id}/previous-client", 
     *     name="admin_inbound_previous_client"
     * )
     * @Method("GET")
     * @Template()
     */
    public function previousAction($id)
    {
        $inbound = $this->repo('JariffAdminBundle:Inbound')->find($id);

        if (!$inbound) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_inbound_index');
        }

        $this->success('Please select member from table then click client icon you want to be assigned');
        
        return array(
            'inbound' => $inbound,
        );
    }

    /**
     * @Route(
     *     "/{id}/previous-client-submit/{member}", 
     *     name="admin_inbound_previous_client_submit",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     * @Template()
     */
    public function previousSubmitAction($id, $member)
    {
        $inbound = $this->repo('JariffAdminBundle:Inbound')->find($id);
        if (!$inbound) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_inbound_index');
        }

        $member = $this->repo('JariffMemberBundle:Member')->find($member);
        if (!$member) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_inbound_index');
        }

        $inbound->setMember($member);
        $this->flush();
        
        return $this->successRedirect('Ok. Inbound previous client done', 'admin_member_profile_show', array('id' => $member->getId()));
    }

    /**
     * @Route(
     *     "/{id}/convert", 
     *     name="admin_inbound_convert"
     * )
     * @Method("GET")
     * @Template()
     */
    public function convertAction($id)
    {
        $inbound = $this->repo('JariffAdminBundle:Inbound')->find($id);

        if (!$inbound) {
            return $this->errorRedirect('Unable to find requested data', 'admin_inbound_index');
        }

        if ($inbound->getPhone() or $inbound->getEmail() or $inbound->getBusiness() ) {
            $lead = new Lead();
            $lead->setCompany($inbound->getBusiness());
            $lead->setPhone($inbound->getPhone());
            $lead->setEmail($inbound->getEmail());

            $inbound->setLead($lead);
            $inbound->setQueue('sales');

            $this->persist($lead);
            $this->flush();

            return $this->successRedirect('Inbound converted to Lead', 'admin_lead_show', array('id' => $lead->getId()));
        } else {
            return $this->errorRedirect('This inbound has no phone, email, neither business name', 'admin_inbound_index');
        }
    }

    /**
     * @Route(
     *     "/{id}/show", 
     *     name="admin_inbound_show",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $inbound = $this->repo('JariffAdminBundle:Inbound')->find($id);

        if (!$inbound) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_inbound_index');
        }
        
        return array(
            'inbound'      => $inbound,
        );
    }

    /**
     * @Route(
     *     "/{id}/check-flag", 
     *     name="admin_inbound_check_flag"
     * )
     * @Method("GET")
     */
    public function checkAction($id)
    {
        $inbound = $this->repo('JariffAdminBundle:Inbound')->find($id);

        if (!$inbound) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_inbound_index');
        }

        if ($inbound->getEmail()) {
            if ($member = $this->repo('JariffMemberBundle:Member')->findOneByEmail($inbound->getEmail())) {
                $inbound->setFlag('red');
                $inbound->setMember($member);
                $inbound->setDescription(
                    date('Y-m-d H:i:s').
                    ' Red flag, email '.$inbound->getEmail().' same with member #'.$member->getNumber().
                    '<br/>'.PHP_EOL.
                    $inbound->getDescription());
            } elseif($leadContact = $this->repo('JariffAdminBundle:LeadContact')->findOneByEmail($inbound->getEmail())) {
                $inbound->setFlag('blue');
                $inbound->setLead($leadContact->getLead());
                $inbound->setDescription(
                    date('Y-m-d H:i:s').
                    ' Blue flag, email '.$inbound->getEmail().' same with lead id '.$leadContact->getLead()->getId().
                    '<br/>'.PHP_EOL.
                    $inbound->getDescription());
            } else {
                return $this->errorRedirect('No member neither lead found with same inbound email address', 'admin_inbound_show', array('id' => $id));
            }
            $this->flush();
        } else {
            return $this->errorRedirect('No email address data in inbound', 'admin_inbound_show', array('id' => $id));
        }

        return $this->successRedirect('Flag checked', 'admin_inbound_show', array('id' => $id));
    }

    /**
     * @Route(
     *     "/{id}/queue/{queue}", 
     *     name="admin_inbound_queue",
     *     requirements = {
     *       "id"  : "\d+",
     *       "queue": "sales|billing|cs"
     *     }
     * )
     * @Method("GET")
     */
    public function queueAction($id, $queue)
    {
        $inbound = $this->repo('JariffAdminBundle:Inbound')->find($id);

        if (!$inbound) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_inbound_index');
        }

        if (empty($inbound->getQueue())) {
            $inbound->setQueue($queue);
            $this->flush();

            return $this->successRedirect('Inbound queued to '.$queue, 'admin_inbound_show', array('id' => $id));
        } else {
            return $this->errorRedirect('Inbound already queued', 'admin_inbound_show', array('id' => $id));
        }
    }

    /**
     * @Route(
     *     "/{id}/status/{status}", 
     *     name="admin_inbound_status",
     *     requirements = {
     *       "id"  : "\d+",
     *       "status": "qualified|answered|unqualified"
     *     }
     * )
     * @Method("GET")
     */
    public function statusAction($id, $status)
    {
        $inbound = $this->repo('JariffAdminBundle:Inbound')->find($id);

        if (!$inbound) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_inbound_index');
        }

        $inbound->setStatus($status);
        $this->flush();

        return $this->successRedirect('Inbound status : '.$status, 'admin_inbound_show', array('id' => $id));
    }

    /**
     * @Route(
     *     "/{id}/answer/{message}", 
     *     name="admin_inbound_quick_answer",
     *     requirements = {
     *       "id"  : "\d+",
     *       "message": "wrong-number|freight-forwarder|foreign-suppliers"
     *     }
     * )
     * @Method("GET")
     */
    public function quickAnswerAction($id, $message)
    {
        $inbound = $this->repo('JariffAdminBundle:Inbound')->find($id);

        if (!$inbound) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_inbound_index');
        }

        if (!is_null($inbound->getDateFinish())) {
            return $this->errorRedirect('Already finished in '.$inbound->getDateFinish()->format('Y-m-d H:i:s'), 'admin_inbound_show', array('id' => $id));
        }

        if ($inbound->getEmail()) {
            $data = $this->getCannedEmail($message);
            $this->sendEmail($inbound->getEmail(), $data['subject'], $data['email']);

            $inbound->setDateFinish(new \DateTime());
            $this->flush();

            return $this->successRedirect('Email sent', 'admin_inbound_show', array('id' => $id));
        } else {
            return $this->errorRedirect('No email address data in inbound', 'admin_inbound_show', array('id' => $id));
        }
    }

    public function getCannedEmail($message)
    {
        switch ($message) {
            case 'wrong-number':
                $subject = 'Response from sTRADEtegy.com - Inquiry';
                $email = <<<EOF
Hi,

Thanks for using the contact form on our website, sTRADEtegy.com.
It appears you may have contacted the wrong company. 
sTRADEtegy gives you the tools to look up importers or suppliers,
to reveal shipping volumes, trade partners, 
or you can look up competitors.

Check out our website at: http://www.stradetegy.com   

sTRADEtegy
EOF;
                break;
            case 'freight-forwarder':
                $subject = 'Response from sTRADEtegy.com - Inquiry';
                $email = <<<EOF
Hi,

Thanks for  your email! 
Are you the person responsible for finding sales leads for your team?
Have you ever used shipping records to target importers and exporters 
to buy your logistics services?

Check out our website at: http://www.stradetegy.com   

sTRADEtegy
EOF;
                break;
            case 'foreign-suppliers':
                $subject = 'Response from sTRADEtegy.com - Inquiry';
                $email = <<<EOF
Hi,

Thanks for your email!
Are you the person responsible for finding sales leads for your team? 
Have you ever used shipping records to identify dependable buyers 
for your products?

Check out our website at: http://www.stradetegy.com   

sTRADEtegy
EOF;
                break;
        }
        return array('subject' => $subject, 'email' => $email);
    }


    public function sendEmail($email, $subject, $message)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('info@stradetegy.com')
            ->setTo($email)
            ->setBcc('ardianys@outlook.com')
            ->setBody($message)
        ;
        $this->get('mailer')->send($message);
    }
}

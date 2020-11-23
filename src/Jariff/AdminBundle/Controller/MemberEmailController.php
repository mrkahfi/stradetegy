<?php

namespace Jariff\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\MemberBundle\Entity\MemberEmail;
use Jariff\MemberBundle\Entity\MemberEmailAlias;
use Jariff\AdminBundle\Form\MemberEmailType;

/**
 * MemberEmail controller.
 */
class MemberEmailController extends BaseController
{
    /**
     * @Route(
     *     "/admin/email/{id}/send", 
     *     name="admin_member_email_send",
     *     options = {"expose"=true}
     * )
     * @Template("JariffAdminBundle:MemberEmail:new.html.twig")
     */
    public function sendAction($id)
    {
        $entity = $this->repo('JariffMemberBundle:MemberEmail')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }
        $member = $entity->getMember();
        $entity->setAddress($member->getEmail());

        // image tracking
        $body = $entity->getBody();
        $newbody = str_replace('</body>', '<img src="'.$this->generateUrl('public_image_show', array('token' => $entity->getToken()), true).'"></body>', $body);
        $entity->setBody($newbody);

        // link alias
        $dom = new \DOMDocument();
        @$dom->loadHTML($entity->getBody());
        $xpath = new \DOMXPath($dom);

        $nodes = $xpath->query('//a/@href');
        foreach($nodes as $href) {
            $url   = $href->nodeValue;
            // echo $url;
            $token = md5(crypt($url.uniqid()));
            
            $map = new MemberEmailAlias();
            $map->setUrl($url);
            $map->setToken($token);
            $map->setMemberEmail($entity);

            $href->nodeValue = $this->generateUrl('public_url_click', array('token' => $token), true);

            $this->persist($map);
        }
        // die();
        $entity->setBody($dom->saveHTML());

        $mail = \Swift_Message::newInstance();
        $mail->setSubject($entity->getSubject())
            ->setFrom('no-reply@stradetegy.com')
            ->setTo($entity->getAddress())
            ->setBcc(array('ardianys@outlook.com'))
            ->setReplyTo('info@stradetegy.com')
            ->addPart($entity->getAltbody())
            ->setBody($entity->getBody())
        ;

            $mailer = $this->container->get('mailer');
            $mailer->send($mail);
            $entity->setDateSend(new \DateTime);
            $this->flush();
            return $this->successRedirect('Mail sent.', 'admin_member_email_show', array('id' => $member->getId()));
        try {
        } catch (\Exception $e) {
            return $this->errorRedirect('Mail send error.', 'admin_member_email_edit', array('id' => $id));
        }
    }

    /**
     * @Route(
     *     "/admin/member/{id}/email/json-index",
     *     name = "admin_member_email_json_index",
     *     options = {"expose"=true}
     * )
     * @Method("GET")
     */
    public function jsonIndexAction(Request $request, $id)
    {

        $qb = $this->repo('JariffMemberBundle:MemberEmail')
            ->createQueryBuilder('m')
            ->select('m')
            ->andWhere('m.member = '.$id);

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
                'm.id'            => $r->getId(),
                'm.altbody'       => $r->getAltbody(),
                'm.dateCreate'    => is_null($r->getDateCreate())?'---':$r->getDateCreate()->format('Y-m-d H:i'),
                'm.dateSend'      => is_null($r->getDateSend())?'---':$r->getDateSend()->format('Y-m-d H:i'),
                'm.subject'       => $r->getSubject(),
                'm.address'       => $r->getAddress(),
                'm.viewDate'      => is_null($r->getViewDate())?'---':$r->getViewDate()->format('Y-m-d H:i'),
                'm.viewIpAddress' => $r->getViewIpAddress(),
                'm.viewOs'        => $r->getViewOs(),
            );
        }

        $merge = array_merge($identify, array('data' => $data));

        return $this->json($merge);
    }

    /**
     * @Route(
     *     "/admin/member/{id}/email", 
     *     name="admin_member_email_show"
     * )
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $member = $this->repo('JariffMemberBundle:Member')->find($id);

        if (!$member) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }
        
        return array(
            'member'      => $member,
        );
    }

    /**
     * Creates a new MemberEmail entity.
     *
     * @Route("/admin/member/email/create", name="admin_member_email_create")
     * @Template("JariffAdminBundle:MemberEmail:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new MemberEmail();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->persist($entity);
            $this->flush();

            $this->success('Ok');
            return $this->redirectUrl('admin_member_email_show', array('id' => $entity->getMember()->getId()));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a MemberEmail entity.
    *
    * @param MemberEmail $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(MemberEmail $entity)
    {
        $form = $this->createForm(new MemberEmailType(), $entity, array(
            'action' => $this->generateUrl('admin_member_email_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Save'));

        return $form;
    }

    /**
     * Displays a form to create a new MemberEmail entity.
     *
     * @Route(
     *     "/admin/member/{id}/email/new",
     *     name = "admin_member_email_new",
     *     requirements = {
     *       "id"  : "\d+"
     *     }
     * )
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $entity = new MemberEmail();
        $member = $this->repo('JariffMemberBundle:Member')->find($id);
        $entity->setMember($member);

        $body   = $this->renderView('JariffAdminBundle:MemberEmail:template.html.twig', array(
            'member'  => $member
        ));
        $altbody   = $this->renderView('JariffAdminBundle:MemberEmail:template.txt.twig', array(
            'member'  => $member
        ));
        $entity->setBody($body);
        $entity->setAltbody($altbody);

        $entity->setSubject('[Stradetegy] ');

        $form   = $this->createCreateForm($entity);

        return array(
            'member' => $member,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing MemberEmail entity.
     *
     * @Route(
     *     "/admin/email/{id}/edit", 
     *     name="admin_member_email_edit",
     *     options = {"expose"=true}
     * )
     * @Template("JariffAdminBundle:MemberEmail:new.html.twig")
     */
    public function editAction($id)
    {
        $entity = $this->repo('JariffMemberBundle:MemberEmail')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'member' => $entity->getMember(),
            'entity' => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a MemberEmail entity.
    *
    * @param MemberEmail $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MemberEmail $entity)
    {
        $form = $this->createForm(new MemberEmailType(), $entity, array(
            'action' => $this->generateUrl('email_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing MemberEmail entity.
     *
     * @Route("/admin/email/{id}/update", name="email_update")
     * @Method("PUT")
     * @Template("JariffAdminBundle:MemberEmail:new.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->repo('JariffMemberBundle:MemberEmail')->find($id);

        if (!$entity) {
            return $this->errorRedirect('Unable to find requested data.', 'admin_member_index');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->flush();
            $this->success('');

            return $this->redirectUrl('admin_member_email_edit', array('id' => $id));
        }

        return array(
            'member' => $entity->getMember(),
            'entity' => $entity,
            'form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a MemberEmail entity.
     *
     * @Route("/admin/email{id}", name="admin_member_email_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity = $em->getRepository('JariffMemberBundle:MemberEmail')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MemberEmail entity.');
            }

            $em->remove($entity);
            $this->flush();
        }

        return $this->redirectUrl('email');
    }

    /**
     * Creates a form to delete a MemberEmail entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_member_email_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

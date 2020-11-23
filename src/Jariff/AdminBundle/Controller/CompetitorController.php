<?php

namespace Jariff\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jariff\AdminBundle\Entity\Competitor;
use Jariff\AdminBundle\Form\CompetitorType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Competitor controller.
 *
 * @Route("/admin/competitor")
 */
class CompetitorController extends Controller
{

    /**
     * Lists all Competitor entities.
     *
     * @Route("/chart/{from}/{to}", name="competitor_chart", options={"expose"=true}))
     * @Method("GET|POST")
     * @Template()
     */
    public function indexAction($from='', $to='')
    {
        $em = $this->getDoctrine()->getManager();

        $series = array();
        $entities = $em->getRepository('JariffAdminBundle:Competitor')->findAll();
        $i = 0;
        $dates = array();
        $lastDayDiffHtmls = array();
        $lastDayDiffs = array();
        $lastWeekDiffHtmls = array();
        $lastWeekDiffs = array();

        $lastMonthiffHtmls = array();
        $lastMonthDiffs = array();

        $lastThreeMonthsDiffHtmls = array();
        $lastThreeMonthsDiffs = array();

        $mins = array();
        foreach ($entities as $entity) {
            $data = array();
            $ranks = $em->getRepository('JariffAdminBundle:DailyRank')->findByCompetitorOrderByDate($entity->getId(), $from, $to);
            foreach ($ranks as $rank) {
                $data[] = array_reverse($rank->getAlexaRank());
                if ($i == 0) {
                    $date = $rank->getDate();
                    $dates[] = $date->format("d M");
                }
            }
            // var_dump($ranks);
            // die();
            $lastDayDiff = $data[count($data)-2] - $data[count($data)-1];
            $lastWeekDiff = $data[count($data)-8] - $data[count($data)-1];
            $lastMonthDiff = $lastWeekDiff;
            $lastThreeMonthsDiff = $lastMonthDiff;
            if (count($ranks) > 30) {
                $lastMonthDiff = $data[count($data)-31] - $data[count($data)-1];
                if (count($ranks) > 93) {
                    $lastThreeMonthsDiff = $data[count($data)-94] - $data[count($data)-1];
                } else {
                    $lastThreeMonthsDiff = $lastMonthDiff;
                }
            } else {
                $lastMonthDiff = $lastWeekDiff;
            }
            $item = new \stdClass();
            $item->data = $data;
            $mins[] = min($item->data);
            $lastDayDiffHtml = "";
            
            if ($lastDayDiff > 0) {
                $lastDayDiffHtml = "<div class='green-triangle' style='margin-right:5px;' />";
                $lastDayDiffHtmls[] = $lastDayDiffHtml;
            } else {
                $lastDayDiffHtml = "<div class='red-triangle' style='float:left; margin-right:5px;' />";
                $lastDayDiffHtmls[] = $lastDayDiffHtml;
            }

            $lastWeekDiffHtml = "";
            if ($lastWeekDiff > 0) {
                $lastWeekDiffHtml = "<div class='green-triangle' style='margin-right:5px;' />";
                $lastWeekDiffHtmls[] = $lastWeekDiffHtml;
            } else {
                $lastWeekDiffHtml = "<div class='red-triangle' style='float:left; margin-right:5px;' />";
                $lastWeekDiffHtmls[] = $lastWeekDiffHtml;
            }

            $lastMonthDiffHtml = "";
            if ($lastMonthDiff > 0) {
                $lastMonthDiffHtml = "<div class='green-triangle' style='margin-right:5px;' />";
                $lastMonthDiffHtmls[] = $lastMonthDiffHtml;
            } else {
                $lastMonthDiffHtml = "<div class='red-triangle' style='float:left; margin-right:5px;' />";
                $lastMonthDiffHtmls[] = $lastMonthDiffHtml;
            }

            $lastThreeMonthsDiffHtml = "";
            if ($lastThreeMonthsDiff > 0) {
                $lastThreeMonthsDiffHtml = "<div class='green-triangle' style='margin-right:5px;' />";
                $lastThreeMonthsDiffHtmls[] = $lastThreeMonthsDiffHtml;
            } else {
                $lastThreeMonthsDiffHtml = "<div class='red-triangle' style='float:left; margin-right:5px;' />";
                $lastThreeMonthsDiffHtmls[] = $lastThreeMonthsDiffHtml;
            }


            $lastDayDiffs[] = abs($lastDayDiff);
            $lastWeekDiffs[] = abs($lastWeekDiff);
            $lastMonthDiffs[] = abs($lastMonthDiff);
            $lastThreeMonthsDiffs[] = abs($lastThreeMonthsDiff);
            $item->name = $entity->getWebsite();
            $series[] = $item;
            $i++;
        }
        $min = min($mins);
        // var_dump($lastWeekDiffs);
        // die();
        return array(
            'entities' => $entities,
            'series' => $series,
            'categories' => array_reverse($dates),
            'competitors' => $entities,
            'lastDayDiffHtmls' => $lastDayDiffHtmls,
            'lastDayDiffs' => $lastDayDiffs,
            'lastWeekDiffHtmls' => $lastWeekDiffHtmls,
            'lastWeekDiffs' => $lastWeekDiffs,
            'lastMonthDiffHtmls' => $lastMonthDiffHtmls,
            'lastMonthDiffs' => $lastMonthDiffs,
            'lastThreeMonthsDiffHtmls' => $lastThreeMonthsDiffHtmls,
            'lastThreeMonthsDiffs' => $lastThreeMonthsDiffs,
            'min' => $min
            );
    }


    /**
     * Lists all Competitor entities.
     *
     * @Route("/chart/json/{from}/{to}", name="competitor_chart_json", options={"expose"=true}))
     * @Method("GET|POST")
     * @Template()
     */
    public function indexJsonAction($from='', $to='')
    {
        $em = $this->getDoctrine()->getManager();

        $series = array();
        $entities = $em->getRepository('JariffAdminBundle:Competitor')->findAll();
        $i = 0;
        $dates = array();

        foreach ($entities as $entity) {
            $data = array();
            $ranks = $em->getRepository('JariffAdminBundle:DailyRank')->findByCompetitorOrderByDate($entity->getId(), $from, $to);
            foreach ($ranks as $rank) {
                $data[] = $rank->getAlexaRank();
                if ($i == 0) {
                    $date = $rank->getDate();
                    $dates[] = $date->format("d M");
                }
            }

            $item = new \stdClass();
            $item->data = $data;
            $item->name = $entity->getWebsite();
            $series[] = $item;
            $i++;
        }

        $json = array('series' => $series, 'categories' => $dates);
        
        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    /**
     * Creates a new Competitor entity.
     *
     * @Route("/", name="competitor_create")
     * @Method("POST")
     * @Template("JariffAdminBundle:Competitor:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Competitor();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('competitor_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            );
    }

    /**
    * Creates a form to create a Competitor entity.
    *
    * @param Competitor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Competitor $entity)
    {
        $form = $this->createForm(new CompetitorType(), $entity, array(
            'action' => $this->generateUrl('competitor_create'),
            'method' => 'POST',
            ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Competitor entity.
     *
     * @Route("/new", name="competitor_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Competitor();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            );
    }

    /**
     * Finds and displays a Competitor entity.
     *
     * @Route("/{id}", name="competitor_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffAdminBundle:Competitor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competitor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            );
    }

    /**
     * Displays a form to edit an existing Competitor entity.
     *
     * @Route("/{id}/edit", name="competitor_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffAdminBundle:Competitor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competitor entity.');
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
    * Creates a form to edit a Competitor entity.
    *
    * @param Competitor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Competitor $entity)
    {
        $form = $this->createForm(new CompetitorType(), $entity, array(
            'action' => $this->generateUrl('competitor_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Competitor entity.
     *
     * @Route("/{id}", name="competitor_update")
     * @Method("PUT")
     * @Template("JariffAdminBundle:Competitor:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JariffAdminBundle:Competitor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competitor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('competitor_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            );
    }
    /**
     * Deletes a Competitor entity.
     *
     * @Route("/{id}", name="competitor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JariffAdminBundle:Competitor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Competitor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('competitor'));
    }

    /**
     * Creates a form to delete a Competitor entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('competitor_delete', array('id' => $id)))
        ->setMethod('DELETE')
        ->add('submit', 'submit', array('label' => 'Delete'))
        ->getForm()
        ;
    }
}

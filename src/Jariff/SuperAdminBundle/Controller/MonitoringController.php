<?php

namespace Jariff\SuperAdminBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/super-admin/monitoring")
 */
class MonitoringController extends BaseController
{
    /**
     * @Route("/user", name="super_admin_monitoring_user", options={"expose"=true})
     * @Template("JariffSuperAdminBundle:Monitoring:user.html.twig")
     */
    public function userAction()
    {
        // $user = simplexml_load_file("https://GCP7HVzVpm5pRh34OOVJ4K0OMqQg5qo1YpoNYvZF:@www.worksnaps.net/api/users/10199.xml");
        // echo $user->login;
    	return array();
    }

    /**
     * @Route("/user-json", name="super_admin_monitoring_user_json", options={"expose"=true})
     */
    public function userJsonAction()
    {
        date_default_timezone_set('Asia/Jakarta');
        $getTimeEntriesUrl = "/projects/#{project_id}/users/10199/time_entries.xml?from_timestamp=xxxxxxxxxx&to_timestamp=yyyyyyyyyy";
        $projectId = "17829";
        $fromTimeStamp = "1402560000";
        $toTimeStamp = "1402585200";
        $getTimeEntriesUrl = str_replace("#{project_id}", $projectId, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("xxxxxxxxxx", $fromTimeStamp, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("yyyyyyyyyy", $toTimeStamp, $getTimeEntriesUrl);
        $url = "https://GCP7HVzVpm5pRh34OOVJ4K0OMqQg5qo1YpoNYvZF:@www.worksnaps.net/api";
        // echo $url . $getTimeEntriesUrl;
        // die(); 
        $timeEntries = simplexml_load_file($url . $getTimeEntriesUrl);
        $entries = array();
        foreach ($timeEntries as $timeEntry) {
            $entries[] = $timeEntry->thumbnail_url;
        }

        $response = new Response(json_encode($entries));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/overview", name="super_admin_monitoring_overview")
     * @Template("JariffSuperAdminBundle:Monitoring:overview.html.twig")
     */
    public function overviewAction()
    {
        date_default_timezone_set('Asia/Jakarta');
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('JariffAdminBundle:Admin')->findAll();


        return array('users' => $users);
    }

    /**
     * @Route("/revenue-detail/{id}", name="super_admin_monitoring_revenue_detail")
     * @Template("JariffSuperAdminBundle:Monitoring:revenue_detail.html.twig")
     */
    public function revenueDetailAction($id)
    {
        date_default_timezone_set('Asia/Jakarta');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('JariffAdminBundle:Admin')->find($id);
        $earnings = $em->getRepository('JariffAdminBundle:Earning')->findBy(array('admin'=>$user), array('id'=>'DESC'),12, null);
        // var_dump(count($earnings));
        // die();

        return array('user' => $user, 'earnings' => $earnings);
    }

    /**
     * @Route("/user-assignment", name="super_admin_monitoring_user_assignment")
     * @Template("JariffSuperAdminBundle:Monitoring:userAssignment.html.twig")
     */
    public function userAssignmentAction()
    {
    	return array();
    }

    /**
     * @Route("/task", name="super_admin_monitoring_task")
     * @Template("JariffSuperAdminBundle:Monitoring:task.html.twig")
     */
    public function taskAction()
    {
    	return array();
    }

    /**
     * @Route("/task-json", name="super_admin_monitoring_task_json", options={"expose"=true})
     */
    public function taskJsonAction()
    {
        date_default_timezone_set('Asia/Jakarta');
        $getTimeEntriesUrl = "/projects/#{project_id}/users/10199/time_entries.xml?from_timestamp=xxxxxxxxxx&to_timestamp=yyyyyyyyyy&time_entry_type=online";
        $projectId = "17829";
        $fromTimeStamp = "1402560000";
        $toTimeStamp = "1402585200";
        $getTimeEntriesUrl = str_replace("#{project_id}", $projectId, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("xxxxxxxxxx", $fromTimeStamp, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("yyyyyyyyyy", $toTimeStamp, $getTimeEntriesUrl);
        $url = "https://GCP7HVzVpm5pRh34OOVJ4K0OMqQg5qo1YpoNYvZF:@www.worksnaps.net/api";
        // echo $url . $getTimeEntriesUrl;
        // die(); 
        $timeEntries = simplexml_load_file($url . $getTimeEntriesUrl);
        $entries = array();
        foreach ($timeEntries as $timeEntry) {
            $entries[] = $timeEntry->thumbnail_url;
        }

        $response = new Response(json_encode($entries));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/user-status-json", name="super_admin_monitoring_user_status_json", options={"expose"=true})
     */
    public function userStatusJsonAction()
    {
        date_default_timezone_set('Asia/Jakarta');
        // /projects/#{project_id}/time_entries.xml?user_ids=12;13;15&
        $getTimeEntriesUrl = "/projects/#{project_id}/time_entries.xml?user_ids=10199;10029&from_timestamp=xxxxxxxxxx&to_timestamp=yyyyyyyyyy";
        $projectId = "17829";
        $toTimeStamp = time();
        $fromTimeStamp = time() -  10 * 60;
        $getTimeEntriesUrl = str_replace("#{project_id}", $projectId, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("xxxxxxxxxx", $fromTimeStamp, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("yyyyyyyyyy", $toTimeStamp, $getTimeEntriesUrl);
        $url = "https://GCP7HVzVpm5pRh34OOVJ4K0OMqQg5qo1YpoNYvZF:@www.worksnaps.net/api";
        // echo date('Y-m-d h:i:s', $fromTimeStamp) ."\n";
        // echo date('Y-m-d h:i:s', $toTimeStamp) ."\n";
        // echo $url . $getTimeEntriesUrl;
        // die(); 
        $timeEntries = simplexml_load_file($url . $getTimeEntriesUrl);
        $entries = array();
        foreach ($timeEntries as $timeEntry) {
            $entries[] = $timeEntry;
        }

        $response = new Response(json_encode($entries));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Route("/user-worktime-json", name="super_admin_monitoring_user_worktime_json", options={"expose"=true})
     */
    public function userWorkTimeJsonAction()
    {
        date_default_timezone_set('Asia/Jakarta');
        // /projects/#{project_id}/time_entries.xml?user_ids=12;13;15&
        $getTimeEntriesUrl = "/projects/#{project_id}/time_entries.xml?user_ids=10199;10029&from_timestamp=xxxxxxxxxx&to_timestamp=yyyyyyyyyy";
        $projectId = "17829";
        $fromTimeStamp = mktime(0,0,0);
        $toTimeStamp = time();
        $getTimeEntriesUrl = str_replace("#{project_id}", $projectId, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("xxxxxxxxxx", $fromTimeStamp, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("yyyyyyyyyy", $toTimeStamp, $getTimeEntriesUrl);
        $url = "https://GCP7HVzVpm5pRh34OOVJ4K0OMqQg5qo1YpoNYvZF:@www.worksnaps.net/api";
        $timeEntries = simplexml_load_file($url . $getTimeEntriesUrl);
        $employees = array();

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('JariffAdminBundle:Admin')->findAll();
        foreach ($users as $user) {
            $minutes = 0;
            foreach ($timeEntries as $timeEntry) {
                if (((int) $timeEntry->user_id) == $user->getWsId()) {
                    $minutes++;
                }
            }
            $employee = new \stdClass();
            $employee->id = $user->getWsId();
            $employee->today = $minutes * 10;
            $employees[] = $employee;
        }


        $getTimeEntriesUrl = "/projects/#{project_id}/time_entries.xml?user_ids=10199;10029&from_timestamp=xxxxxxxxxx&to_timestamp=yyyyyyyyyy";
        $projectId = "17829";
        $fromTimeStamp = mktime(0,0,0) - (7 * 24 * 60 * 60);
        $toTimeStamp = time();
        $getTimeEntriesUrl = str_replace("#{project_id}", $projectId, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("xxxxxxxxxx", $fromTimeStamp, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("yyyyyyyyyy", $toTimeStamp, $getTimeEntriesUrl);
        $url = "https://GCP7HVzVpm5pRh34OOVJ4K0OMqQg5qo1YpoNYvZF:@www.worksnaps.net/api";
        $timeEntries = simplexml_load_file($url . $getTimeEntriesUrl);
        foreach ($employees as $employee) {
            $minutes = 0;
            foreach ($timeEntries as $timeEntry) {
                if (((int) $timeEntry->user_id) == $employee->id) {
                    $minutes++;
                }
            }
            $employee->week = $minutes * 10;
        }

        $getTimeEntriesUrl = "/projects/#{project_id}/time_entries.xml?user_ids=10199;10029&from_timestamp=xxxxxxxxxx&to_timestamp=yyyyyyyyyy";
        $projectId = "17829";
        $fromTimeStamp = time() - (30 * 24 * 60 * 60);
        $toTimeStamp = time();
        $getTimeEntriesUrl = str_replace("#{project_id}", $projectId, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("xxxxxxxxxx", $fromTimeStamp, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("yyyyyyyyyy", $toTimeStamp, $getTimeEntriesUrl);
        $url = "https://GCP7HVzVpm5pRh34OOVJ4K0OMqQg5qo1YpoNYvZF:@www.worksnaps.net/api";
        // echo date('Y-m-d h:i:s', $fromTimeStamp) ."\n";
        // echo date('Y-m-d h:i:s', $toTimeStamp) ."\n";
        // echo $url . $getTimeEntriesUrl;
        // die(); 
        $timeEntries = simplexml_load_file($url . $getTimeEntriesUrl);
        foreach ($employees as $employee) {
            $minutes = 0;
            foreach ($timeEntries as $timeEntry) {
                if (((int) $timeEntry->user_id) == $employee->id) {
                    $minutes++;
                }
            }
            $employee->month = $minutes * 10;
        }


        $response = new Response(json_encode($employees));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Route("/task-assignment", name="super_admin_monitoring_task_assignment")
     * @Template("JariffSuperAdminBundle:Monitoring:taskAssignment.html.twig")
     */
    public function taskAssignmentAction()
    {
        return array();
    }

    /**
     * @Route("/time-entry/{userId}", name="super_admin_monitoring_time_entry")
     * @Template("JariffSuperAdminBundle:Monitoring:timeEntry.html.twig")
     */
    public function timeEntryAction($userId)
    {
        date_default_timezone_set('Asia/Jakarta');
        $hour = date('H', time());
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('JariffAdminBundle:Admin')->findOneByWsId($userId);
        // echo $user->getName();
        // echo $hour;
        // $hour = date('d m Y h:i:s', time());
        // die();
        return array('hour' => intval($hour), 'name' => $user->getName(), 'userId' => $userId);
    }

    /**
     * @Route("/time-entry-json/{userId}", name="super_admin_monitoring_time_entry_json", options={"expose"=true})
     */
    public function timeEntryJsonAction($userId)
    {
        date_default_timezone_set('Asia/Jakarta');
        $getTimeEntriesUrl = "/projects/#{project_id}/users/" . $userId . "/time_entries.xml?from_timestamp=xxxxxxxxxx&to_timestamp=yyyyyyyyyy";
        $projectId = "17829";
        $fromTimeStamp = mktime(0,0,0);
        $toTimeStamp = time();
        $getTimeEntriesUrl = str_replace("#{project_id}", $projectId, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("xxxxxxxxxx", $fromTimeStamp, $getTimeEntriesUrl);
        $getTimeEntriesUrl = str_replace("yyyyyyyyyy", $toTimeStamp, $getTimeEntriesUrl);
        $url = "https://GCP7HVzVpm5pRh34OOVJ4K0OMqQg5qo1YpoNYvZF:@www.worksnaps.net/api";
        // echo date('Y-m-d h:i:s', $fromTimeStamp) ."\n";
        // echo date('Y-m-d h:i:s', $toTimeStamp) ."\n";
        // echo $url . $getTimeEntriesUrl;
        // die(); 
        $timeEntries = simplexml_load_file($url . $getTimeEntriesUrl);
        $entries = array();
        foreach ($timeEntries as $timeEntry) {
            $entries[] = $timeEntry;
        }

        $response = new Response(json_encode($entries));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/time-entry-detail-json/{id}", name="super_admin_monitoring_time_entry_detail_json", options={"expose"=true})
     */
    public function timeEntryDetailJsonAction($id)
    {
        date_default_timezone_set('Asia/Jakarta');
        $getTimeEntriesUrl = "/projects/#{project_id}/time_entries/" . $id . ".xml";
        $projectId = "17829";
        $getTimeEntriesUrl = str_replace("#{project_id}", $projectId, $getTimeEntriesUrl);
        $url = "https://GCP7HVzVpm5pRh34OOVJ4K0OMqQg5qo1YpoNYvZF:@www.worksnaps.net/api";
        // echo $url . $getTimeEntriesUrl;
        // die();
        $timeEntries = simplexml_load_file($url . $getTimeEntriesUrl); 
        $entries = array();
        // foreach ($timeEntries as $timeEntry) {
        //     $entries[] = $timeEntry;
        // }

        $response = new Response(json_encode($timeEntries));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/report", name="super_admin_monitoring_report")
     * @Template("JariffSuperAdminBundle:Monitoring:report.html.twig")
     */
    public function reportAction()
    {
        return array();
    }

}

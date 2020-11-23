<?php

namespace Jariff\AdminBundle\Tests\Controller;

use Jariff\AdminBundle\Tests\Controller\BaseAdminControllerTest;

class MemberEmailControllerTest extends BaseAdminControllerTest
{
    public function testCompleteScenario()
    {
        $this->routerGetAdmin('admin_member_email_new', array('id' => 4));
        $this->routerGetAdmin('admin_member_email_show', array('id' => 4));
        $this->routerGetJsonAdmin('admin_member_email_json_index', array(
					'id'         => 4,
					"pq_curpage" => 1,
					"pq_rpp"     => 20,
					"pq_sort"    => '[{"dataIndx":"m.id","dir":"down"}]',
					"_"          => 1402281604156,
				));
    }
}

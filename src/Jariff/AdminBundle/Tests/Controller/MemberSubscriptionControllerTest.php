<?php

namespace Jariff\AdminBundle\Tests\Controller;

use Jariff\AdminBundle\Tests\Controller\BaseAdminControllerTest;

class AdminMemberProfileTest extends BaseAdminControllerTest
{
    public function testCompleteScenario()
    {
        $this->routerGetAdmin('admin_member_subscription_show', array('id' => 4));
        $this->routerGetJsonAdmin('admin_member_subscription_json_index', array(
					'id'         => 4,
					"pq_curpage" => 1,
					"pq_rpp"     => 20,
					"pq_sort"    => '[{"dataIndx":"subscription.id","dir":"down"}]',
					"_"          => 1402281604156,
				));
    }
}

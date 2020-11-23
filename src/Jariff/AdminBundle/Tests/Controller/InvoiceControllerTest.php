<?php

namespace Jariff\AdminBundle\Tests\Controller;

use Jariff\AdminBundle\Tests\Controller\BaseAdminControllerTest;

class InvoiceControllerTest extends BaseAdminControllerTest
{
    public function testCompleteScenario()
    {
        $this->routerGetAdmin('admin_member_invoice_new', array('id' => 4));
        $this->routerGetJsonAdmin('admin_member_invoice_json_index', array(
					'id'         => 4,
					"pq_curpage" => 1,
					"pq_rpp"     => 20,
					"pq_sort"    => '[{"dataIndx":"invoice.id","dir":"down"}]',
					"_"          => 1402281604156,
				));
    }
}

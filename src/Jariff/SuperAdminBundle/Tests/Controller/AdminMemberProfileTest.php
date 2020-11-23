<?php

namespace Jariff\AdminBundle\Tests\Controller;

use Jariff\AdminBundle\Tests\Controller\BaseAdminControllerTest;

class AdminMemberProfileTest extends BaseAdminControllerTest
{
    public function testCompleteScenario()
    {
        $this->routerGetAdmin('admin_member_profile_show', array('id' => 4));
    }
}

<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Tests\Controller\Helper;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper\CrudHelper;
use LaVestima\HannaAgency\InfrastructureBundle\Tests\BaseWebTestCase;

class CrudHelperTest extends BaseWebTestCase
{
    public function testGeneratePathSlug()
    {
        $pathSlug = CrudHelper::generatePathSlug();

        $this->assertEquals(50, strlen($pathSlug));

        $this->assertRegExp('/([A-Za-z0-9]{49})\w/', $pathSlug);
    }
}
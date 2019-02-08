<?php

namespace App\Tests\Helper;

use App\Helper\CrudHelper;
use PHPUnit\Framework\TestCase;

class CrudHelperTest extends TestCase
{
    public function testGeneratePathSlug()
    {
        $this->assertRegExp('/[A-Za-z0-9]{50}/', CrudHelper::generatePathSlug());
    }
}
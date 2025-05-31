<?php

namespace Tests\Unit\Infra\Request;

use App\Infra\Request\RequestTools;
use Tests\UnitTestCase;

class RequestToolsUnitTest extends UnitTestCase
{
    public function testIsApplicationInDevelopMode()
    {
        $this->assertTrue(RequestTools::isApplicationInDevelopMode());

        config()->set('app.env', 'production');

        $this->assertFalse(RequestTools::isApplicationInDevelopMode());
    }
}

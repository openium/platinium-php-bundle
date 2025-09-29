<?php

namespace Openium\PlatiniumBundle\Tests\Entity;

use Openium\PlatiniumBundle\Entity\PlatiniumPushResponse;
use PHPUnit\Framework\TestCase;

/**
 * Class PlatiniumPushResponseTest
 *
 * @package Openium\PlatiniumBundle\Tests\Entity
 */
class PlatiniumPushResponseTest extends TestCase
{
    public function testPlatiniumPushResponse(): void
    {
        $ppr = new PlatiniumPushResponse(200, 'OK');
        $this->assertTrue($ppr instanceof PlatiniumPushResponse);
        $this->assertEquals(200, $ppr->getStatus());
        $this->assertEquals('OK', $ppr->getResult());
        $ppr->setStatus(201);
        $ppr->setResult('CREATED');
        $this->assertEquals(201, $ppr->getStatus());
        $this->assertEquals('CREATED', $ppr->getResult());
    }
}

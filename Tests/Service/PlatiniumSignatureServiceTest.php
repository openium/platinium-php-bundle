<?php

namespace Openium\PlatiniumBundle\Tests\Service;

use Openium\PlatiniumBundle\Service\PlatiniumSignatureService;
use PHPUnit\Framework\TestCase;

/**
 * Class PlatiniumSignatureServiceTest
 *
 * @package Openium\PlatiniumBundle\Tests\Service
 */
class PlatiniumSignatureServiceTest extends TestCase
{
    public function testCreatePushParam(): void
    {
        $apiServerId = 'server_id';
        $apiServerKey = 'server_key';
        $url = '/api/server/notify.json';
        $params = ['param1' => 'value1', 'param2' => 'value2'];
        $pss = new PlatiniumSignatureService($apiServerId, $apiServerKey);
        $this->assertTrue($pss instanceof PlatiniumSignatureService);
        $result = $pss->createServerSignature($url, $params);
        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));
        self::assertArrayHasKey('x-ws-signature', $result);
        $pattern = "/^WS-Signature UUID=\".*\", Signature=\".*\", Created=\"\d*\"$/";
        $match = preg_match($pattern, $result['x-ws-signature']);
        $this->assertEquals(1, $match);
    }

    public function testCreatePushParamWithoutParamaters(): void
    {
        $apiServerId = 'server_id';
        $apiServerKey = 'server_key';
        $url = '/api/server/notify.json';
        $params = [];
        $pss = new PlatiniumSignatureService($apiServerId, $apiServerKey);
        $this->assertTrue($pss instanceof PlatiniumSignatureService);
        $result = $pss->createServerSignature($url, $params);
        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));
        self::assertArrayHasKey('x-ws-signature', $result);
        $pattern = "/^WS-Signature UUID=\".*\", Signature=\".*\", Created=\"\d*\"$/";
        $match = preg_match($pattern, $result['x-ws-signature']);
        $this->assertEquals(1, $match);
    }
}

<?php

/**
 * PHP Version 7.1, 7.2
 *
 * @package  Openium\PlatiniumBundle\Tests\Service
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace Openium\PlatiniumBundle\Tests\Service;

use Openium\PlatiniumBundle\Service\PlatiniumSignatureService;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class PlatiniumSignatureServiceTest
 *
 * @package Openium\PlatiniumBundle\Tests\Service
 */
class PlatiniumSignatureServiceTest extends TestCase
{
    public function testCreatePushParam()
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
        $pattern = "/^x-ws-signature: WS-Signature UUID=\".*\", Signature=\".*\", Created=\"\d*\"$/";
        $match = preg_match($pattern, $result[0]);
        $this->assertEquals(1, $match);
    }

    public function testCreatePushParamWithoutParamaters()
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
        $pattern = "/^x-ws-signature: WS-Signature UUID=\".*\", Signature=\".*\", Created=\"\d*\"$/";
        $match = preg_match($pattern, $result[0]);
        $this->assertEquals(1, $match);
    }
}

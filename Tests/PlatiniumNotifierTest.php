<?php

/**
 * PHP Version 7.1, 7.2
 *
 * @package  Openium\PlatiniumBundle\Tests
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace Openium\PlatiniumBundle\Tests;

use Openium\PlatiniumBundle\Entity\PlatiniumPushResponse;
use Openium\PlatiniumBundle\PlatiniumClient;
use Openium\PlatiniumBundle\PlatiniumNotifier;
use Openium\PlatiniumBundle\Service\PlatiniumParameterBagService;
use Openium\PlatiniumBundle\Service\PlatiniumSignatureService;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class PlatiniumNotifierTest
 *
 * @package Openium\PlatiniumBundle\Tests
 */
class PlatiniumNotifierTest extends TestCase
{
    /*
     * /!\ Caution
     * Don't commit real config
     */
    public static $apiServerId = "";
    public static $apiServerKey = "";
    public static $tokenDev = "";
    // fake token to avoid real push
    public static $tokenProd = "DontPushInProd";

    private function getRealNotifier(): PlatiniumNotifier
    {
        if (empty(self::$apiServerId) || empty(self::$apiServerKey) || empty(self::$tokenDev)) {
            $this->markTestSkipped(
                'You need to defined server config to execute this test'
            );
        }
        $signatureService = new PlatiniumSignatureService(self::$apiServerId, self::$apiServerKey);
        $client = new PlatiniumClient('https://platinium.openium.fr', $signatureService, 'test');
        $parameterBagService = new PlatiniumParameterBagService('test', self::$tokenDev, self::$tokenProd);
        $notifier = new PlatiniumNotifier(
            $client,
            $parameterBagService,
            '/api/server/notify.json',
            '/api/server/subscribed.json'
        );
        return $notifier;
    }

    private function getMockNotifier(): PlatiniumNotifier
    {
        $signatureService = new PlatiniumSignatureService('MockedServerId', 'MockedServerKey');
        $client = new PlatiniumClient('https://platinium-dev.openium.fr', $signatureService, 'test');
        $parameterBagService = new PlatiniumParameterBagService('test', self::$tokenProd, self::$tokenProd);
        $notifier = new PlatiniumNotifier(
            $client,
            $parameterBagService,
            '/api/server/notify.json',
            '/api/server/subscribed.json'
        );
        return $notifier;
    }

    public function testNotifier()
    {
        $notifier = $this->getRealNotifier();
        $this->assertTrue($notifier instanceof PlatiniumNotifier);
        $result = $notifier->notify('Push from platinium php bundle in test env');
        $this->assertTrue(is_bool($result));
        $this->assertTrue($result);
    }

    public function testSubscriber()
    {
        $notifier = $this->getRealNotifier();
        $this->assertTrue($notifier instanceof PlatiniumNotifier);
        $count = $notifier->subscribed();
        $this->assertTrue(is_int($count));
        $this->assertTrue($count > 0);
    }

    public function testVerifyResponseWithSuccessResponse()
    {
        $notifier = $this->getMockNotifier();
        $responseContent = json_encode(
            [
                'id' => 'ABCD-1234',
                'is_dev' => true,
                'ids_groups' => [],
                'langs' => [],
                'notification_per_minute' => 10,
                'creation_date' => time(),
                'params' => [],
                'tolerance' => null,
                'state' => 1,
                'origin' => '',
                'token_notifications' => []
            ]
        );
        $response = new PlatiniumPushResponse(PlatiniumPushResponse::STATUS_SUCCESS, $responseContent);
        $this->assertTrue($notifier->verifyResponse($response));
    }

    /**
     * @expectedException Openium\PlatiniumBundle\Exception\PushException
     * @expectedExceptionMessage Invalid push data : dev iOS certificate has expired
     */
    public function testVerifyResponseWithFailResponse()
    {
        $notifier = $this->getMockNotifier();
        $response = new PlatiniumPushResponse(1, "Invalid push data : dev iOS certificate has expired");
        $notifier->verifyResponse($response);
    }

    /**
     * @expectedException Openium\PlatiniumBundle\Exception\PushException
     * @expectedExceptionMessage Push Send Failed : JSON Parse Failed.
     */
    public function testVerifyResponseWithWrongResultResponse()
    {
        $notifier = $this->getMockNotifier();
        $response = new PlatiniumPushResponse(0, "Invalid push data : dev iOS certificate has expired");
        $notifier->verifyResponse($response);
    }

    /**
     * @expectedException Openium\PlatiniumBundle\Exception\PushException
     * @expectedExceptionMessage Push Send Failed : invalid result.
     */
    public function testVerifyResponseWithIncompleteResultResponse()
    {
        $notifier = $this->getMockNotifier();
        $responseContent = json_encode(
            [
                'is_dev' => true,
                'ids_groups' => [],
                'langs' => [],
                'notification_per_minute' => 10,
                'creation_date' => time(),
                'params' => [],
                'tolerance' => null,
                'state' => 1,
                'origin' => '',
                'token_notifications' => []
            ]
        );
        $response = new PlatiniumPushResponse(0, $responseContent);
        $notifier->verifyResponse($response);
    }
}

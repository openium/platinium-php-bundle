<?php

/**
 * PHP Version 7.1, 7.2
 *
 * @package  Openium\PlatiniumBundle\Tests\Entity
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace Openium\PlatiniumBundle\Tests\Entity;

use Openium\PlatiniumBundle\Entity\PlatiniumPushNotification;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class PlatiniumPushNotificationTest
 *
 * @package Openium\PlatiniumBundle\Tests\Entity
 */
class PlatiniumPushNotificationTest extends TestCase
{
    public function testPlatiniumPushNotification()
    {
        $ppn = new PlatiniumPushNotification(
            "This is a push message",
            ["key1" => "value1","key2" => "value2"],
            2,
            false,
            'push.mp3'
        );
        $this->assertTrue($ppn instanceof PlatiniumPushNotification);
        $this->assertEquals("This is a push message", $ppn->getMessage());
        $this->assertEquals(2, $ppn->getBadgeValue());
        $this->assertEquals(["key1" => "value1","key2" => "value2"], $ppn->getParamsBag());
        $this->assertEquals('push.mp3', $ppn->getSound());
        $this->assertFalse($ppn->isNewsStand());
        $this->assertEquals('[{"newsstand":0,"message":"This is a push message","sound":"push.mp3","badge":2,"paramsbag":{"key1":"value1","key2":"value2"}}]', $ppn->jsonFormat());
        $ppn->setMessage("This is a new push message");
        $ppn->setBadgeValue(3);
        $ppn->setNewsStand(true);
        $ppn->addAdditionalParameter('key3', 'value3');
        $ppn->setSound('newSound.mp3');
        $this->assertEquals("This is a new push message", $ppn->getMessage());
        $this->assertEquals(3, $ppn->getBadgeValue());
        $this->assertEquals(["key1" => "value1","key2" => "value2", 'key3' => 'value3'], $ppn->getParamsBag());
        $this->assertEquals('newSound.mp3', $ppn->getSound());
        $this->assertEquals('[{"newsstand":1,"message":"This is a new push message","sound":"newSound.mp3","badge":3,"paramsbag":{"key1":"value1","key2":"value2","key3":"value3"}}]', $ppn->jsonFormat());
        $this->assertTrue($ppn->isNewsStand());
        $ppn->setParamsBag(['param' => 'value']);
        $this->assertEquals(['param' => 'value'], $ppn->getParamsBag());
    }
}

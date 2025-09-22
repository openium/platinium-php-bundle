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

use Openium\PlatiniumBundle\Entity\PlatiniumPushInformation;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class PlatiniumPushInformationTest
 *
 * @package Openium\PlatiniumBundle\Tests\Entity
 */
class PlatiniumPushInformationTest extends TestCase
{
    public function testPlatiniumPushNotification(): void
    {
        $ppi = new PlatiniumPushInformation(["grp1", "grp2"], ["fr", "en"], true);
        $this->assertTrue($ppi instanceof PlatiniumPushInformation);
        $this->assertEquals(["grp1", "grp2"], $ppi->getGroups());
        $this->assertEquals(["fr", "en"], $ppi->getLangs());
        $this->assertFalse($ppi->isGeolocated());
        $this->assertTrue($ppi->isLangNotIn());
        $this->assertTrue($ppi->isValidGeolocation());
        $this->assertNull($ppi->getLongitude());
        $ppi->setGeolocation(1.15, 2.16, 50, 500);
        $this->assertTrue($ppi->isGeolocated());
        $this->assertTrue($ppi->isValidGeolocation());
        $this->assertEquals(1.15, $ppi->getLatitude());
        $this->assertEquals(2.16, $ppi->getLongitude());
        $this->assertEquals(50, $ppi->getTolerance());
        $this->assertEquals(500, $ppi->getRadius());
        $ppi->setGroups(['grp3', 'grp4']);
        $ppi->setLangs(['de', 'it']);
        $this->assertEquals(['grp3', 'grp4'], $ppi->getGroups());
        $this->assertEquals(['de', 'it'], $ppi->getLangs());
        $ppi->setLangNotIn(false);
        $this->assertFalse($ppi->isLangNotIn());
    }
}

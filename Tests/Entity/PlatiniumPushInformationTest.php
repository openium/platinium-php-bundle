<?php

namespace Openium\PlatiniumBundle\Tests\Entity;

use Openium\PlatiniumBundle\Entity\PlatiniumPushInformation;
use PHPUnit\Framework\TestCase;

/**
 * Class PlatiniumPushInformationTest
 *
 * @package Openium\PlatiniumBundle\Tests\Entity
 */
class PlatiniumPushInformationTest extends TestCase
{

    public function testSetGeolocationWithRightParameters()
    {
        $ppi = new PlatiniumPushInformation(["grp1", "grp2"], ["fr", "en"], true);
        $ppi->setGeolocation(1.15, 2.16, 50, 500);
        $this->assertTrue($ppi->isGeolocated());
        $this->assertEquals(1.15, $ppi->getLatitude());
        $this->assertEquals(2.16, $ppi->getLongitude());
        $this->assertEquals(50, $ppi->getTolerance());
        $this->assertEquals(500, $ppi->getRadius());
    }
    public function testSetGeolocationWithNullTolerance()
    {
        $ppi = new PlatiniumPushInformation(["grp1", "grp2"], ["fr", "en"], true);
        $ppi->setGeolocation(1.15, 2.16, null, 500);
        $this->assertTrue($ppi->isGeolocated());
        $this->assertEquals(1.15, $ppi->getLatitude());
        $this->assertEquals(2.16, $ppi->getLongitude());
        $this->assertEquals(null, $ppi->getTolerance());
        $this->assertEquals(500, $ppi->getRadius());
    }
    public function testSetGeolocationWithWrongRadius()
    {
        $ppi = new PlatiniumPushInformation(["grp1", "grp2"], ["fr", "en"], true);
        $ppi->setGeolocation(1.15, 2.16, 50, -400);
        $this->assertFalse($ppi->isGeolocated());
        $this->assertEquals(1.15, $ppi->getLatitude());
        $this->assertEquals(2.16, $ppi->getLongitude());
        $this->assertEquals(50, $ppi->getTolerance());
        $this->assertEquals(-400, $ppi->getRadius());
    }
    public function testSetGeolocationWithWrongLatLon()
    {
        $ppi = new PlatiniumPushInformation(["grp1", "grp2"], ["fr", "en"], true);
        $ppi->setGeolocation(0.0, 0.0, 50, 500);
        $this->assertFalse($ppi->isGeolocated());
        $this->assertEquals(0.0, $ppi->getLatitude());
        $this->assertEquals(0.0, $ppi->getLongitude());
        $this->assertEquals(50, $ppi->getTolerance());
        $this->assertEquals(500, $ppi->getRadius());
    }

    public function testSetGeolocationWithZeroLat()
    {
        $ppi = new PlatiniumPushInformation(["grp1", "grp2"], ["fr", "en"], true);
        $ppi->setGeolocation(0.0, 3.67, 50, 500);
        $this->assertTrue($ppi->isGeolocated());
        $this->assertEquals(0.0, $ppi->getLatitude());
        $this->assertEquals(3.67, $ppi->getLongitude());
        $this->assertEquals(50, $ppi->getTolerance());
        $this->assertEquals(500, $ppi->getRadius());
    }

    public function testPlatiniumPushNotification(): void
    {
        $ppi = new PlatiniumPushInformation(["grp1", "grp2"], ["fr", "en"], true);
        $this->assertTrue($ppi instanceof PlatiniumPushInformation);
        $this->assertEquals(["grp1", "grp2"], $ppi->getGroups());
        $this->assertEquals(["fr", "en"], $ppi->getLanguages());
        $this->assertFalse($ppi->isGeolocated());
        $this->assertTrue($ppi->isLangNotIn());
        $this->assertNull($ppi->getLongitude());
        $ppi->setGroups(['grp3', 'grp4']);
        $ppi->setLanguages(['de', 'it']);
        $this->assertEquals(['grp3', 'grp4'], $ppi->getGroups());
        $this->assertEquals(['de', 'it'], $ppi->getLanguages());
        $ppi->setLangNotIn(false);
        $this->assertFalse($ppi->isLangNotIn());
    }
}

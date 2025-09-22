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

use Openium\PlatiniumBundle\Entity\PlatiniumPushInformation;
use Openium\PlatiniumBundle\Entity\PlatiniumPushNotification;
use Openium\PlatiniumBundle\Exception\InvalidPushGeolocationConfigurationException;
use Openium\PlatiniumBundle\Service\PlatiniumParameterBagService;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class PlatiniumParameterBagServiceTest
 *
 * @package Openium\PlatiniumBundle\Tests\Service
 */
class PlatiniumParameterBagServiceTest extends TestCase
{
    public function testCreatePushParam(): void
    {
        $env = 'test';
        $tokenDev = 'MockDevToken';
        $tokenProd = 'MockProdToken';
        $ppi = new PlatiniumPushInformation(['grp1'], ['fr']);
        $ppn = new PlatiniumPushNotification('push message', [], 1, false, null);
        $ppbs = new PlatiniumParameterBagService($env, $tokenDev, $tokenProd);
        $result = $ppbs->createPushParam($ppi, $ppn);
        $this->assertEquals(
            [
                'api_notify[app]' => $tokenDev,
                'api_notify[params]' => $ppn->jsonFormat(),
                'api_notify[idsGroups]' => json_encode(['grp1']),
                'api_notify[langs]' => json_encode(['fr']),
            ],
            $result
        );
    }

    public function testCreatePushParamWithValidGeolocationAndLangNotIn(): void
    {
        $env = 'test';
        $tokenDev = 'MockDevToken';
        $tokenProd = 'MockProdToken';
        $ppi = new PlatiniumPushInformation(['grp1'], ['fr'], true);
        $ppi->setGeolocation(1.15, 2.16, 50, 500);

        $ppn = new PlatiniumPushNotification('push message', [], 1, false, null);
        $ppbs = new PlatiniumParameterBagService($env, $tokenDev, $tokenProd);
        $result = $ppbs->createPushParam($ppi, $ppn);
        $this->assertEquals(
            [
                'api_notify[app]' => $tokenDev,
                'api_notify[params]' => $ppn->jsonFormat(),
                'api_notify[langNotIn]' => true,
                'api_notify[idsGroups]' => json_encode(['grp1']),
                'api_notify[langs]' => json_encode(['fr']),
                'api_notify[latitude]' => 1.15,
                'api_notify[longitude]' => 2.16,
                'api_notify[radius]' => 500,
                'api_notify[tolerance]' => 50,
            ],
            $result
        );
    }

    public function testCreatePushParamWithInvalidGeolocation(): void
    {
        self::expectException(InvalidPushGeolocationConfigurationException::class);
        self::expectExceptionMessage('Invalid push geolocation configuration');
        $env = 'test';
        $tokenDev = 'MockDevToken';
        $tokenProd = 'MockProdToken';
        $ppi = $this->getMockBuilder(PlatiniumPushInformation::class)
            ->disableOriginalConstructor()
            ->getMock();
        $ppi->expects($this->once())
            ->method('isGeolocated')
            ->will($this->returnValue(true));
        $ppi->expects($this->once())
            ->method('isValidGeolocation')
            ->will($this->returnValue(false));
        $ppn = new PlatiniumPushNotification('push message', [], 1, false, null);
        $ppbs = new PlatiniumParameterBagService($env, $tokenDev, $tokenProd);
        $ppbs->createPushParam($ppi, $ppn);
    }
}

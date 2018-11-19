<?php

/**
 * PHP Version 7.1, 7.2
 *
 * @package  Openium\PlatiniumBundle\Service
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace Openium\PlatiniumBundle\Service;

use Openium\PlatiniumBundle\Exception\InvalidPushGeolocationConfigurationException;
use Openium\PlatiniumBundle\Entity\PlatiniumPushInformation;
use Openium\PlatiniumBundle\Entity\PlatiniumPushNotification;

/**
 * Class PlatiniumParameterBagService
 *
 * @package Openium\PlatiniumBundle\Service
 */
class PlatiniumParameterBagService
{
    /**
     * @var string
     */
    protected $env;

    /**
     * @var string
     */
    protected $tokenDev;

    /**
     * @var string
     */
    protected $tokenProd;

    /**
     * PlatiniumParameterBagService constructor.
     *
     * @param string $env
     * @param string $tokenDev
     * @param string $tokenProd
     */
    public function __construct(string $env, string $tokenDev, string $tokenProd)
    {
        $this->env = $env;
        $this->tokenDev = $tokenDev;
        $this->tokenProd = $tokenProd;
    }

    /**
     * createPushParam
     *
     * @param PlatiniumPushInformation $pushInformation
     * @param PlatiniumPushNotification $notification
     *
     * @throws InvalidPushGeolocationConfigurationException
     *
     * @return array
     */
    public function createPushParam(
        PlatiniumPushInformation $pushInformation,
        PlatiniumPushNotification $notification
    ): array {
        $token = ($this->env === 'prod') ? $this->tokenProd : $this->tokenDev;
        $paramsBag = [
            'api_notify[app]' => $token,
            'api_notify[params]' => $notification->jsonFormat(),
        ];
        if ($pushInformation->isLangNotIn()) {
            $paramsBag['api_notify[langNotIn]'] = $pushInformation->isLangNotIn();
        }
        if (count($pushInformation->getGroups()) > 0) {
            $paramsBag['api_notify[idsGroups]'] = json_encode($pushInformation->getGroups());
        }
        if (count($pushInformation->getLangs()) > 0) {
            $paramsBag['api_notify[langs]'] = json_encode($pushInformation->getLangs());
        }
        if ($pushInformation->isGeolocated()) {
            if ($pushInformation->isValidGeolocation()) {
                $paramsBag['api_notify[latitude]'] = $pushInformation->getLatitude();
                $paramsBag['api_notify[longitude]'] = $pushInformation->getLongitude();
                $paramsBag['api_notify[radius]'] = $pushInformation->getRadius();
                $paramsBag['api_notify[tolerance]'] = $pushInformation->getTolerance();
            } else {
                throw new InvalidPushGeolocationConfigurationException();
            }
        }
        return $paramsBag;
    }
}

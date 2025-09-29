<?php

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
    public function __construct(
        private readonly string $env,
        private readonly string $tokenDev,
        private readonly string $tokenProd
    ) {
    }

    /**
     * createPushParam
     *
     * @throws InvalidPushGeolocationConfigurationException
     * @return array<string, mixed>
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
        if ($pushInformation->getGroups() !== []) {
            $paramsBag['api_notify[idsGroups]'] = json_encode($pushInformation->getGroups());
        }
        if ($pushInformation->getLangs() !== []) {
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

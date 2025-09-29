<?php

namespace Openium\PlatiniumBundle;

use Openium\PlatiniumBundle\Exception\InvalidPushGeolocationConfigurationException;
use Openium\PlatiniumBundle\Exception\PushException;
use Openium\PlatiniumBundle\Entity\PlatiniumPushInformation;
use Openium\PlatiniumBundle\Entity\PlatiniumPushNotification;
use Openium\PlatiniumBundle\Entity\PlatiniumPushResponse;
use Openium\PlatiniumBundle\Service\PlatiniumParameterBagService;

/**
 * Class PlatiniumNotifier
 *
 * @package Openium\PlatiniumBundle
 */
class PlatiniumNotifier
{
    /**
     * PlatiniumNotifier constructor.
     */
    public function __construct(
        private readonly PlatiniumClient $client,
        private readonly PlatiniumParameterBagService $platiniumParameterBagService,
        private readonly string $notifyPath,
        private readonly string $subscribedPath
    ) {
    }

    /**
     * notify
     * send push
     * $message is required
     * if you want geolocated push you have to defined all of $latitude, $longitude, $tolerance, $radius
     *
     * @param string $message the message to push
     * @param string[] $groups notification groups
     * @param string[] $langs notification langs
     * @param float|null $latitude for geolocated push
     * @param float|null $longitude for geolocated push
     * @param int|null $tolerance for geolocated push
     * @param int|null $radius for geolocated push
     * @param string|null $sound
     *
     * @throws InvalidPushGeolocationConfigurationException if geolocation parameters are incorrect
     * @throws PushException if push is not sent
     * @return bool true => push is sent to platinium
     */
    public function notify(
        string $message,
        array $groups = [],
        array $langs = [],
        bool $langNotIn = false,
        float $latitude = null,
        float $longitude = null,
        int $tolerance = null,
        int $radius = null,
        array $paramsBag = [],
        int $badgeValue = 0,
        bool $newsStand = false,
        string $sound = null
    ): bool {
        $notificationInformation = new PlatiniumPushInformation($groups, $langs, $langNotIn);
        if ($latitude && $longitude && $radius && $tolerance) {
            $notificationInformation->setGeolocation($latitude, $longitude, $tolerance, $radius);
        }

        $notification = new PlatiniumPushNotification(
            $message,
            $paramsBag,
            $badgeValue,
            $newsStand,
            $sound
        );
        $parameterBag = $this->platiniumParameterBagService->createPushParam(
            $notificationInformation,
            $notification
        );
        $response = $this->client->send($this->notifyPath, $parameterBag);
        $this->verifyResponse($response);
        // TODO use result for ?
        return true;
    }

    /**
     * subscribed
     * get number of subscriber
     *
     * @param string[] $groups notification groups
     * @param string[] $langs notification langs
     * @param float|null $latitude for geolocated push
     * @param float|null $longitude for geolocated push
     * @param int|null $tolerance for geolocated push
     * @param int|null $radius for geolocated push
     *
     * @throws InvalidPushGeolocationConfigurationException
     */
    public function subscribed(
        array $groups = [],
        array $langs = [],
        bool $langNotIn = false,
        float $latitude = null,
        float $longitude = null,
        int $tolerance = null,
        int $radius = null
    ): int {
        $notificationInformation = new PlatiniumPushInformation($groups, $langs, $langNotIn);
        if ($latitude && $longitude && $radius && $tolerance) {
            $notificationInformation->setGeolocation($latitude, $longitude, $tolerance, $radius);
        }

        $notification = new PlatiniumPushNotification();
        $parameterBag = $this->platiniumParameterBagService->createPushParam(
            $notificationInformation,
            $notification
        );
        $response = $this->client->send($this->subscribedPath, $parameterBag);
        $content = json_decode($response->getResult(), true);
        return array_key_exists('result', $content) ? $content['result'] : 0;
    }

    /**
     * verifyResponse
     *
     * @throws PushException
     */
    public function verifyResponse(PlatiniumPushResponse $response): bool
    {
        if ($response->getStatus() !== PlatiniumPushResponse::STATUS_SUCCESS) {
            $errorMessage = sprintf(
                'Status: %s\nResult: %s',
                $response->getStatus(),
                $response->getResult()
            );
            throw new PushException($errorMessage);
        }

        $data = json_decode($response->getResult(), true);
        if (is_null($data) || empty($data)) {
            throw new PushException('Push Send Failed : JSON Parse Failed.');
        }

        $responseKeys = [
            'id',
            'is_dev',
            'ids_groups',
            'langs',
            'notification_per_minute',
            'creation_date',
            'params',
            'state',
            'origin',
        ];
        foreach ($responseKeys as $key) {
            if (!array_key_exists($key, $data)) {
                $errorMessage = 'Push Send Failed : invalid result. Missing ' . $key;
                throw new PushException($errorMessage);
            }
        }

        return true;
    }
}

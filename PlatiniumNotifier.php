<?php

/**
 * PHP Version 7.1, 7.2
 *
 * @package  Openium\PlatiniumBundle
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace Openium\PlatiniumBundle;

use Openium\Platinium\Exception\InvalidPushGeolocationConfigurationException;
use Openium\Platinium\Exception\PushException;
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
     * @var PlatiniumClient
     */
    protected $client;

    /**
     * @var PlatiniumParameterBagService
     */
    protected $platiniumParameterBagService;

    /**
     * @var string
     */
    private $notifyPath;

    /**
     * PlatiniumNotifier constructor.
     *
     * @param PlatiniumClient $platiniumClient
     * @param PlatiniumParameterBagService $platiniumParameterBagService
     * @param string $notifyPath
     */
    public function __construct(
        PlatiniumClient $platiniumClient,
        PlatiniumParameterBagService $platiniumParameterBagService,
        string $notifyPath
    ) {
        $this->client = $platiniumClient;
        $this->platiniumParameterBagService = $platiniumParameterBagService;
        $this->notifyPath = $notifyPath;
    }

    /**
     * notify
     *
     * @param string $message
     * @param array $groups
     * @param array $langs
     * @param float|null $latitude
     * @param float|null $longitude
     * @param int|null $tolerance
     * @param int|null $radius
     *
     * @throws InvalidPushGeolocationConfigurationException
     * @throws PushException
     *
     * @return bool
     */
    public function notify(
        string $message,
        array $groups = [],
        array $langs = [],
        float $latitude = null,
        float $longitude = null,
        int $tolerance = null,
        int $radius = null
    ): bool {
        $notificationInformation = new PlatiniumPushInformation($groups, $langs);
        if ($latitude && $longitude && $radius && $tolerance) {
            $notificationInformation->setGeolocation($latitude, $longitude, $tolerance, $radius);
        }
        $notification = new PlatiniumPushNotification($message);
        $parameterBag = $this->platiniumParameterBagService->createPushParam($notificationInformation, $notification);
        $response = $this->client->send($this->notifyPath, $parameterBag);
        $this->verifyResponse($response);
        // TODO use result for ?
        return true;
    }

    /**
     * verifyResponse
     *
     * @param PlatiniumPushResponse $response
     *
     * @throws PushException
     *
     * @return void
     */
    private function verifyResponse(PlatiniumPushResponse $response)
    {
        if ($response->getStatus() !== PlatiniumPushResponse::STATUS_SUCCESS) {
            throw new PushException();
        }
        $data = json_decode($response->getResult());
        if (is_null($data) || empty($data)) {
            throw new PushException('Push JSON Parse Failed.');
        }
        /*
         * TODO may by check each property
         * id,is_dev,ids_groups,langs,notification_per_minute,
         * creation_date,params,tolerance,state,origin,token_notifications
         */
        if (!array_key_exists('id', $data)) {
            $errorMessage = __METHOD__ . ' : Push Send Failed. Result : ' . $response->getResult();
            throw new PushException($errorMessage);
        }
    }
}

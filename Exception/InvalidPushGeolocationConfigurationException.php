<?php

namespace Openium\PlatiniumBundle\Exception;

/**
 * Class InvalidPushGeolocationConfigurationException
 *
 * @package Openium\PlatiniumBundle\Exception
 */
class InvalidPushGeolocationConfigurationException extends \Exception
{
    /** @var string */
    public const DEFAULT_MESSAGE = 'Invalid push geolocation configuration';
    /** @var int */
    public const DEFAULT_CODE = 1612012002;

    public function __construct(
        string $message = self::DEFAULT_MESSAGE,
        int $code = self::DEFAULT_CODE,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}

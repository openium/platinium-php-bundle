<?php

namespace Openium\PlatiniumBundle\Exception;

/**
 * Class PushException
 *
 * @package Openium\PlatiniumBundle\Exception
 */
class PushException extends \Exception
{
    /** @var string */
    public const DEFAULT_MESSAGE = 'Push not sent';

    /** @var int */
    public const DEFAULT_CODE = 1612012001;

    public function __construct(
        string $message = self::DEFAULT_MESSAGE,
        int $code = self::DEFAULT_CODE,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}

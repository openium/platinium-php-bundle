<?php

/**
 * PHP Version 7.1, 7.2
 *
 * @package  Openium\PlatiniumBundle\Exception
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace Openium\PlatiniumBundle\Exception;

/**
 * Class PushException
 *
 * @package Openium\PlatiniumBundle\Exception
 */
class PushException extends \Exception
{
    /**
     * @var string
     */
    public const DEFAULT_MESSAGE = 'Push not sent';

    /**
     * @var int
     */
    public const DEFAULT_CODE = 1612012001;

    /**
     * LunaException constructor.
     *
     * @param string|null $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = self::DEFAULT_MESSAGE,
        int $code = self::DEFAULT_CODE,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}

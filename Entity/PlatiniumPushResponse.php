<?php

/**
 * PHP Version 7.1, 7.2
 *
 * @package  Openium\PlatiniumBundle\Entity
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace Openium\PlatiniumBundle\Entity;

/**
 * Class PlatiniumPushResponse
 *
 * @package Openium\PlatiniumBundle\Entity
 */
class PlatiniumPushResponse
{
    public const STATUS_SUCCESS = 0;

    /**
     * The status returned by the query
     * @var int $status
     */
    protected $status;

    /**
     * Raw result of the query
     * @var string $result
     */
    protected $result;

    /**
     * PlatiniumPushResponse constructor.
     *
     * @param int $status
     * @param string $result
     */
    public function __construct(int $status, string $result)
    {
        $this->status = $status;
        $this->result = $result;
    }

    /**
     * Getter for status
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Setter for status
     *
     * @param int $status
     *
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Getter for result
     *
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }

    /**
     * Setter for result
     *
     * @param string $result
     *
     * @return self
     */
    public function setResult(string $result): self
    {
        $this->result = $result;
        return $this;
    }
}

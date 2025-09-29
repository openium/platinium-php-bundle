<?php

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
     */
    protected int $status;

    /**
     * Raw result of the query
     */
    protected string $result;

    public function __construct(int $status, string $result)
    {
        $this->status = $status;
        $this->result = $result;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function setResult(string $result): self
    {
        $this->result = $result;
        return $this;
    }
}

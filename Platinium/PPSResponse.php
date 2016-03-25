<?php

namespace Openium\PlatiniumBundle\Platinium;

class PPSResponse
{
    const STATUS_SUCCESS = 0;

    /** @var int $status The status returned by the query */
    private $status;
    /** @var string $result Raw result of the query */
    private $result;

    function __construct($status, $result)
    {
        $this->status = $status;
        $this->result = $result;
    }
}

<?php

namespace Openium\PlatiniumBundle;

use Openium\PlatiniumBundle\DependencyInjection\PlatiniumExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class PlatiniumBundle
 *
 * @package Openium\PlatiniumBundle
 */
class PlatiniumBundle extends Bundle
{
    /**
     * getContainerExtension
     *
     * @return PlatiniumExtension
     */
    public function getContainerExtension()
    {
        return new PlatiniumExtension();
    }
}

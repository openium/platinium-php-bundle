<?php

namespace Openium\PlatiniumBundle;

use Openium\PlatiniumBundle\DependencyInjection\PlatiniumExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class PlatiniumBundle
 *
 * @package Openium\PlatiniumBundle
 */
class PlatiniumBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new PlatiniumExtension();
    }
}

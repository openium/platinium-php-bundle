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
     */
    public function getContainerExtension(): PlatiniumExtension
    {
        return new PlatiniumExtension();
    }
}

<?php

namespace Openium\PlatiniumBundle\Tests;

use Openium\PlatiniumBundle\DependencyInjection\PlatiniumExtension;
use Openium\PlatiniumBundle\PlatiniumBundle;
use PHPUnit\Framework\TestCase;

/**
 * Class PlatiniumBundleTest
 *
 * @package Openium\PlatiniumBundle\Tests
 */
class PlatiniumBundleTest extends TestCase
{
    public function testGetContainerExtensionReturnsPlatiniumExtension(): void
    {
        $bundle = new PlatiniumBundle();

        $this->assertInstanceOf(PlatiniumExtension::class, $bundle->getContainerExtension());
    }
}

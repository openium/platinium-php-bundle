<?php

namespace Openium\PlatiniumBundle\Tests\DependencyInjection;

use Openium\PlatiniumBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class ConfigurationTest
 *
 * @package Openium\PlatiniumBundle\Tests\DependencyInjection
 */
class ConfigurationTest extends TestCase
{
    public function testConfiguration(): void
    {
        $configuration = new Configuration();
        $tree = $configuration->getConfigTreeBuilder();
        $this->assertTrue($tree instanceof TreeBuilder);
    }

    /**
     * Since Symfony's Config component, ConfigurationInterface::getConfigTreeBuilder()
     * declares a native `TreeBuilder` return type. A missing return type here is a
     * fatal "Declaration must be compatible" error as soon as the installed
     * symfony/config version enforces it (already true when resolved to 7.x).
     */
    public function testGetConfigTreeBuilderDeclaresNativeReturnType(): void
    {
        $method = new \ReflectionMethod(Configuration::class, 'getConfigTreeBuilder');
        $returnType = $method->getReturnType();

        $this->assertNotNull($returnType, 'getConfigTreeBuilder() must declare a native return type');
        $this->assertSame(TreeBuilder::class, (string) $returnType);
    }
}

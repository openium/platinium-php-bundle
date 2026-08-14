<?php

namespace Openium\PlatiniumBundle\Tests\DependencyInjection;

use Openium\PlatiniumBundle\DependencyInjection\PlatiniumExtension;
use Openium\PlatiniumBundle\PlatiniumClient;
use Openium\PlatiniumBundle\PlatiniumNotifier;
use Openium\PlatiniumBundle\Service\PlatiniumParameterBagService;
use Openium\PlatiniumBundle\Service\PlatiniumSignatureService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class PlatiniumExtensionTest
 *
 * Exercises the bundle's actual service definition loading and container
 * compilation, which is the part of the bundle most exposed to breaking
 * changes in the DependencyInjection/Config components across major
 * Symfony versions.
 *
 * @package Openium\PlatiniumBundle\Tests\DependencyInjection
 */
class PlatiniumExtensionTest extends TestCase
{
    protected function setUp(): void
    {
        putenv('PLATINIUM_SERVER_ID=test-server-id');
        putenv('PLATINIUM_SERVER_KEY=test-server-key');
        putenv('PLATINIUM_SERVER_TOKEN_DEV=test-token-dev');
        putenv('PLATINIUM_SERVER_TOKEN_PROD=test-token-prod');
    }

    protected function tearDown(): void
    {
        putenv('PLATINIUM_SERVER_ID');
        putenv('PLATINIUM_SERVER_KEY');
        putenv('PLATINIUM_SERVER_TOKEN_DEV');
        putenv('PLATINIUM_SERVER_TOKEN_PROD');
    }

    private function buildContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');
        $container->register(HttpClientInterface::class, MockHttpClient::class);

        $extension = new PlatiniumExtension();
        $extension->load([], $container);

        return $container;
    }

    public function testExtensionRegistersExpectedServiceDefinitions(): void
    {
        $container = $this->buildContainer();

        $expectedDefinitions = [
            'openium_platinium.signature_service' => PlatiniumSignatureService::class,
            'openium_platinium.parameter_bag_service' => PlatiniumParameterBagService::class,
            'openium_platinium.client' => PlatiniumClient::class,
            'openium_platinium.notifier' => PlatiniumNotifier::class,
        ];

        foreach ($expectedDefinitions as $serviceId => $expectedClass) {
            $this->assertTrue($container->hasDefinition($serviceId), "Missing service: $serviceId");
            $this->assertSame($expectedClass, $container->getDefinition($serviceId)->getClass());
        }
    }

    public function testExtensionRegistersNotifierAlias(): void
    {
        $container = $this->buildContainer();

        $this->assertTrue($container->hasAlias(PlatiniumNotifier::class));
        $this->assertSame(
            'openium_platinium.notifier',
            (string) $container->getAlias(PlatiniumNotifier::class)
        );
    }

    public function testContainerCompilesAndNotifierServiceIsUsable(): void
    {
        $container = $this->buildContainer();
        $container->compile();

        $notifier = $container->get('openium_platinium.notifier');

        $this->assertInstanceOf(PlatiniumNotifier::class, $notifier);
    }
}

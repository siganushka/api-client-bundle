<?php

declare(strict_types=1);

namespace Siganushka\ApiClientBundle\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\RequestClientInterface;
use Siganushka\ApiClient\RequestRegistryInterface;
use Siganushka\ApiClientBundle\DependencyInjection\SiganushkaApiClientExtension;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SiganushkaApiClientExtensionTest extends TestCase
{
    public function testAll(): void
    {
        $container = $this->createContainerWithConfigs([
            [
                'wechat' => [
                    'appid' => 'foo',
                    'appsecret' => 'bar',
                ],
            ],
        ]);

        static::assertTrue($container->hasDefinition('siganushka.api_client.request_registry'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.request_client'));
        static::assertTrue($container->hasAlias(RequestRegistryInterface::class));
        static::assertTrue($container->hasAlias(RequestClientInterface::class));

        $requestRegistryDef = $container->getDefinition('siganushka.api_client.request_registry');
        static::assertInstanceOf(TaggedIteratorArgument::class, $requestRegistryDef->getArgument(0));

        $requestClientDef = $container->getDefinition('siganushka.api_client.request_client');
        static::assertSame('http_client', (string) $requestClientDef->getArgument(0));
        static::assertSame('siganushka.api_client.request_registry', (string) $requestClientDef->getArgument(1));
    }

    /**
     * @param array<mixed> $configs
     */
    protected function createContainerWithConfigs(array $configs): ContainerBuilder
    {
        $container = new ContainerBuilder();

        $extension = new SiganushkaApiClientExtension();
        $extension->load($configs, $container);

        return $container;
    }
}

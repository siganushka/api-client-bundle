<?php

declare(strict_types=1);

namespace Siganushka\ApiClientBundle\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\RequestInterface;
use Siganushka\ApiClientBundle\SiganushkaApiClientBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SiganushkaApiClientBundleTest extends TestCase
{
    public function testAll(): void
    {
        $container = new ContainerBuilder();

        $bundle = new SiganushkaApiClientBundle();
        $bundle->build($container);

        $instanceof = $container->getAutoconfiguredInstanceof();
        static::assertArrayHasKey(RequestInterface::class, $instanceof);
        static::assertTrue($instanceof[RequestInterface::class]->hasTag('siganushka.api_client.request'));
    }
}

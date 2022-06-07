<?php

declare(strict_types=1);

namespace Siganushka\ApiClientBundle;

use Siganushka\ApiClient\RequestExtensionInterface;
use Siganushka\ApiClient\RequestInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SiganushkaApiClientBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(RequestInterface::class)
            ->addTag('siganushka.api_client.request')
        ;

        $container->registerForAutoconfiguration(RequestExtensionInterface::class)
            ->addTag('siganushka.api_client.request_extension')
        ;
    }
}

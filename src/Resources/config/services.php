<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Siganushka\ApiClient\RequestClient;
use Siganushka\ApiClient\RequestClientInterface;
use Siganushka\ApiClient\RequestRegistry;
use Siganushka\ApiClient\RequestRegistryInterface;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('siganushka.api_client.request_client', RequestClient::class)
            ->arg(0, service('http_client'))
            ->arg(1, service('siganushka.api_client.request_registry'))
            ->arg(2, tagged_iterator('siganushka.api_client.request_extension'))
            ->alias(RequestClientInterface::class, 'siganushka.api_client.request_client')

        ->set('siganushka.api_client.request_registry', RequestRegistry::class)
            ->arg(0, tagged_iterator('siganushka.api_client.request'))
            ->alias(RequestRegistryInterface::class, 'siganushka.api_client.request_registry')
    ;
};

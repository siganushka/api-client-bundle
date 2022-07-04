<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Siganushka\ApiClient\Github\AccessToken;
use Siganushka\ApiClient\Github\Client;
use Siganushka\ApiClient\Github\Configuration;
use Siganushka\ApiClient\Github\User;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('siganushka.api_client.github.configuration', Configuration::class)
            ->alias(Configuration::class, 'siganushka.api_client.github.configuration')

        ->set('siganushka.api_client.github.client', Client::class)
            ->arg(0, service('siganushka.api_client.github.configuration'))
            ->alias(Client::class, 'siganushka.api_client.github.client')

        ->set('siganushka.api_client.github.access_token', AccessToken::class)
            ->arg(0, service('cache.app'))
            ->arg(1, service('siganushka.api_client.github.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.github.user', User::class)
            ->tag('siganushka.api_client.request')
    ;
};

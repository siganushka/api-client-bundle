<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Siganushka\ApiClient\Github\AccessToken;
use Siganushka\ApiClient\Github\Authorize;
use Siganushka\ApiClient\Github\Configuration;
use Siganushka\ApiClient\Github\User;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('siganushka.api_client.github.configuration', Configuration::class)
            ->alias(Configuration::class, 'siganushka.api_client.github.configuration')

        ->set('siganushka.api_client.github.authorize', Authorize::class)
            ->arg(0, service('siganushka.api_client.github.configuration'))
            ->alias(Authorize::class, 'siganushka.api_client.github.authorize')

        ->set('siganushka.api_client.github.access_token', AccessToken::class)
            ->arg(0, service('siganushka.api_client.github.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.github.user', User::class)
            ->tag('siganushka.api_client.request')
    ;
};

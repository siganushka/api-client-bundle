<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Siganushka\ApiClientBundle\Wechat\Configuration;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('siganushka.api_client.wechat.configuration', Configuration::class)
            ->alias(Configuration::class, 'siganushka.api_client.wechat.configuration')
    ;
};

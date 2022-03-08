<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Siganushka\ApiClient\Wechat\Configuration;
use Siganushka\ApiClient\Wechat\Core\AccessToken;
use Siganushka\ApiClient\Wechat\Core\ServerIp;
use Siganushka\ApiClient\Wechat\Miniapp\SessionKey;
use Siganushka\ApiClient\Wechat\Payment\ParameterUtils;
use Siganushka\ApiClient\Wechat\Payment\SignatureUtils;
use Siganushka\ApiClient\Wechat\Payment\Transfer;
use Siganushka\ApiClient\Wechat\Payment\Unifiedorder;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('siganushka.api_client.wechat.configuration', Configuration::class)
            ->alias(Configuration::class, 'siganushka.api_client.wechat.configuration')

        ->set('siganushka.api_client.wechat.core.access_token', AccessToken::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.core.server_ip', ServerIp::class)
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.miniapp.session_key', SessionKey::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.payment.parameter_utils', ParameterUtils::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->alias(ParameterUtils::class, 'siganushka.api_client.wechat.payment.parameter_utils')

        ->set('siganushka.api_client.wechat.payment.signature_utils', SignatureUtils::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->alias(SignatureUtils::class, 'siganushka.api_client.wechat.payment.signature_utils')

        ->set('siganushka.api_client.wechat.payment.unifiedorder', Unifiedorder::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->arg(1, service('serializer.encoder.xml'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.payment.transfer', Transfer::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->arg(1, service('serializer.encoder.xml'))
            ->tag('siganushka.api_client.request')
    ;
};

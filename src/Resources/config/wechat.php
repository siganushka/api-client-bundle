<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Siganushka\ApiClient\Wechat\Configuration;
use Siganushka\ApiClient\Wechat\Core\Request\AccessTokenRequest;
use Siganushka\ApiClient\Wechat\Core\Request\ServerIpRequest;
use Siganushka\ApiClient\Wechat\Miniapp\Request\SessionKeyRequest;
use Siganushka\ApiClient\Wechat\Payment\ParameterManager;
use Siganushka\ApiClient\Wechat\Payment\Request\TransferRequest;
use Siganushka\ApiClient\Wechat\Payment\Request\UnifiedorderRequest;
use Siganushka\ApiClient\Wechat\Payment\SignatureManager;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('siganushka.api_client.wechat.configuration', Configuration::class)
            ->alias(Configuration::class, 'siganushka.api_client.wechat.configuration')

        ->set('siganushka.api_client.wechat.parameter_manager', ParameterManager::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->alias(ParameterManager::class, 'siganushka.api_client.wechat.parameter_manager')

        ->set('siganushka.api_client.wechat.signature_manager', SignatureManager::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->alias(SignatureManager::class, 'siganushka.api_client.wechat.signature_manager')

        ->set('siganushka.api_client.wechat.core.access_token_request', AccessTokenRequest::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.core.server_ip_request', ServerIpRequest::class)
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.miniapp.session_key_request', SessionKeyRequest::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.payment.unifiedorder_request', UnifiedorderRequest::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->arg(1, service('serializer.encoder.xml'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.payment.transfer_request', TransferRequest::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->arg(1, service('serializer.encoder.xml'))
            ->tag('siganushka.api_client.request')
    ;
};

<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Siganushka\ApiClient\Wechat\Configuration;
use Siganushka\ApiClient\Wechat\Core\AccessToken;
use Siganushka\ApiClient\Wechat\Core\CallbackIp;
use Siganushka\ApiClient\Wechat\Core\ServerIp;
use Siganushka\ApiClient\Wechat\Extension\AccessTokenExtension;
use Siganushka\ApiClient\Wechat\Jsapi\ConfigUtils as JsapiConfigUtils;
use Siganushka\ApiClient\Wechat\Miniapp\Qrcode;
use Siganushka\ApiClient\Wechat\Miniapp\SessionKey;
use Siganushka\ApiClient\Wechat\Miniapp\Wxacode;
use Siganushka\ApiClient\Wechat\Miniapp\WxacodeUnlimited;
use Siganushka\ApiClient\Wechat\OAuth\AccessToken as OAuthAccessToken;
use Siganushka\ApiClient\Wechat\OAuth\Authorize;
use Siganushka\ApiClient\Wechat\OAuth\CheckToken;
use Siganushka\ApiClient\Wechat\OAuth\RefreshToken;
use Siganushka\ApiClient\Wechat\OAuth\UserInfo;
use Siganushka\ApiClient\Wechat\Payment\ConfigUtils as PaymentConfigUtils;
use Siganushka\ApiClient\Wechat\Payment\Query;
use Siganushka\ApiClient\Wechat\Payment\Refund;
use Siganushka\ApiClient\Wechat\Payment\SignatureUtils;
use Siganushka\ApiClient\Wechat\Payment\Transfer;
use Siganushka\ApiClient\Wechat\Payment\Unifiedorder;
use Siganushka\ApiClient\Wechat\Template\Message;
use Siganushka\ApiClient\Wechat\Ticket\Ticket;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('siganushka.api_client.wechat.configuration', Configuration::class)
            ->alias(Configuration::class, 'siganushka.api_client.wechat.configuration')

        ->set('siganushka.api_client.wechat.core.access_token', AccessToken::class)
            ->arg(0, service('cache.app'))
            ->arg(1, service('siganushka.api_client.wechat.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.core.server_ip', ServerIp::class)
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.core.callback_ip', CallbackIp::class)
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.extension.access_token', AccessTokenExtension::class)
            ->arg(0, service('siganushka.api_client.request_registry'))
            ->tag('siganushka.api_client.request_extension')

        ->set('siganushka.api_client.wechat.jsapi.config_utils', JsapiConfigUtils::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->alias(JsapiConfigUtils::class, 'siganushka.api_client.wechat.jsapi.config_utils')

        ->set('siganushka.api_client.wechat.template.message', Message::class)
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.miniapp.session_key', SessionKey::class)
            ->arg(0, service('cache.app'))
            ->arg(1, service('siganushka.api_client.wechat.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.miniapp.wxacode', Wxacode::class)
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.miniapp.wxacode_unlimited', WxacodeUnlimited::class)
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.miniapp.qrcode', Qrcode::class)
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.oauth.authorize', Authorize::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->alias(Authorize::class, 'siganushka.api_client.wechat.oauth.authorize')

        ->set('siganushka.api_client.wechat.oauth.access_token', OAuthAccessToken::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.oauth.check_token', CheckToken::class)
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.oauth.refresh_token', RefreshToken::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.oauth.user_info', UserInfo::class)
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.payment.config_utils', PaymentConfigUtils::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->alias(PaymentConfigUtils::class, 'siganushka.api_client.wechat.payment.config_utils')

        ->set('siganushka.api_client.wechat.payment.signature_utils', SignatureUtils::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->alias(SignatureUtils::class, 'siganushka.api_client.wechat.payment.signature_utils')

        ->set('siganushka.api_client.wechat.payment.query', Query::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.payment.refund', Refund::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.payment.transfer', Transfer::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.payment.unifiedorder', Unifiedorder::class)
            ->arg(0, service('siganushka.api_client.wechat.configuration'))
            ->tag('siganushka.api_client.request')

        ->set('siganushka.api_client.wechat.ticket.ticket', Ticket::class)
            ->arg(0, service('cache.app'))
            ->tag('siganushka.api_client.request')
    ;
};

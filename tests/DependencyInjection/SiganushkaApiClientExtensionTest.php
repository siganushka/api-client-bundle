<?php

declare(strict_types=1);

namespace Siganushka\ApiClientBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\Github\Client as GithubClient;
use Siganushka\ApiClient\Github\Configuration as GithubConfiguration;
use Siganushka\ApiClient\RequestClientInterface;
use Siganushka\ApiClient\RequestRegistryInterface;
use Siganushka\ApiClient\Wechat\Configuration as WechatConfiguration;
use Siganushka\ApiClient\Wechat\Jsapi\ConfigUtils as JsapiConfigUtils;
use Siganushka\ApiClient\Wechat\OAuth\Client as WechatClient;
use Siganushka\ApiClient\Wechat\Payment\ConfigUtils as PaymentConfigUtils;
use Siganushka\ApiClient\Wechat\Payment\SignatureUtils;
use Siganushka\ApiClientBundle\DependencyInjection\SiganushkaApiClientExtension;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SiganushkaApiClientExtensionTest extends TestCase
{
    public function testAll(): void
    {
        static::assertTrue(interface_exists(RequestClientInterface::class));
        static::assertTrue(interface_exists(RequestRegistryInterface::class));

        $container = $this->createContainerWithConfigs([]);
        static::assertTrue($container->hasDefinition('siganushka.api_client.request_client'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.request_registry'));
        static::assertTrue($container->hasAlias(RequestClientInterface::class));
        static::assertTrue($container->hasAlias(RequestRegistryInterface::class));

        $apiClientDef = $container->getDefinition('siganushka.api_client.request_client');
        static::assertSame('siganushka.api_client.request_registry', (string) $apiClientDef->getArgument(0));
        static::assertInstanceOf(TaggedIteratorArgument::class, $apiClientDef->getArgument(1));

        $requestRegistryDef = $container->getDefinition('siganushka.api_client.request_registry');
        static::assertSame('http_client', (string) $requestRegistryDef->getArgument(0));
        static::assertInstanceOf(TaggedIteratorArgument::class, $requestRegistryDef->getArgument(1));
    }

    public function testWechatConfigs(): void
    {
        $config = [
            'appid' => 'test_appid',
            'secret' => 'test_secret',
            'open_appid' => 'test_open_appid',
            'open_secret' => 'test_open_secret',
            'mchid' => 'test_mchid',
            'mchkey' => 'test_mchkey',
            'client_cert_file' => 'test_client_cert_file',
            'client_key_file' => 'test_client_key_file',
            'sign_type' => 'test_sign_type',
        ];

        $container = $this->createContainerWithConfigs(['wechat' => $config]);

        static::assertTrue($container->hasDefinition('siganushka.api_client.request_client'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.request_registry'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.configuration'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.core.access_token'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.core.server_ip'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.core.callback_ip'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.extension.access_token'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.jsapi.config_utils'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.template.message'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.miniapp.session_key'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.miniapp.wxacode'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.miniapp.wxacode_unlimited'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.miniapp.qrcode'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.oauth.client'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.oauth.access_token'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.oauth.check_token'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.oauth.refresh_token'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.oauth.user_info'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.payment.config_utils'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.payment.signature_utils'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.payment.query'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.payment.refund'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.payment.transfer'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.payment.unifiedorder'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.ticket.ticket'));

        static::assertTrue($container->hasAlias(RequestClientInterface::class));
        static::assertTrue($container->hasAlias(RequestRegistryInterface::class));
        static::assertTrue($container->hasAlias(WechatConfiguration::class));
        static::assertTrue($container->hasAlias(JsapiConfigUtils::class));
        static::assertTrue($container->hasAlias(WechatClient::class));
        static::assertTrue($container->hasAlias(PaymentConfigUtils::class));
        static::assertTrue($container->hasAlias(SignatureUtils::class));

        $configurationDef = $container->getDefinition('siganushka.api_client.wechat.configuration');
        static::assertSame($config, $configurationDef->getArgument(0));
    }

    public function testGithubConfigs(): void
    {
        $config = [
            'client_id' => 'test_client_id',
            'client_secret' => 'test_client_secret',
        ];

        $container = $this->createContainerWithConfigs(['github' => $config]);
        static::assertTrue($container->hasDefinition('siganushka.api_client.request_client'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.request_registry'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.github.configuration'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.github.client'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.github.access_token'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.github.user'));

        static::assertTrue($container->hasAlias(RequestClientInterface::class));
        static::assertTrue($container->hasAlias(RequestRegistryInterface::class));
        static::assertTrue($container->hasAlias(GithubConfiguration::class));
        static::assertTrue($container->hasAlias(GithubClient::class));

        $configurationDef = $container->getDefinition('siganushka.api_client.github.configuration');
        static::assertSame($config, $configurationDef->getArgument(0));
    }

    /**
     * @param array<mixed> $configs
     */
    protected function createContainerWithConfigs(array $configs): ContainerBuilder
    {
        $container = new ContainerBuilder();

        $extension = new SiganushkaApiClientExtension();
        $extension->load([$configs], $container);

        return $container;
    }
}

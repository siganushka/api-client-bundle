<?php

declare(strict_types=1);

namespace Siganushka\ApiClientBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\Github\Authorize;
use Siganushka\ApiClient\Github\Configuration as GithubConfiguration;
use Siganushka\ApiClient\RequestClientInterface;
use Siganushka\ApiClient\RequestRegistryInterface;
use Siganushka\ApiClient\Wechat\Configuration as WechatConfiguration;
use Siganushka\ApiClient\Wechat\Payment\ParameterUtils;
use Siganushka\ApiClient\Wechat\Payment\SignatureUtils;
use Siganushka\ApiClientBundle\DependencyInjection\SiganushkaApiClientExtension;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SiganushkaApiClientExtensionTest extends TestCase
{
    public function testAll(): void
    {
        $container = $this->createContainerWithConfigs([]);

        static::assertTrue($container->hasDefinition('siganushka.api_client.request_registry'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.request_client'));
        static::assertTrue($container->hasAlias(RequestRegistryInterface::class));
        static::assertTrue($container->hasAlias(RequestClientInterface::class));

        $requestRegistryDef = $container->getDefinition('siganushka.api_client.request_registry');
        static::assertInstanceOf(TaggedIteratorArgument::class, $requestRegistryDef->getArgument(0));

        $requestClientDef = $container->getDefinition('siganushka.api_client.request_client');
        static::assertSame('http_client', (string) $requestClientDef->getArgument(0));
        static::assertSame('cache.app', (string) $requestClientDef->getArgument(1));
        static::assertSame('siganushka.api_client.request_registry', (string) $requestClientDef->getArgument(2));
    }

    public function testWithWechatConfigs(): void
    {
        $configs = [
            'appid' => 'test_appid',
            'appsecret' => 'test_appsecret',
            'mchid' => 'test_mchid',
            'mchkey' => 'test_mchkey',
            'client_cert_file' => 'test_client_cert_file',
            'client_key_file' => 'test_client_key_file',
            'sign_type' => 'test_sign_type',
        ];

        $container = $this->createContainerWithConfigs(['wechat' => $configs]);

        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.configuration'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.core.access_token'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.core.server_ip'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.miniapp.session_key'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.payment.parameter_utils'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.payment.signature_utils'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.payment.unifiedorder'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.payment.transfer'));

        static::assertTrue($container->hasAlias(WechatConfiguration::class));
        static::assertTrue($container->hasAlias(ParameterUtils::class));
        static::assertTrue($container->hasAlias(SignatureUtils::class));

        $configurationDef = $container->getDefinition('siganushka.api_client.wechat.configuration');
        /**
         * @var array{
         *  appid: string,
         *  appsecret: string,
         *  mchid: string,
         *  mchkey: string,
         *  client_cert_file: string,
         *  client_key_file: string,
         *  sign_type: string
         * }
         */
        $configurationArgs = $configurationDef->getArgument(0);

        static::assertSame($configs['appid'], $configurationArgs['appid']);
        static::assertSame($configs['appsecret'], $configurationArgs['appsecret']);
        static::assertSame($configs['mchid'], $configurationArgs['mchid']);
        static::assertSame($configs['mchkey'], $configurationArgs['mchkey']);
        static::assertSame($configs['client_cert_file'], $configurationArgs['client_cert_file']);
        static::assertSame($configs['client_key_file'], $configurationArgs['client_key_file']);
        static::assertSame($configs['sign_type'], $configurationArgs['sign_type']);
    }

    public function testWithGithubConfigs(): void
    {
        $configs = [
            'client_id' => 'test_client_id',
            'client_secret' => 'test_client_secret',
        ];

        $container = $this->createContainerWithConfigs(['github' => $configs]);

        static::assertTrue($container->hasDefinition('siganushka.api_client.github.configuration'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.github.authorize'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.github.access_token'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.github.user'));

        static::assertTrue($container->hasAlias(GithubConfiguration::class));
        static::assertTrue($container->hasAlias(Authorize::class));

        $configurationDef = $container->getDefinition('siganushka.api_client.github.configuration');
        /**
         * @var array{
         *  client_id: string,
         *  client_secret: string
         * }
         */
        $configurationArgs = $configurationDef->getArgument(0);

        static::assertSame($configs['client_id'], $configurationArgs['client_id']);
        static::assertSame($configs['client_secret'], $configurationArgs['client_secret']);
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

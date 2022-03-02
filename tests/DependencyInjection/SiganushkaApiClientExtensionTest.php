<?php

declare(strict_types=1);

namespace Siganushka\ApiClientBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClient\RequestClientInterface;
use Siganushka\ApiClient\RequestRegistryInterface;
use Siganushka\ApiClient\Wechat\Configuration;
use Siganushka\ApiClient\Wechat\Payment\ParameterManager;
use Siganushka\ApiClient\Wechat\Payment\SignatureManager;
use Siganushka\ApiClientBundle\DependencyInjection\SiganushkaApiClientExtension;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SiganushkaApiClientExtensionTest extends TestCase
{
    public function testAll(): void
    {
        $container = $this->createContainerWithConfigs([]);
        // dd($container->getServiceIds());

        static::assertTrue($container->hasDefinition('siganushka.api_client.request_registry'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.request_client'));
        static::assertTrue($container->hasAlias(RequestRegistryInterface::class));
        static::assertTrue($container->hasAlias(RequestClientInterface::class));

        $requestRegistryDef = $container->getDefinition('siganushka.api_client.request_registry');
        static::assertInstanceOf(TaggedIteratorArgument::class, $requestRegistryDef->getArgument(0));

        $requestClientDef = $container->getDefinition('siganushka.api_client.request_client');
        static::assertSame('http_client', (string) $requestClientDef->getArgument(0));
        static::assertSame('siganushka.api_client.request_registry', (string) $requestClientDef->getArgument(1));
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

        $container = $this->createContainerWithConfigs([
            ['wechat' => $configs],
        ]);

        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.configuration'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.parameter_manager'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.signature_manager'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.core.access_token_request'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.core.server_ip_request'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.miniapp.session_key_request'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.payment.unifiedorder_request'));
        static::assertTrue($container->hasDefinition('siganushka.api_client.wechat.payment.transfer_request'));

        static::assertTrue($container->hasAlias(Configuration::class));
        static::assertTrue($container->hasAlias(ParameterManager::class));
        static::assertTrue($container->hasAlias(SignatureManager::class));

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

    /**
     * @param array<mixed> $configs
     */
    protected function createContainerWithConfigs(array $configs): ContainerBuilder
    {
        $container = new ContainerBuilder();

        $extension = new SiganushkaApiClientExtension();
        $extension->load($configs, $container);

        return $container;
    }
}

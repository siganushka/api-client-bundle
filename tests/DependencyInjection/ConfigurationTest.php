<?php

declare(strict_types=1);

namespace Siganushka\ApiClientBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiClientBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    private ?ConfigurationInterface $configuration = null;
    private ?Processor $processor = null;

    protected function setUp(): void
    {
        $this->configuration = new Configuration();
        $this->processor = new Processor();
    }

    protected function tearDown(): void
    {
        $this->configuration = null;
        $this->processor = null;
    }

    public function testDefaultConfig(): void
    {
        $treeBuilder = $this->configuration->getConfigTreeBuilder();

        static::assertInstanceOf(ConfigurationInterface::class, $this->configuration);
        static::assertInstanceOf(TreeBuilder::class, $treeBuilder);

        $processedConfig = $this->processor->processConfiguration($this->configuration, []);

        static::assertSame($processedConfig, [
            'wechat' => [
                'enabled' => false,
                'mchid' => null,
                'mchkey' => null,
                'client_cert_file' => null,
                'client_key_file' => null,
                'sign_type' => 'MD5',
            ],
        ]);
    }

    public function testCustomWechat(): void
    {
        $config = [
            'appid' => 'test_appid',
            'appsecret' => 'test_appsecret',
            'mchid' => 'test_mchid',
            'mchkey' => 'test_mchkey',
            'client_cert_file' => 'test_client_cert_file',
            'client_key_file' => 'test_client_key_file',
            'sign_type' => 'test_sign_type',
        ];

        $processedConfig = $this->processor->processConfiguration($this->configuration, [
            ['wechat' => $config],
        ]);

        static::assertSame($processedConfig['wechat'], array_merge($config, ['enabled' => true]));
    }

    public function testCustomWechatMissingAppidException(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child config "appid" under "siganushka_api_client.wechat" must be configured');

        $config = [
            'appsecret' => 'test_appsecret',
        ];

        $this->processor->processConfiguration($this->configuration, [
            ['wechat' => $config],
        ]);
    }

    public function testCustomWechatMissingAppsecretException(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child config "appsecret" under "siganushka_api_client.wechat" must be configured');

        $config = [
            'appid' => 'test_appid',
        ];

        $this->processor->processConfiguration($this->configuration, [
            ['wechat' => $config],
        ]);
    }
}

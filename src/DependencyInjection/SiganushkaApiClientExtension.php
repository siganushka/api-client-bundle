<?php

declare(strict_types=1);

namespace Siganushka\ApiClientBundle\DependencyInjection;

use Composer\InstalledVersions;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SiganushkaApiClientExtension extends Extension
{
    /**
     * @param array<mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__).'/Resources/config'));
        $loader->load('services.php');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if ($this->isConfigEnabled($container, $config['wechat'])) {
            if (!InstalledVersions::isInstalled('siganushka/wechat-api')) {
                throw new \LogicException('Wechat API support cannot be enabled as the Wechat API is not installed. Try running "composer require siganushka/wechat-api".');
            }

            $this->registerWechatConfiguration($config['wechat'], $container, $loader);
        }

        if ($this->isConfigEnabled($container, $config['github'])) {
            if (!InstalledVersions::isInstalled('siganushka/github-api')) {
                throw new \LogicException('Github API support cannot be enabled as the Github API is not installed. Try running "composer require siganushka/github-api".');
            }

            $this->registerGithubConfiguration($config['github'], $container, $loader);
        }
    }

    /**
     * @param array<mixed> $config
     */
    private function registerWechatConfiguration(array $config, ContainerBuilder $container, PhpFileLoader $loader): void
    {
        $loader->load('wechat.php');

        $configurationDef = $container->getDefinition('siganushka.api_client.wechat.configuration');
        $configurationDef->setArgument(0, [
            'appid' => $config['appid'],
            'appsecret' => $config['appsecret'],
            'mchid' => $config['mchid'],
            'mchkey' => $config['mchkey'],
            'client_cert_file' => $config['client_cert_file'],
            'client_key_file' => $config['client_key_file'],
            'sign_type' => $config['sign_type'],
        ]);
    }

    /**
     * @param array<mixed> $config
     */
    private function registerGithubConfiguration(array $config, ContainerBuilder $container, PhpFileLoader $loader): void
    {
        $loader->load('github.php');

        $configurationDef = $container->getDefinition('siganushka.api_client.github.configuration');
        $configurationDef->setArgument(0, [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
        ]);
    }
}

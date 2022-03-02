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
        dd($config);

        if ($this->isConfigEnabled($container, $config['wechat'])) {
            $this->registerWechatConfiguration($config['wechat'], $container, $loader);
        }

        // foreach ([
        //     'siganushka/wechat-api',
        // ] as $packageName) {
        //     if (!InstalledVersions::isInstalled($packageName)) {
        //         continue;
        //     }

        //     $configFile = sprintf('%s/config/services.php', InstalledVersions::getInstallPath($packageName));
        //     if (is_file($configFile)) {
        //         $loader->load($configFile);
        //     }
        // }
    }

    private function registerWechatConfiguration(array $config, ContainerBuilder $container, PhpFileLoader $loader): void
    {
        $loader->load('wechat.php');

        $configurationDef = $container->getDefinition('siganushka.api_client.wechat.configuration');
        $configurationDef->setArgument(0, [
            'appid' => $config['appid'],
            'appsecret' => $config['appsecret'],
        ]);
    }
}

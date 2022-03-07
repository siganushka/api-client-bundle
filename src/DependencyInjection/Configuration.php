<?php

declare(strict_types=1);

namespace Siganushka\ApiClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('siganushka_api_client');
        /** @var ArrayNodeDefinition */
        $rootNode = $treeBuilder->getRootNode();

        $this->addWechatSection($rootNode);
        $this->addGithubSection($rootNode);

        return $treeBuilder;
    }

    private function addWechatSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('wechat')
                    ->info('wechat configuration')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('appid')->isRequired()->end()
                        ->scalarNode('appsecret')->isRequired()->end()
                        ->scalarNode('mchid')->defaultNull()->end()
                        ->scalarNode('mchkey')->defaultNull()->end()
                        ->scalarNode('client_cert_file')->defaultNull()->end()
                        ->scalarNode('client_key_file')->defaultNull()->end()
                        ->scalarNode('sign_type')->defaultValue('MD5')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addGithubSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('github')
                    ->info('github configuration')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('client_id')->isRequired()->end()
                        ->scalarNode('client_secret')->isRequired()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}

<?php

namespace Jariff\AuthorizeNetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jariff_authorize_net');

        $rootNode
            ->children()
                ->scalarNode('api_login_id')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('api_transaction_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->booleanNode('sandbox')
                    ->isRequired()
                    ->defaultValue(true)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 15/06/2018
 * Time: 08:23
 */

namespace MKebza\SonataExt\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mkebza_ext');

        $rootNode
            ->children()
                ->arrayNode('admin')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('user')->defaultTrue()->end()
                            ->booleanNode('user_group')->defaultTrue()->end()
                            ->booleanNode('action_log')->defaultTrue()->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('action_log_user_detail_route')->defaultValue('backend_user_edit')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
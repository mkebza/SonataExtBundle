<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
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
                ->arrayNode('dashboard')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('admin_homepage')->defaultValue('MKebza\SonataExt\Dashboard\AdminDashboard')->end()
                    ->end()
                ->end()
                ->arrayNode('admin')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('user')->defaultTrue()->end()
                        ->booleanNode('user_group')->defaultTrue()->end()
                        ->booleanNode('log')->defaultTrue()->end()
                        ->booleanNode('cron')->defaultTrue()->end()
                    ->end()
                ->end()
                ->arrayNode('action_log')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('user_detail_route')->defaultValue('admin_user_edit')->end()
                    ->end()
                ->end()
                ->scalarNode('user_entity')->defaultValue('App\Entity\User')->end()
                ->scalarNode('user_group_entity')->defaultValue('App\Entity\UserGroup')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

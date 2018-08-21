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
                ->arrayNode('options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue('My new APP')->end()
                        ->scalarNode('name_short')->defaultValue('APP')->end()
                        ->scalarNode('logo_admin')->defaultNull()->end()
                        ->scalarNode('logo_email')->defaultNull()->end()
                        ->scalarNode('email_from')->defaultValue('foo@bar.com')->end()
                        ->scalarNode('email_from_name')->defaultValue('FooBar')->end()
                    ->end()
                ->end()
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
                ->scalarNode('user_entity')->defaultValue('App\Entity\User')->end()
                ->scalarNode('user_group_entity')->defaultValue('App\Entity\UserGroup')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

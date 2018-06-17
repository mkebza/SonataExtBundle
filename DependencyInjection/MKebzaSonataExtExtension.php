<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 09/06/2018
 * Time: 16:56
 */

namespace MKebza\SonataExt\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class MKebzaSonataExtExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        $container->loadFromExtension('twig', [
            'paths' => [
                '%kernel.project_dir%/vendor/mkebza/sonata-ext-bundle/Resources/views/sonata' =>  'SonataAdmin',
                '%kernel.project_dir%/vendor/mkebza/sonata-ext-bundle/Resources/views/ext' => 'SonataExt',
                '%kernel.project_dir%/vendor/mkebza/sonata-ext-bundle/Resources/views/fos-user' => 'FOSUser',
            ]
        ]);
    }


    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yaml');

        // Include registered admins
        foreach ($config['admin'] as $name => $enabled) {
            if ($enabled) {
                $loader->load('admin/'.$name.'.yaml');
            }
        }

        if (is_string($config['action_log']['user_detail_route']) && $container->hasDefinition('backend.action_log')) {
            $container->getDefinition('backend.action_log')->addMethodCall('setUserDetailRouteName', [$config['action_log']['user_detail_route']]);
        }
    }
}
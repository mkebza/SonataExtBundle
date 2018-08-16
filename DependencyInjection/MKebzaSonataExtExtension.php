<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 09/06/2018
 * Time: 16:56
 */

namespace MKebza\SonataExt\DependencyInjection;

use MKebza\SonataExt\Controller\DashboardController;
use MKebza\SonataExt\Dashboard\DashboardRenderer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class MKebzaSonataExtExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        // Load custom security handler
        $container->loadFromExtension('sonata_admin', [
            'show_mosaic_button' => false,
            'search' => false,
            'options' => [
                'use_stickyforms' => true,
                'html5_validate' => false,
                'form_type' => 'horizontal',
                'title_mode' => 'both',
            ],
            'security' => [
                'handler' =>  'MKebza\SonataExt\Sonata\RoleSecurityHandler'
            ]
        ]);

        // Add paths to namespaces so we can override some bundles paths
        $container->loadFromExtension('twig', [
            'paths' => [
                '%kernel.project_dir%/vendor/mkebza/sonata-ext-bundle/Resources/views/email' =>  'Email',
                '%kernel.project_dir%/templates/email' =>  'Email',
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

        $container->setParameter('sonata_ext.user_entity', $config['user_entity']);
        $container->setParameter('sonata_ext.user_group_entity', $config['user_group_entity']);

        $loader->load('services.yaml');
        $loader->load('dashboard.yaml');

        // Include registered admins
        foreach ($config['admin'] as $name => $enabled) {
            if ($enabled) {
                $loader->load('admin/'.$name.'.yaml');
            }
        }

        // User route for action log
        if (is_string($config['action_log']['user_detail_route']) && $container->hasDefinition('sonata.admin.action_log')) {
            $container->getDefinition('sonata.admin.action_log')->addMethodCall('setUserDetailRouteName', [$config['action_log']['user_detail_route']]);
        }

        // Set default builder for dashboard
        if ($container->hasDefinition(DashboardController::class)) {
            $container
                ->getDefinition(DashboardController::class)
                ->setArgument('$dashboard', new Reference($config['dashboard']['admin_homepage']));
        }

        // Chnage of skin

    }
}
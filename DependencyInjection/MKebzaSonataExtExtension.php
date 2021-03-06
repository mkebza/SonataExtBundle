<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\DependencyInjection;

use MKebza\SonataExt\Controller\DashboardController;
use MKebza\SonataExt\ORM\Type\CarbonDateTimeType;
use MKebza\SonataExt\ORM\Type\CarbonDateType;
use MKebza\SonataExt\ORM\Type\CarbonTimeType;
use MKebza\SonataExt\ORM\Type\LoginAttemptResultType;
use MKebza\SonataExt\ORM\Type\LogLevelType;
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
        // Configure sonata
        $container->loadFromExtension('sonata_admin', [
            'show_mosaic_button' => false,
            'search' => false,
            'options' => [
                'use_stickyforms' => false,
                'html5_validate' => false,
                'form_type' => 'horizontal',
                'title_mode' => 'both',
            ],
            'security' => [
                'handler' => 'MKebza\SonataExt\Sonata\RoleSecurityHandler',
            ],
        ]);

        // Configure doctrine
        $container->loadFromExtension('doctrine', [
            'dbal' => [
                // Custom types
                'types' => [
                    'time' => CarbonTimeType::class,
                    'date' => CarbonDateType::class,
                    'datetime' => CarbonDateTimeType::class,
                    'log_level' => LogLevelType::class,
                    'login_attempt_result' => LoginAttemptResultType::class,
                ],
            ],
        ]);

        $container->loadFromExtension('sonata_doctrine_orm_admin', [
            'templates' => [
                // Custom types
                'types' => [
                    'list' => [
                        'money' => '@SonataAdmin/CRUD/list_money.html.twig',
                    ],
                ],
            ],
        ]);

        // Configure twig paths
        $twigPaths = [
            '%kernel.project_dir%/vendor/mkebza/sonata-ext-bundle/Resources/views/email' => 'Email',
            '%kernel.project_dir%/vendor/mkebza/sonata-ext-bundle/Resources/views/sonata' => 'SonataAdmin',
            '%kernel.project_dir%/vendor/mkebza/sonata-ext-bundle/Resources/views/ext' => 'SonataExt',
        ];
        // Only if emails exists, otherwise no need
        $emailPath = $container->getParameter('kernel.project_dir').'/templates/email';
        if (file_exists($emailPath) && is_dir($emailPath)) {
            $twigPaths = array_merge($twigPaths, ['%kernel.project_dir%/templates/email' => 'Email']);
        }
        $container->loadFromExtension('twig', ['paths' => $twigPaths]);
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

        $container->setParameter('app.name', $config['options']['name']);
        $container->setParameter('app.name_short', $config['options']['name_short']);
        $container->setParameter('app.logo.admin', $config['options']['logo_admin']);
        $container->setParameter('app.logo.email', $config['options']['logo_email']);
        $container->setParameter('app.email.from', $config['options']['email_from']);
        $container->setParameter('app.email.from_name', $config['options']['email_from_name']);

        $loader->load('services.yaml');
        $loader->load('dashboard.yaml');

        if ($container->getParameter('kernel.environment') !== 'prod') {
            $loader->load('services_dev.yaml');
        }

        // Include registered admins
        foreach ($config['admin'] as $name => $enabled) {
            if ($enabled) {
                $loader->load('admin/'.$name.'.yaml');
            }
        }

        // Set default builder for dashboard
        if ($container->hasDefinition(DashboardController::class)) {
            $container
                ->getDefinition(DashboardController::class)
                ->setArgument('$dashboard', new Reference($config['dashboard']['admin_homepage']));
        }
    }
}

<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\DependencyInjection\CompilerPass;

use MKebza\SonataExt\ORM\SonataExtUserGroupInterface;
use MKebza\SonataExt\ORM\SonataExtUserInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AutoResolveTargetEntitiesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $def = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        $def->addMethodCall('addResolveTargetEntity', [
            SonataExtUserInterface::class,
            $container->getParameter('sonata_ext.user_entity'),
            [],
        ]);

        $def->addMethodCall('addResolveTargetEntity', [
            SonataExtUserGroupInterface::class,
            $container->getParameter('sonata_ext.user_group_entity'),
            [],
        ]);

        $def->addTag('doctrine.event_subscriber');
    }
}

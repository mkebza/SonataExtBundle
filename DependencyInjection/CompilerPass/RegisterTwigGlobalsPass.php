<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterTwigGlobalsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $twig = $container->getDefinition('twig');

        $twig->addMethodCall('addGlobal', ['config', [
            'app' => [
                'name' => $container->getParameter('app.name'),
                'name_short' => $container->getParameter('app.name_short'),
            ],
            'logo' => [
                'email' => $container->getParameter('app.logo.email'),
                'admin' => $container->getParameter('app.logo.admin'),
            ],
        ]]);
    }
}

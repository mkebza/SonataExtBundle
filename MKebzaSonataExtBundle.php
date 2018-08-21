<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt;

use MKebza\SonataExt\DependencyInjection\CompilerPass\AutoResolveTargetEntitiesPass;
use MKebza\SonataExt\DependencyInjection\CompilerPass\RegisterTwigGlobalsPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MKebzaSonataExtBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterTwigGlobalsPass());

        // For this one we need to run before doctrine event_subscriber are done, so it be registered (Because we are adding new]
        $container->addCompilerPass(
            new AutoResolveTargetEntitiesPass(),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            100);
    }
}

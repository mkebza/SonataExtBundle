<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 10/06/2018
 * Time: 15:54
 */

namespace MKebza\SonataExt;

use MKebza\SonataExt\DependencyInjection\CompilerPass\ActionLogAddUserRoutePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MKebzaSonataExtBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
    }

}
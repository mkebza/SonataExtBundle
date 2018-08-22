<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Twig\Extension;

use MKebza\SonataExt\Twig\Runtime\LastLoginRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LastLoginExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('last_login', [LastLoginRuntime::class, 'getLastLogin']),
        ];
    }
}

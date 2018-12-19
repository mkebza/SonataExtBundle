<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Twig\Extension;

use MKebza\SonataExt\Twig\Runtime\EnumRuntime;
use MKebza\SonataExt\Twig\Runtime\TextRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class EnumExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('enum_choices', [EnumRuntime::class, 'choices']),
        ];
    }
}

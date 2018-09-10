<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Twig\Extension;

use MKebza\SonataExt\Twig\Runtime\TextRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TextExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('text_itemize', [TextRuntime::class, 'itemize']),
            new TwigFunction('text_fqn', [TextRuntime::class, 'fqn']),
            new TwigFunction('text_print_r', [TextRuntime::class, 'print_r']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('text_itemize', [TextRuntime::class, 'itemize']),
        ];
    }
}

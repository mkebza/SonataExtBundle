<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class TextRuntime implements RuntimeExtensionInterface
{
    public function itemize(string $text): array
    {
        $items = explode("\n", $text);
        $items = array_map('trim', $items);
        $items = array_filter($items);

        return array_merge($items);
    }
}

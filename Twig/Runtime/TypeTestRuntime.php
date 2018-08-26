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

class TypeTestRuntime implements RuntimeExtensionInterface
{
    public function isArray($value)
    {
        return is_array($value);
    }
}

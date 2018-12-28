<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Twig\Runtime;

use Elao\Enum\EnumInterface;
use Twig\Extension\RuntimeExtensionInterface;

class EnumRuntime implements RuntimeExtensionInterface
{
    public function choices(string $enum): array
    {
        $refClass = new \ReflectionClass($enum);
        if (!$refClass->implementsInterface(EnumInterface::class)) {
            throw new \LogicException(sprintf(
                "You have to provde name of enum class, provided '%s' and enum has to implement '%s'",
                $enum, EnumInterface::class));
        }

        return $enum::readables();
    }
}

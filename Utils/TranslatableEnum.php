<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Utils;

use Elao\Enum\AutoDiscoveredValuesTrait;
use Elao\Enum\ChoiceEnumTrait;
use Elao\Enum\ReadableEnum;

abstract class TranslatableEnum extends ReadableEnum
{
    use AutoDiscoveredValuesTrait {
        choices as protected traitChoices;
    }

    use ChoiceEnumTrait {
        ChoiceEnumTrait::values insteadof AutoDiscoveredValuesTrait;
    }

    protected static function choices(): array
    {
        $choices = self::traitChoices();
        foreach ($choices as $k => $v) {
            $choices[$k] = sprintf('enum.%s.%s',
                (new \ReflectionClass(static::class))->getShortName(),
                mb_strtolower($v)
            );
        }

        return $choices;
    }
}

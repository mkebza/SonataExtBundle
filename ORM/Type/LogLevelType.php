<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\ORM\Type;

use Elao\Enum\Bridge\Doctrine\DBAL\Types\AbstractEnumType;
use MKebza\SonataExt\Enum\LogLevel;

final class LogLevelType extends AbstractEnumType
{
    const NAME = 'log_level';

    public function getName()
    {
        return static::NAME;
    }

    protected function getEnumClass(): string
    {
        return LogLevel::class;
    }
}

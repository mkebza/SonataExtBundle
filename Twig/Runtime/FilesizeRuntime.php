<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Twig\Runtime;

use MKebza\SonataExt\Utils\FilesizeFormatter;
use Twig\Extension\RuntimeExtensionInterface;

class FilesizeRuntime implements RuntimeExtensionInterface
{
    public function filesizeBinary($value, $decimals = 2)
    {
        return FilesizeFormatter::binary($value, $decimals);
    }

    public function filesize($value, $decimals = 2)
    {
        return FilesizeFormatter::format($value, $decimals);
    }
}

<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Twig\Extension;

use MKebza\SonataExt\Twig\Runtime\FilesizeRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FilesizeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('filesize', [FilesizeRuntime::class, 'filesize']),
        ];
    }
}

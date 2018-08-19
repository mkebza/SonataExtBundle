<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Exception;

class BlockNotFoundException extends \LogicException
{
    public static function create(string $alias): self
    {
        return new self(sprintf("Can't find block with alias '%s', does your block has `dashboard.block` tag?", $alias));
    }
}

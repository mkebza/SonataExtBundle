<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 21/06/2018
 * Time: 10:12
 */

namespace MKebza\SonataExt\Exception;

class BlockNotFoundException extends \LogicException
{
    public static function create(string $alias): self
    {
        return new self(sprintf("Can't find block with alias '%s', does your block has `dashboard.block` tag?", $alias));
    }
}
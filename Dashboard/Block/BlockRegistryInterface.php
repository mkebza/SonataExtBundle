<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard\Block;

interface BlockRegistryInterface
{
    public function add(BlockInterface $block): void;

    public function has(string $alias): bool;

    public function get(string $alias): BlockInterface;
}

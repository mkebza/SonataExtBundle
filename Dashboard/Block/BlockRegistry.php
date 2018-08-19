<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard\Block;

use MKebza\SonataExt\Exception\BlockNotFoundException;

class BlockRegistry implements BlockRegistryInterface
{
    /**
     * @var BlockInterface[]
     */
    private $blocks = [];

    /**
     * BlockRegistry constructor.
     *
     * @param BlockInterface[] $blocks
     */
    public function __construct(iterable $blocks)
    {
        foreach ($blocks as $block) {
            $this->add($block);
        }
    }

    public function add(BlockInterface $block): void
    {
        $this->blocks[$block->getAlias()] = $block;
    }

    public function has(string $alias): bool
    {
        return isset($this->blocks[$alias]);
    }

    public function get(string $alias): BlockInterface
    {
        if (!$this->has($alias)) {
            throw BlockNotFoundException::create($alias);
        }

        return $this->blocks[$alias];
    }
}

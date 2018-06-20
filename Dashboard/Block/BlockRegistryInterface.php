<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 15:41
 */

namespace MKebza\SonataExt\Dashboard\Block;


interface BlockRegistryInterface
{
    public function add(BlockInterface $block): void;
    public function has(string $alias): bool;
    public function get(string $alias): BlockInterface;
}
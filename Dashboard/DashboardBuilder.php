<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 15:40
 */

namespace MKebza\SonataExt\Dashboard;


use MKebza\SonataExt\Dashboard\Block\BlockRegistryInterface;

class DashboardBuilder implements DashboardBuilderInterface
{
    protected $blocks = [];

    public function add(string $name, string $block, array $options = []): self {
        $this->blocks[$name] = [
            'block' => $block,
            'options' => $options
        ];

        return $this;
    }

    public function resolve(): array
    {
        return $this->blocks;
    }
}
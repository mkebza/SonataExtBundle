<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard;

class DashboardBuilder implements DashboardBuilderInterface
{
    protected $blocks = [];

    public function add(string $name, string $block, array $options = []): self
    {
        $this->blocks[$name] = [
            'block' => $block,
            'options' => $options,
        ];

        return $this;
    }

    public function resolve(): array
    {
        return $this->blocks;
    }
}

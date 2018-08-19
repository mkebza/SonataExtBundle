<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard;

interface DashboardBuilderInterface
{
    public function add(string $name, string $block, array $options = []);

    public function resolve(): array;
}

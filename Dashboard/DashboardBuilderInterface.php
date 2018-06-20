<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 17:03
 */

namespace MKebza\SonataExt\Dashboard;


interface DashboardBuilderInterface
{
    public function add(string $name, string $block, array $options = []);
    public function resolve(): array;
}
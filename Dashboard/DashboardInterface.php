<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 16:37
 */

namespace MKebza\SonataExt\Dashboard;


interface DashboardInterface
{
    public function build(DashboardBuilderInterface $builder): void;
}
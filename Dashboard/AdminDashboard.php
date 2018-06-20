<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 16:38
 */

namespace MKebza\SonataExt\Dashboard;


use MKebza\SonataExt\Dashboard\Block\Type\AppInfoBlock;
use MKebza\SonataExt\Dashboard\Block\Type\CurrentUserInfoBlock;

class AdminDashboard implements DashboardInterface
{
    public function build(DashboardBuilderInterface $builder): void
    {
        $builder
            ->add('current_user_info', CurrentUserInfoBlock::class)
            ->add('app_info', AppInfoBlock::class)
        ;
    }
}
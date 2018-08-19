<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\EventListener\Sonata;

use Sonata\AdminBundle\Event\ConfigureMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MenuDashboardSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'sonata.admin.event.configure.menu.sidebar' => ['addDashboardLink', 1],
        ];
    }

    public function addDashboardLink(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $dashboardItem = $event->getFactory()
            ->createItem('<span>Dashboard</span>', [
                'route' => 'sonata_admin_dashboard',
                'escape' => false,
                'on_top' => true,
            ])
            ->setExtra('icon', '<i class="fa fa-tachometer"></i>')
            ->setExtra('safe_label', true)
        ;

        // Add to first position
        $menu->setChildren([$dashboardItem->getLabel() => $dashboardItem] + $menu->getChildren());
    }
}

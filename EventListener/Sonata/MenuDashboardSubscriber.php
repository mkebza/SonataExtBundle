<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 21/06/2018
 * Time: 14:37
 */

namespace MKebza\SonataExt\EventListener\Sonata;

use Sonata\AdminBundle\Event\ConfigureMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MenuDashboardSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'sonata.admin.event.configure.menu.sidebar' => ['addDashboardLink', 1]
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
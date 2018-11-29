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
use Symfony\Component\Translation\TranslatorInterface;

class MenuDashboardSubscriber implements EventSubscriberInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * MenuDashboardSubscriber constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getSubscribedEvents()
    {
        return [
            'sonata.admin.event.configure.menu.sidebar' => ['addDashboardLink', 1],
        ];
    }

    public function addDashboardLink(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $name = $this->translator->trans('menu.item.dashboard', [], 'admin');
        $dashboardItem = $event->getFactory()
            ->createItem(sprintf('<span>%s</span>', $name), [
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

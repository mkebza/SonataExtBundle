<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Admin;

use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin as BaseAbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

abstract class AbstractAdmin extends BaseAbstractAdmin
{
    protected $translationDomain = 'admin';

    public function createLogTabMenuItem(string $routeName = null, array $params = ['id']): array
    {
        return $this->createTabMenuItem(
            'Log',
            (null === $routeName ? sprintf('%s_log_list', $this->baseRouteName) : $routeName),
            $params,
            'bars');
    }

    public function createParentTabMenuItem($route = null, array $params = ['id']): array
    {
        return $this->createTabMenuItem(
            'Parent',
            (null === $route ? $this->baseRouteName.'_edit' : $route),
            $params,
            'chevron-left');
    }

    public function getExportFormats()
    {
        return [];
    }

    /**
     * @return \App\Entity\User
     */
    public function getUser()
    {
        return $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
    }

    public function isEditing()
    {
        return $this->id($this->getSubject());
    }

    public function isCreating()
    {
        return null === $this->id($this->getSubject());
    }

    public function isGrantedSymfony($attributes, $subject = null): bool
    {
        return $this->getConfigurationPool()
            ->getContainer()
            ->get('security.authorization_checker')
            ->isGranted($attributes, $subject);
    }

    public function createTabMenu(array $map, string $currentAction, ItemInterface $root, AdminInterface $currentAdmin): void
    {
        $menuDefinition = null;

        $currentAdminClasss = get_class($currentAdmin);
        if (isset($map[$currentAdminClasss])) {
            foreach ($map[$currentAdminClasss] as $menu) {
                if (in_array($currentAction, $menu['actions'], true)) {
                    $menuDefinition = $menu['items'];

                    break;
                }
            }
        }

        if (isset($map[$currentAdmin->getCode()])) {
            foreach ($map[$currentAdmin->getCode()] as $menu) {
                if (in_array($currentAction, $menu['actions'], true)) {
                    $menuDefinition = $menu['items'];

                    break;
                }
            }
        }

        if (null === $menuDefinition) {
            return;
        }

        foreach ($menuDefinition as $itemDefinition) {
            $item = $root->addChild($itemDefinition[0], $itemDefinition[1]);
            if (!empty($itemDefinition[2])) {
                $item->setAttributes($itemDefinition[2]);
            }
        }
    }

    public function getTabMenuMap(): array
    {
        return [];
    }

    public function createTabMenuItem($name, $route, $routeParams, $icon = null): array
    {
        $routeParams = array_combine($routeParams, $routeParams);
        $routeParams = array_map(function ($name) {
            return $this->getRequest()->get($name);
        }, $routeParams);

        $item = [
            $name,
            ['route' => $route, 'routeParameters' => $routeParams],
            ['icon' => 'fa fa-'.$icon],
        ];

        return $item;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
        $collection->remove('export');
    }

    protected function configureTabMenu(ItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if ($this->hasChildren() && !empty($this->getTabMenuMap())) {
            $admin = $this->getCurrentLeafChildAdmin() ? $this->getCurrentLeafChildAdmin() : $this;
            $this->createTabMenu($this->getTabMenuMap(), $action, $menu, $admin);
        }
    }
}

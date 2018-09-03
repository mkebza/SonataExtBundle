<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Admin;

use MKebza\SonataExt\Form\Type\UserGroup\UserGroupSecurityRolesType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserGroupAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'user-group';
    protected $baseRouteName = 'admin_user_group';
    protected $translationDomain = 'admin';

    public function getTabMenuMap(): array
    {
        return [
            self::class => [
                [
                    'actions' => ['edit'],
                    'items' => [
                        $this->createTabMenuItem('Log', 'admin_user_group_log_list', ['id'], 'bars'),
                    ],
                ],
            ],
        ];
    }

    protected function configureFormFields(FormMapper $form)
    {
        parent::configureFormFields($form);

        $form
            ->tab('UserGroup.tab.general')
                ->with(null)
                    ->add('name')
                ->end();

        if ($this->isGranted('ROLE_GRANT')) {
            $form
                ->with('UserGroup.panel.roles')
                    ->add('roles', UserGroupSecurityRolesType::class, ['group' => $this->getSubject()])
                ->end();
        }
        $form->end();
    }

    protected function configureListFields(ListMapper $list)
    {
        parent::configureListFields($list);

        $list->addIdentifier('name', 'string', ['label' => 'UserGroup.field.name']);

        if ($this->isGranted('ROLE_DEVELOPER')) {
            $list->add('key', null, ['label' => 'UserGroup.field.key']);
        }

        $list->add('_action', null, [
            'actions' => [
                'edit' => [],
                'delete' => [],
            ],
        ]);
    }
}

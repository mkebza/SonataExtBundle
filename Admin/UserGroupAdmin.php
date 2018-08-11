<?php

/*
 * (c) Marek Kebza <marek@kebza.cz>
 */

namespace MKebza\SonataExt\Admin;

use FOS\UserBundle\Model\UserManagerInterface;
use MKebza\SonataExt\Form\Type\UserGroup\UserGroupSecurityRolesType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserGroupAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'user-group';
    protected $baseRouteName = 'admin_user_group';
    protected $translationDomain = 'admin';

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
                'delete' => []
            ]
        ]);
    }
}

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
    protected $baseRouteName = 'be_user_group';


    protected function configureFormFields(FormMapper $form)
    {
        parent::configureFormFields($form);

        $form
            ->tab('Group')
                ->with('General')
                    ->add('name')
                ->end();

        if ($this->isGranted('ROLE_GRANT')) {
            $form
                ->with('Roles')
                    ->add('roles', UserGroupSecurityRolesType::class, ['group' => $this->getSubject()])
                ->end();
        }
        $form->end();
    }

    protected function configureListFields(ListMapper $list)
    {
        parent::configureListFields($list);

        $list
            ->addIdentifier('name', 'string', ['label' => 'Name'])
            ->add('_action', null, ['actions' => ['edit' => [], 'delete' => []]]);
    }
}

<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Admin;

use App\Entity\User;
use MKebza\SonataExt\Service\Logger\AdminLoggerTab;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserAdmin extends AbstractAdmin
{
    use AdminLoggerTab;

    protected $baseRoutePattern = 'user';
    protected $baseRouteName = 'admin_user';
    protected $translationDomain = 'admin';

    public function getFormBuilder()
    {
        $this->formOptions['validation_groups'] = (!$this->getSubject() || null === $this->getSubject()->getId()) ? 'Registration' : 'Profile';

        return parent::getFormBuilder();
    }

    public function prePersist($object)
    {
        $object->setPassword('test');
    }

    public function postUpdate($object)
    {
        parent::postUpdate($object); // TODO: Change the autogenerated stub

        $this->getConfigurationPool()->getContainer()->get('monolog.logger.action')->debug('Haha', ['references' => $object]);
    }

    protected function configureFormFields(FormMapper $form)
    {
        parent::configureFormFields($form);

        $this->tabGeneral($form);
        $form->tab('Security')
                ->with('Status')
                    ->add('active')
//                    ->add('plainPassword', PasswordType::class)
                ->end();

        if ($this->isGranted('ROLE_ADMIN_GRANT_PERMISSION')) {
            $form
                ->with('Groups')
                    ->add('groups', null, [
                        'multiple' => true,
                        'expanded' => true,
                    ])
                ->end()
            ;
        }

        $form->end();

        $this->tabLog($form);
    }

    protected function tabGeneral(FormMapper $form): void
    {
        $form
            ->tab('User')
                ->with('General')
                    ->add('email');

//        if (null === $this->id($this->getSubject())) {
//            $form->add('password', null, ['mapped']);
//        }

        $form
                ->end()
            ->end();
    }

    protected function configureListFields(ListMapper $list)
    {
        parent::configureListFields($list);

        $actions = ['edit' => []];

        if ($this->isGranted('ROLE_ALLOW_IMPERSONATE')) {
            $actions['switch'] = ['template' => ['@SonataExt/user/list/action_impersonate.html.twig']];
        }

        $actions['delete'] = [];

        $list
            ->addIdentifier('email', 'string', ['label' => 'E-mail'])
            ->add('active', 'boolean', ['label' => 'Active', 'editable' => true])
            ->add('lastLogin', 'datetime', ['label' => 'Last login'])
            ->add('created', 'date', ['label' => 'Created at'])
            ->add('_action', null, ['actions' => $actions]);
    }
}

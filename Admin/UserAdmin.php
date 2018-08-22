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
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordRequirements;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserAdmin extends AbstractAdmin
{
    use AdminLoggerTab;

    protected $baseRoutePattern = 'user';
    protected $baseRouteName = 'admin_user';

    public function getFormBuilder()
    {
        $this->formOptions['validation_groups'] = (!$this->getSubject() || null === $this->getSubject()->getId()) ? 'Registration' : 'Profile';

        return parent::getFormBuilder();
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

        if (null === $this->id($this->getSubject())) {
            $form->add('password', null, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new PasswordRequirements([
                        'requireCaseDiff' => true,
                        'requireNumbers' => true,
                        'minLength' => 10,
                    ]),
                ],
            ]);
        }

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
            ->addIdentifier('email', 'string', ['label' => 'User.field.email'])
            ->add('active', 'boolean', ['label' => 'User.field.active', 'editable' => true])
            ->add('created', 'date', ['label' => 'User.field.created'])
            ->add('_action', null, ['actions' => $actions]);
    }
}

<?php

/*
 * (c) Marek Kebza <marek@kebza.cz>
 */

namespace MKebza\SonataExt\Admin;

use App\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;
use MKebza\EntityHistory\Entity\EntityHistory;
use MKebza\EntityHistory\Sonata\AdminHistoryTab;
use MKebza\SonataExt\ActionLog\AdminActionLogTab;
use MKebza\SonataExt\Entity\ActionLog;
use MKebza\SonataExt\Form\Type\User\UserSecurityRolesType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Sonata\AdminBundle\Admin\AbstractAdmin;

class UserAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'user';
    protected $baseRouteName = 'be_user';

    use AdminActionLogTab;

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @param mixed $userManager
     */
    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function getFormBuilder()
    {
        $this->formOptions['validation_groups'] = (!$this->getSubject() || is_null($this->getSubject()->getId())) ? 'Registration' : 'Profile';

        return parent::getFormBuilder();
    }

    protected function configureFormFields(FormMapper $form)
    {
        parent::configureFormFields($form);

        $form
            ->tab('User')
                ->with('General')
                    ->add('email')
                    // Hack to go through validation instead of changing default groups
                    ->add('username', HiddenType::class, ['data' => uniqid('username_', true)])
                ->end()
            ->end()
            ->tab('Security')
                ->with('Status')
                    ->add('enabled')
                    ->add('plainPassword', PasswordType::class)
                ->end();

        if ($this->isGranted('ROLE_ADMIN_GRANT_PERMISSION')) {
            $form
                ->with('Groups')
                    ->add('groups', null, [
                        'multiple' => true,
                        'expanded' => true,
                    ])
                ->end()
                ->with('Roles')
                    ->add('roles', UserSecurityRolesType::class, ['user' => $this->getSubject()])
                ->end();
        }

        $form->end();

        $this->addActionLogTab($form);
    }

    /**
     * @param User $object
     */
    public function preValidate($object)
    {
        parent::preValidate($object);
        $object->preSaveUpdateUsername();
    }

    /**
     * @param $object User
     */
    public function preUpdate($object)
    {
        $object->logAction(ActionLog::success('Logged hahaa'));

        $this->userManager->updateCanonicalFields($object);
        $this->userManager->updatePassword($object);
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
            ->add('enabled', 'boolean', ['label' => 'Active', 'editable' => true])
            ->add('lastLogin', 'datetime', ['label' => 'Last login'])
            ->add('createdAt', 'date', ['label' => 'Created at'])
            ->add('_action', null, ['actions' => $actions]);
    }
}

<?php

/*
 * (c) Marek Kebza <marek@kebza.cz>
 */

namespace MKebza\SonataExt\Form\Type\User;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class UserSecurityRolesType extends AbstractType
{
    private $roles;
    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    /**
     * SecurityRolesType constructor.
     */
    public function __construct($roles, AccessDecisionManagerInterface $decisionManager)
    {
        $this->roles = $this->flatArray($roles);
        $this->decisionManager = $decisionManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $event->setData($this->getActualRoles($options['user']));
        });
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'user' => null,
            'choices' => $this->roles,
            'multiple' => true,
            'expanded' => true,
        ]);
    }

    protected function getActualRoles(UserInterface $user)
    {
        $token = new UsernamePasswordToken($user, 'none', 'none', $user->getRoles());

        return array_filter($this->roles, function ($role) use ($token) {
            return $this->decisionManager->decide($token, [$role]);
        });
    }

    private function flatArray(array $data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            if ('ROLE' === substr($key, 0, 4)) {
                $result[$key] = $key;
            }
            if (is_array($value)) {
                $tmpresult = $this->flatArray($value);
                if (count($tmpresult) > 0) {
                    $result = array_merge($result, $tmpresult);
                }
            } else {
                $result[$value] = $value;
            }
        }

        return array_unique($result);
    }
}

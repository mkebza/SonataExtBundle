<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Form\Type\UserGroup;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserGroupSecurityRolesType extends AbstractType
{
    private $roles;

    /**
     * SecurityRolesType constructor.
     *
     * @param mixed $roles
     */
    public function __construct($roles)
    {
        $this->roles = $this->flatArray($roles);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
//            $event->setData($this->getActualRoles($options['user']));
        });
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'group' => null,
                'choices' => $this->roles,
                'multiple' => true,
                'expanded' => true,
            ])
            ->setRequired('group')
        ;
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

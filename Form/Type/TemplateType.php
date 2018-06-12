<?php

/*
 * (c) Marek Kebza <marek@kebza.cz>
 */

namespace MKebza\SonataExt\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_merge($view->vars, [
            'template' => $options['template'],
            'vars' => $options['vars'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'template' => null,
                'vars' => [],
                'mapped' => false,
                'label' => false,
            ])
            ->setRequired('template')
            ->setAllowedTypes('template', ['string'])
        ;
    }
}

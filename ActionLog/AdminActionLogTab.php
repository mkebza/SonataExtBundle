<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 10/06/2018
 * Time: 14:57
 */

namespace MKebza\SonataExt\ActionLog;


use MKebza\SonataExt\Form\Type\TemplateType;
use MKebza\SonataExt\ORM\ActionLog\ActionLoggableInterface;
use Sonata\AdminBundle\Form\FormMapper;

trait AdminActionLogTab
{
    protected function addActionLogTab(FormMapper $formMapper): void
    {
        if (!$this->getSubject() instanceof ActionLoggableInterface) {
            throw new \LogicException(
                sprintf("Class %s is required to implement %s interface in order to display logged actions",
                    get_class($this->getSubject()), ActionLoggableInterface::class
                ));
        }

        $formMapper
            ->tab('action_log.admin_tab.label', ['translation_domain' => 'sonata_ext'])
                ->with(null)
                    ->add('_entity_history', TemplateType::class, [
                        'template' => '@SonataExt/action_log/tab_history.html.twig',
                        'vars' => [
                            'entries' => $this->getSubject()->getLoggedActions()
                        ]
                    ])
                ->end()
            ->end();
    }
}
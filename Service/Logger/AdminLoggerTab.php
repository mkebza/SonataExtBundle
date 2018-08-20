<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Service\Logger;

use MKebza\SonataExt\Form\Type\TemplateType;
use MKebza\SonataExt\ORM\Logger\LoggableInterface;
use Sonata\AdminBundle\Form\FormMapper;

trait AdminLoggerTab
{
    protected function tabLog(FormMapper $formMapper): void
    {
        if (!$this->getSubject() instanceof LoggableInterface) {
            throw new \LogicException(
                sprintf('Class %s is required to implement %s interface in order to display logged actions',
                    get_class($this->getSubject()), LoggableInterface::class
                ));
        }

        $formMapper
            ->tab('Log.action.adminTab.title', ['translation_domain' => 'admin'])
                ->with(null)
                    ->add('_log', TemplateType::class, [
                        'template' => '@SonataExt/log/part/admin_tab.html.twig',
                        'vars' => [
                            'entries' => $this->getSubject()->getLog(),
                        ],
                    ])
                ->end()
            ->end();
    }
}

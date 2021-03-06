<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Admin;

use MKebza\SonataExt\Entity\Log;
use MKebza\SonataExt\Enum\LogLevel;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DateRangePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LogReferenceAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'log';
    protected $baseRouteName = 'log';

    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'log.created',
    ];

    public function getActionButtons($action, $object = null)
    {
        return [];
    }

    public function getBatchActions()
    {
        return [];
    }

    public function getDatagrid()
    {
        return parent::getDatagrid(); // TODO: Change the autogenerated stub
    }

    public function getClassnameLabel()
    {
        // Better breadcrumbs name
        return 'Log';
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        if (!$this->isChild()) {
            $collection->clear();
        }

        $collection->clearExcept(['list']);
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $channels = $this->getConfigurationPool()
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Log::class)
            ->getChannels();

        $filter
            ->add('log.message', null, [
                'label' => 'Log.field.message',
            ])
            ->add('log.channel', 'doctrine_orm_choice', ['label' => 'Log.field.channel'], ChoiceType::class, [
                'choices' => array_combine($channels, $channels),
            ])
            ->add('log.user', null, [
                'label' => 'Log.field.user',
            ])
            ->add('log.level', 'doctrine_orm_choice', ['label' => 'Log.field.level'], ChoiceType::class, [
                'choices' => LogLevel::readables(),
            ])
            ->add('log.created', 'doctrine_orm_date_range', ['label' => 'Log.field.created'], DateRangePickerType::class)
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('log.message', null, ['label' => 'Log.field.message', 'template' => '@SonataExt/log/list/message.html.twig'])
            ->add('log.user', null, [
                'label' => 'Log.field.user',
                'template' => '@SonataExt/log/list/user.html.twig',
            ])
            ->add('log.created', null, ['label' => 'Log.field.created'])
            ->add('_action', null, ['actions' => [
                'show' => ['template' => '@SonataExt/log/part/log_reference_show.html.twig'],
            ]]);
    }
}

<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Admin;

use MKebza\SonataExt\Entity\Log;
use MKebza\SonataExt\Enum\LogLevel;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DateRangePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LogAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'log';
    protected $baseRouteName = 'admin_log';
    protected $translationDomain = 'admin';

    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'created',
    ];

    protected $userDetailRouteName;

    /**
     * @param mixed $userDetailRouteName
     */
    public function setUserDetailRouteName(string $userDetailRouteName): void
    {
        $this->userDetailRouteName = $userDetailRouteName;
    }

    public function getUserDetailRouteName(): ?string
    {
        return $this->userDetailRouteName;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'export']);
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $channels = $this->getConfigurationPool()
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Log::class)
            ->getChannels();

        $filter
            ->add('message', null, [
                'label' => 'Log.field.message',
                'show_filter' => true,
            ])
            ->add('channel', 'doctrine_orm_choice', ['label' => 'Log.field.channel'], ChoiceType::class, [
                'choices' => array_combine($channels, $channels),
            ])
            ->add('user', null, [
                'label' => 'Log.field.user',
            ])
            ->add('level', 'doctrine_orm_choice', ['label' => 'Log.field.level'], ChoiceType::class, [
                'choices' => LogLevel::readables(),
            ])
            ->add('created', 'doctrine_orm_date_range', ['label' => 'Log.field.created'], DateRangePickerType::class)
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('channel', null, ['label' => 'Log.field.channel'])
            ->add('message', null, ['label' => 'Log.field.message'])
            ->add('level', null, [
                'label' => 'Log.field.level',
                'template' => '@SonataExt/log/list/level.html.twig',
            ])
            ->add('user', null, [
                'label' => 'Log.field.user',
                'template' => '@SonataExt/log/list/user.html.twig',
            ])
            ->add('created', null, ['label' => 'Log.field.created']);
    }
}

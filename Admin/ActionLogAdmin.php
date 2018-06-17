<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 10/06/2018
 * Time: 12:28
 */

namespace MKebza\SonataExt\Admin;


use MKebza\SonataExt\Entity\ActionLog;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DateRangePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ActionLogAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'action-log';
    protected $baseRouteName = 'be_action_log';

    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    ];

    protected $userDetailRouteName = null;

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
        $filter
            ->add('message', null, ['show_filter' => true])
            ->add('user')
            ->add('level', 'doctrine_orm_choice', [], ChoiceType::class,
                ['choices' => array_flip(ActionLog::levels())]
            )
            ->add('createdAt', 'doctrine_orm_date_range', [], DateRangePickerType::class)
        ;
    }


    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('message')
            ->add('level', null, ['template' => '@SonataExt/action_log/_list_level.html.twig'])
            ->add('user', null, ['template' => '@SonataExt/action_log/_list_user.html.twig'])
            ->add('createdAt');
    }
}
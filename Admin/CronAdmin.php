<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 17/06/2018
 * Time: 11:14
 */

namespace MKebza\SonataExt\Admin;

use JMose\CommandSchedulerBundle\Form\Type\CommandChoiceType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DateRangePickerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CronAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'cron';
    protected $baseRouteName = 'be_cron';

    protected function configureRoutes(RouteCollection $collection)
    {
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
//        $filter
//            ->add('message', null, ['show_filter' => true])
//            ->add('user')
//
//            ->add('createdAt', 'doctrine_orm_date_range', [], DateRangePickerType::class)
//        ;
    }

    protected function configureFormFields(FormMapper $form)
    {

        $form
            ->add('name')
            ->add('command', CommandChoiceType::class)
            ->add('arguments', TextType::class)
            ->add('cronExpression', TextType::class)
            ->add('logFile', TextType::class)
            ->add('priority', IntegerType::class, ['empty_data' => 100,])
            ->add('executeImmediately', CheckboxType::class)
            ->add('disabled', CheckboxType::class)
        ;
    }


    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('name')
            ->add('command')
            ->add('cronExpression')
            ->add('lastExecution')
            ->add('lastReturnCode', null, ['label' => 'LRC'])
            ->add('locked', 'boolean', ['editable' => true, 'inverse' => true])
            ->add('disabled', 'boolean', ['editable' => true, 'inverse' => true])
            ->add('priority')
            ->add('_action', null, ['actions' => [
                'edit' => [],
                'delete' => [],
            ]])
        ;
    }
}
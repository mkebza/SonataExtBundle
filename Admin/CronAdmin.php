<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Admin;

use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;
use JMose\CommandSchedulerBundle\Form\Type\CommandChoiceType;
use MKebza\SonataExt\Form\Type\TemplateType;
use MKebza\SonataExt\Service\Logger\AdminLoggerTab;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Process\Process;

class CronAdmin extends AbstractAdmin
{
    use AdminLoggerTab;

    protected $baseRoutePattern = 'cron';
    protected $baseRouteName = 'admin_cron';

    protected function configureRoutes(RouteCollection $collection)
    {
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('name')
            ->add('lastReturnCode')
            ->add('locked')
            ->add('disabled')
        ;
    }

    protected function configureFormFields(FormMapper $form)
    {
        /** @var ScheduledCommand $command */
        $command = $this->getSubject();

        $form
            ->tab('General')
                ->with(null)
                    ->add('name')
                    ->add('command', CommandChoiceType::class)
                    ->add('arguments', TextType::class)
                    ->add('cronExpression', TextType::class)
                    ->add('logFile', TextType::class)
                    ->add('priority', IntegerType::class, ['empty_data' => 100])
                    ->add('executeImmediately', CheckboxType::class)
                    ->add('disabled', CheckboxType::class)
                ->end()
            ->end()
            ->tab('Execution log')
                ->with(sprintf("Log file '%s' (last 200 lines)", $command->getLogFile()))
                    ->add('_execution_log', TemplateType::class, [
                        'template' => '@SonataExt/cron/edit/_tab_execution_log.html.twig',
                        'vars' => [
                            'fileName' => $command->getLogFile(),
                            'content' => $this->getLogFileContent($command->getLogFile()),
                        ],
                    ])
                ->end()
            ->end()
        ;

        $this->addActionLogTab($form);
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

    protected function getLogFileContent($fileName): ?string
    {
        $fileName = $this->getConfigurationPool()->getContainer()->getParameter('kernel.logs_dir').'/'.basename($fileName);

        if (!file_exists($fileName)) {
            return "--- Log file doesn't exists ---";
        }

        if (!is_readable($fileName)) {
            return "--- Log file isn't readable ---";
        }

        $tail = new Process(['tail', '-n 200', $fileName]);
        $tail->run();

        return $tail->getOutput();
    }
}

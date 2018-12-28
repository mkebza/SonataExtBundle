<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;
use MKebza\SonataExt\Dashboard\Block\Type\AppInfoBlock;
use MKebza\SonataExt\Dashboard\Block\Type\CurrentUserInfoBlock;
use MKebza\SonataExt\Dashboard\Block\Type\LogBlock;
use MKebza\SonataExt\Dashboard\Block\Type\SeparatorBlock;
use MKebza\SonataExt\Dashboard\Block\Type\StatsNumberBlock;
use MKebza\SonataExt\Entity\Log;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class AdminDashboard implements DashboardInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * AdminDashboard constructor.
     *
     * @param RouterInterface        $router
     * @param EntityManagerInterface $em
     * @param TranslatorInterface    $translator
     */
    public function __construct(RouterInterface $router, EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->em = $em;
        $this->translator = $translator;
    }

    public function build(DashboardBuilderInterface $builder): void
    {
        $builder
            ->add('current_user_info', CurrentUserInfoBlock::class)

            ->add('user_count', StatsNumberBlock::class, [
                'number' => $this->getTotalUsers(),
                'label' => $this->translator->trans('block.StatsNumber.users', [], 'admin'),
                'icon' => 'fa fa-users',
                'target' => $this->router->generate('admin_user_list'),
            ])
            ->add('log_event_count_count', StatsNumberBlock::class, [
                'number' => $this->getTotalLogEvents(),
                'label' => $this->translator->trans('block.StatsNumber.logEvents', [], 'admin'),
                'icon' => 'fa fa-bars',
                'target' => $this->router->generate('admin_log_list'),
            ])

            ->add('locked_crons_count', StatsNumberBlock::class, [
                'number' => $this->getTotalLockedCrons(),
                'label' => $this->translator->trans('block.StatsNumber.lockedCrons', [], 'admin'),
                'icon' => 'fa fa-clock-o',
                'color' => 0 === $this->getTotalLockedCrons() ? 'green' : 'red',
                'target' => $this->router->generate('admin_cron_list'),
            ])

            ->add('_separator_1', SeparatorBlock::class)

            ->add('app_info', AppInfoBlock::class)
            ->add('log', LogBlock::class)
        ;
    }

    protected function getTotalUsers(): int
    {
        return $this->em
            ->createQueryBuilder()
            ->select('COUNT(user)')
            ->from(User::class, 'user')
            ->getQuery()
            ->getSingleScalarResult();
    }

    protected function getTotalLogEvents(): int
    {
        return $this->em
            ->createQueryBuilder()
            ->select('COUNT(entry)')
            ->from(Log::class, 'entry')
            ->getQuery()
            ->getSingleScalarResult();
    }

    protected function getTotalLockedCrons(): int
    {
        return $this->em
            ->createQueryBuilder()
            ->select('COUNT(entry)')
            ->from(ScheduledCommand::class, 'entry')
            ->where('entry.locked = true')
            ->getQuery()
            ->getSingleScalarResult();
    }
}

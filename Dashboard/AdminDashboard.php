<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 16:38
 */

namespace MKebza\SonataExt\Dashboard;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;
use MKebza\SonataExt\Dashboard\Block\StatsNumberProvider\UserCountProvider;
use MKebza\SonataExt\Dashboard\Block\Type\ActionLogBlock;
use MKebza\SonataExt\Dashboard\Block\Type\AppInfoBlock;
use MKebza\SonataExt\Dashboard\Block\Type\CurrentUserInfoBlock;
use MKebza\SonataExt\Dashboard\Block\Type\SeparatorBlock;
use MKebza\SonataExt\Dashboard\Block\Type\StatsNumberBlock;
use MKebza\SonataExt\Entity\ActionLog;
use Symfony\Component\Routing\RouterInterface;

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
     * AdminDashboard constructor.
     * @param RouterInterface $router
     * @param EntityManagerInterface $em
     */
    public function __construct(RouterInterface $router, EntityManagerInterface $em)
    {
        $this->router = $router;
        $this->em = $em;
    }

    public function build(DashboardBuilderInterface $builder): void
    {
        $builder
            ->add('current_user_info', CurrentUserInfoBlock::class)

            ->add('user_count', StatsNumberBlock::class, [
                'number' => $this->getTotalUsers(),
                'label' => 'Total users',
                'icon' => 'fa fa-users',
                'target' => $this->router->generate('admin_user_list')
            ])
            ->add('log_event_count_count', StatsNumberBlock::class, [
                'number' => $this->getTotalLogEvents(),
                'label' => 'Total log events',
                'icon' => 'fa fa-bars',
                'target' => $this->router->generate('admin_action_log_list')
            ])

            ->add('locked_crons_count', StatsNumberBlock::class, [
                'number' => $this->getTotalLockedCrons(),
                'label' => 'Locked crons',
                'icon' => 'fa fa-clock-o',
                'color' => $this->getTotalLockedCrons() == 0 ? 'green' : 'red',
                'target' => $this->router->generate('admin_cron_list')
            ])

            ->add('_separator_1', SeparatorBlock::class)


            ->add('app_info', AppInfoBlock::class)
            ->add('action_log', ActionLogBlock::class)
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
            ->from(ActionLog::class, 'entry')
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
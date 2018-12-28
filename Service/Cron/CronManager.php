<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Service\Cron;

use Cocur\Slugify\SlugifyInterface;
use Cron\CronExpression;
use Doctrine\ORM\EntityManagerInterface;
use JMose\CommandSchedulerBundle\Entity\Repository\ScheduledCommandRepository;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;

class CronManager
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var ScheduledCommandRepository */
    private $repository;

    /**
     * @var SlugifyInterface
     */
    private $slugify;

    /**
     * CronManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, SlugifyInterface $slugger)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(ScheduledCommand::class);
        $this->slugify = $slugger;
    }

    /**
     * Creates new cron record.
     *
     * @param string      $name
     * @param string      $expression
     * @param string      $commnad
     * @param bool        $immidiate
     * @param null|string $arguments
     * @param int         $priority
     *
     * @return CronManager
     */
    public function add(string $name, string $expression, string $commnad, bool $immidiate = false, string $arguments = null, int $priority = 100): self
    {
        if (!$this->has($name)) {
            // Validate
            CronExpression::factory($expression);

            $command = new ScheduledCommand();
            $command
                ->setName($name)
                ->setCronExpression($expression)
                ->setCommand($commnad)
                ->setArguments($arguments)
                ->setPriority($priority)
                ->setExecuteImmediately($immidiate)
                ->setDisabled(false)
                ->setLogFile(sprintf('cron.%s.log', $this->slugify->slugify($name)))
            ;

            $this->em->persist($command);
            $this->em->flush();
        }

        return $this;
    }

    public function delete(string $name): self
    {
        $command = $this->get($name);
        if (null !== $command) {
            $this->em->remove($command);
            $this->em->flush();
        }

        return $this;
    }

    public function disable(string $name): self
    {
        $command = $this->get($name);
        if (null !== $command) {
            $command->setDisabled(true);
            $this->em->persist($command);
            $this->em->flush();
        }

        return $this;
    }

    public function enable(string $name): self
    {
        $command = $this->get($name);
        if (null !== $command) {
            $command->setDisabled(false);
            $this->em->persist($command);
            $this->em->flush();
        }

        return $this;
    }

    public function has(string $name): bool
    {
        return null !== $this->get($name);
    }

    public function get(string $name): ?ScheduledCommand
    {
        return $this->repository->findOneBy(['name' => $name]);
    }
}

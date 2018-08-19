<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\EventListener;

use Carbon\Carbon;
use MKebza\SonataExt\Utils\CommandInfoInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Stopwatch\Stopwatch;

class CommandInfoSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    /**
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     * CommandInfoSubscriber constructor.
     *
     * @param Stopwatch $stopwatch
     */
    public function __construct(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }

    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => 'onCommand',
            ConsoleEvents::TERMINATE => 'onTerminate',
        ];
    }

    public function onCommand(ConsoleCommandEvent $event): void
    {
        if (!$event->getCommand() instanceof CommandInfoInterface) {
            return;
        }

        $this->stopwatch->start($event->getCommand()->getName());
        $event->getOutput()->writeln(sprintf(
            '// Command %s started at %s', $event->getCommand()->getName(), Carbon::now()->toDateTimeString()
        ));
    }

    public function onTerminate(ConsoleTerminateEvent $event): void
    {
        if (!$event->getCommand() instanceof CommandInfoInterface) {
            return;
        }

        $timer = $this->stopwatch->stop($event->getCommand()->getName());
        $event->getOutput()->writeln(sprintf(
            '// Command finished in %.2fs with code %s', $timer->getDuration() / 100, $event->getExitCode()));
    }
}

<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\EventListener\Workflow;

use MKebza\SonataExt\Service\Workflow\WorkflowLoggableInterface;
use MKebza\SonataExt\Service\Workflow\WorkflowTransitionInfo;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class WorkflowLoggableSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * WorkflowLoggableSubscriber constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.completed' => 'logTransition',
        ];
    }

    public function logTransition(Event $event): void
    {
        if ($event->getSubject() instanceof WorkflowLoggableInterface) {
            /** @var WorkflowTransitionInfo $info */
            $info = $event->getSubject()->getWorkflowTransitionInfo($event);
            $this->logger->info(
                $info->getMessage(),
                ['references' => $info->getReferences()]
            );
        }
    }
}

<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Service\Workflow;

use MKebza\SonataExt\ORM\Logger\LoggableInterface;
use Symfony\Component\Workflow\Event\Event;

trait WorkflowLoggable
{
    /**
     * Gets reference object to which log entry should be assigned.
     *
     * @param Event $event
     *
     * @return array
     */
    public function getWorkflowTransitionReferenceObjects(Event $event): array
    {
        $objects = [];
        if ($event->getSubject() instanceof LoggableInterface) {
            $objects[] = $event->getSubject();
        }

        return $objects;
    }

    /**
     * Gets information about log transitiont with formatted message etc.
     *
     * @param Event $event
     *
     * @return WorkflowTransitionInfo
     */
    public function getWorkflowTransitionInfo(Event $event): WorkflowTransitionInfo
    {
        return new WorkflowTransitionInfo(
            sprintf(
                "Changed state of '%s' to '%s' in '%s' workflow.",
                method_exists($event->getSubject(), '__toString') ? (string) $event->getSubject() : get_class($event->getSubject()),
                $this->workflowHumanizeKey($event->getTransition()->getTos()[0]),
                $this->workflowHumanizeKey($event->getWorkflowName())
            ),
            $this->getWorkflowTransitionReferenceObjects($event)
        );
    }

    protected function workflowHumanizeKey(string $key): string
    {
        return ucfirst(str_replace('_', ' ', $key));
    }
}

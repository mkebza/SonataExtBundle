<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\EventListener\Notificator;

use MKebza\Notificator\Event\PostNotificationHandleEvent;
use MKebza\Notificator\NotifiableInterface;
use MKebza\Notificator\Notification;
use MKebza\Notificator\NotificationInterface;
use MKebza\SonataExt\ORM\Logger\LoggableInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NotificatorLoggerSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * NotificatorLoggerSubscriber constructor.
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
            PostNotificationHandleEvent::class => 'logNotification',
        ];
    }

    public function logNotification(PostNotificationHandleEvent $event): void
    {
        $this->logger->info(
            sprintf(
                "Send notification '%s' to '%s' via '%s' with target '%s'",
                $this->getNotificationName($event->getNotificationHandler()),
                method_exists($event->getTarget(), '__toString') ? (string) $event->getTarget() : 'General target',
                $event->getNotification()->getChannel(),
                $event->getNotification()->getTarget()
            ),
            ['references' => $this->getReferences($event)]
        );
    }

    protected function getReferences(PostNotificationHandleEvent $event): array
    {
        $references = [];
        if ($event->getTarget() instanceof LoggableInterface) {
            $references[] = $event->getTarget();
        }

        foreach ($event->getOptions() as $key => $value) {
            if (is_object($value) && $value instanceof LoggableInterface) {
                $references[] = $value;
            }
        }

        return $references;
    }

    protected function getNotificationName(NotificationInterface $notification): string
    {
        $name = (new \ReflectionClass($notification))->getShortName();
        $name = preg_split('/(^[^A-Z]+|[A-Z][^A-Z]+)/', $name, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        if ('Notification' === $name[count($name) - 1]) {
            array_pop($name);
        }

        $name = ucfirst(strtolower(implode(' ', $name)));

        return $name;
    }
}

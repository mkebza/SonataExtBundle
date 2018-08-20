<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\EventListener;

use MKebza\SonataExt\Event\Security\ResetPasswordRequestedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LoggerSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AppLoggerSubscriber constructor.
     *
     * @param LoggerInterface $appLogger
     */
    public function __construct(LoggerInterface $appLogger)
    {
        $this->logger = $appLogger;
    }

    public static function getSubscribedEvents()
    {
        return [
            ResetPasswordRequestedEvent::class => 'resetPasswordRequested',
        ];
    }

    public function resetPasswordRequested(ResetPasswordRequestedEvent $event)
    {
        $this->logger->info('User logged', ['references' => $event->getUser()]);
    }
}

<?php

declare(strict_types=1);


namespace MKebza\SonataExt\EventListener;


use MKebza\SonataExt\Event\Security\ResetPasswordRequestedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AppLoggerSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    protected $appLogger;

    /**
     * AppLoggerSubscriber constructor.
     * @param LoggerInterface $appLogger
     */
    public function __construct(LoggerInterface $appLogger)
    {
        $this->appLogger = $appLogger;
    }

    public static function getSubscribedEvents()
    {
        return [
            ResetPasswordRequestedEvent::class => 'resetPasswordRequested'
        ];
    }

    public function resetPasswordRequested(ResetPasswordRequestedEvent $event)
    {
        dump("AAA"); exit;
        $this->appLogger->info('User logged', ['references' => $event->getUser()]);
    }

}
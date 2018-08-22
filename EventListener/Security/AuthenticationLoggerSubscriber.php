<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\EventListener\Security;

use Doctrine\ORM\EntityManagerInterface;
use MKebza\SonataExt\Entity\UserLoginAttempt;
use MKebza\SonataExt\Enum\LoginAttemptResult;
use MKebza\SonataExt\ORM\SonataExtUserInterface;
use MKebza\SonataExt\Repository\UserLoginAttemptRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class AuthenticationLoggerSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserLoginAttemptRepository
     */
    private $attemptRepository;

    /**
     * @var string
     */
    private $userEntityName;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AuthenticationLoggerSubscriber constructor.
     *
     * @param UserLoginAttemptRepository $attemptRepository
     * @param string                     $userEntityName
     * @param EntityManagerInterface     $em
     * @param RequestStack               $requestStack
     * @param LoggerInterface            $logger
     */
    public function __construct(
        UserLoginAttemptRepository $attemptRepository,
        string $userEntityName,
        EntityManagerInterface $em,
        RequestStack $requestStack,
        LoggerInterface $appLogger
    ) {
        $this->attemptRepository = $attemptRepository;
        $this->userEntityName = $userEntityName;
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->logger = $appLogger;
    }

    public static function getSubscribedEvents()
    {
        return [
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onFailure',
            SecurityEvents::INTERACTIVE_LOGIN => 'onLogin',
        ];
    }

    public function onLogin(InteractiveLoginEvent $event): void
    {
        if (!$event->getAuthenticationToken() instanceof UsernamePasswordToken) {
            return;
        }

        $user = $event->getAuthenticationToken()->getUser();
        if (!$user instanceof SonataExtUserInterface) {
            return;
        }

        $this->logger->info(sprintf('User %s logged in', $user), ['references' => $user]);
        $this->logAttempt($user, LoginAttemptResult::SUCCESS());
    }

    public function onFailure(AuthenticationFailureEvent $event): void
    {
        $userObject = $this->em
            ->getRepository($this->userEntityName)
            ->findOneBy(['email' => $event->getAuthenticationToken()->getUser()]);

        if (null === $userObject || !$userObject instanceof SonataExtUserInterface) {
            return;
        }

        $this->logger->info(sprintf('Failed attempt to log as %s', $userObject), ['references' => $userObject]);
        $this->logAttempt($userObject, LoginAttemptResult::FAILURE());
    }

    protected function logAttempt(SonataExtUserInterface $user, LoginAttemptResult $result): void
    {
        $attempt = new UserLoginAttempt(
            $user,
            $result,
            $this->requestStack->getMasterRequest()->headers->get('User-Agent'),
            $this->requestStack->getMasterRequest()->getClientIp()
        );

        $this->em->persist($attempt);
        $this->em->flush();
    }
}

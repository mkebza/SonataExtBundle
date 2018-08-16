<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 11/08/2018
 * Time: 15:03
 */

namespace MKebza\SonataExt\Security;


use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use MKebza\Notificator\NotificatorInterface;
use MKebza\SonataExt\Entity\User;
use MKebza\SonataExt\Event\Security\UserPasswordResetRequestedEvent;
use MKebza\SonataExt\Notification\Security\ResetPasswordRequestNotification;
use MKebza\SonataExt\Service\AppMailer;
use MKebza\SonataExt\Utils\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserResetPasswordRequest
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var NotificatorInterface
     */
    private $notificator;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * UserResetPasswordRequest constructor.
     * @param EntityManagerInterface $em
     * @param NotificatorInterface $notificator
     * @param EventDispatcherInterface $dispatcher
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function __construct(
        EntityManagerInterface $em,
        NotificatorInterface $notificator,
        EventDispatcherInterface $dispatcher,
        TokenGeneratorInterface $tokenGenerator
    ) {
        $this->em = $em;
        $this->notificator = $notificator;
        $this->dispatcher = $dispatcher;
        $this->tokenGenerator = $tokenGenerator;
    }


    public function request(User $user, \DateInterval $expiration)
    {
        $this->em->beginTransaction();
        try {
            $requestCreated = Carbon::now();
            $token = $this->tokenGenerator->generate(self::TOKEN_LENGTH, $requestCreated);
            $requestValid = (clone $requestCreated)->add($expiration);

            $user->setPassworResetRequest($token);

            $this->notificator->send($user, ResetPasswordRequestNotification::class, [
                'user' => $user,
                'requestValid' => $requestValid
            ]);

            $this->dispatcher->dispatch(
                UserPasswordResetRequestedEvent::class,
                new UserPasswordResetRequestedEvent($user, $token, $requestValid)
            );

            $this->em->persist($user);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }

    protected const TOKEN_LENGTH = 20;
}
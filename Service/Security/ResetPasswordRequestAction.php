<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 11/08/2018
 * Time: 15:03
 */

namespace MKebza\SonataExt\Service\Security;


use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use MKebza\Notificator\NotificatorInterface;
use MKebza\SonataExt\Entity\PasswordResetRequest;
use MKebza\SonataExt\Entity\ResetPasswordRequest;
use MKebza\SonataExt\Entity\User;
use MKebza\SonataExt\Event\Security\ResetPasswordRequestedEvent;
use MKebza\SonataExt\Event\Security\UserPasswordResetRequestedEvent;
use MKebza\SonataExt\Notification\Security\ResetPasswordRequestNotification;
use MKebza\SonataExt\ORM\SonataExtUserInterface;
use MKebza\SonataExt\Service\AppMailer;
use MKebza\SonataExt\Utils\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ResetPasswordRequestAction
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
     * @var RequestStack
     */
    private $requestStack;

    /**
     * UserResetPasswordRequest constructor.
     *
     * @param EntityManagerInterface $em
     * @param NotificatorInterface $notificator
     * @param EventDispatcherInterface $dispatcher
     * @param TokenGeneratorInterface $tokenGenerator
     * @param RequestStack $requestStack
     */
    public function __construct(
        EntityManagerInterface $em,
        NotificatorInterface $notificator,
        EventDispatcherInterface $dispatcher,
        TokenGeneratorInterface $tokenGenerator,
        RequestStack $requestStack
    ) {
        $this->em = $em;
        $this->notificator = $notificator;
        $this->dispatcher = $dispatcher;
        $this->tokenGenerator = $tokenGenerator;
        $this->requestStack = $requestStack;
    }

    /**
     * Create request for user so he can change password
     *
     * @param SonataExtUserInterface $user
     * @param \DateInterval $expireIn
     * @throws \Exception
     */
    public function create(SonataExtUserInterface $user, \DateInterval $expireIn)
    {
        $this->em->beginTransaction();
        try {
            $request = $this->requestFactory($user, $expireIn);

            $this->notificator->send($user, ResetPasswordRequestNotification::class, [
                'user' => $user,
                'request' => $request
            ]);

            $this->dispatcher->dispatch(
                ResetPasswordRequestedEvent::class,
                new ResetPasswordRequestedEvent($user, $request)
            );

            $this->em->persist($request);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }

    /**
     * Create request entity for resetting password
     *
     * @param SonataExtUserInterface $user
     * @param \DateInterval $expireIn
     * @return ResetPasswordRequest
     */
    protected function requestFactory(SonataExtUserInterface $user, \DateInterval $expireIn): ResetPasswordRequest{

        return new ResetPasswordRequest(
            $user,
            $this->tokenGenerator->generate(self::TOKEN_LENGTH),
            Carbon::now()->add($expireIn),
            $this->requestStack->getMasterRequest()->headers->get('User-Agent', 'Unknown'),
            $this->requestStack->getMasterRequest()->getClientIp()
        );
    }

    protected const TOKEN_LENGTH = 20;
}
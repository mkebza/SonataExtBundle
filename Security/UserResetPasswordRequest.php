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
use MKebza\SonataExt\Entity\User;
use MKebza\SonataExt\Event\Security\UserPasswordResetRequestedEvent;
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
     * @var AppMailer
     */
    private $mailer;

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
     * @param AppMailer $mailer
     * @param EventDispatcherInterface $dispatcher
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function __construct(
        EntityManagerInterface $em,
        AppMailer $mailer,
        EventDispatcherInterface $dispatcher,
        TokenGeneratorInterface $tokenGenerator
    ) {
        $this->em = $em;
        $this->mailer = $mailer;
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

//            $this->mailer->createAndSend(
//                $user->getEmail(),
//                'security.resetPasswordRequest.subject',
//                '@SonataExt/email/security/reset_password_request.html.twig',
//                ['token ' => $token, 'user' => $user, 'validity' => $requestValid]
//            );

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
<?php

declare(strict_types=1);


namespace MKebza\SonataExt\Service\Security;

use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use MKebza\SonataExt\Entity\ResetPasswordRequest;
use MKebza\SonataExt\Event\Security\UserPasswordResettedEvent;
use MKebza\SonataExt\ORM\SonataExtUserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordAction
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * ResetPasswordAction constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher
    ) {
        $this->encoder = $encoder;
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }


    public function getToken(string $token, \DateTime $now = null): ?ResetPasswordRequest
    {
        $token = $this->em->getRepository(ResetPasswordRequest::class)->findOneBy(['token' => $token]);

        if (null === $token) {
            return null;
        }
        $now = $now ?? Carbon::now();
        if ($now >= $token->getExpire()  || $token->isUsed()) {
            return null;
        }

        return $token;
    }

    public function reset(ResetPasswordRequest $request, string $newPassword): void
    {
        $this->em->beginTransaction();
        try {
            $user = $request->getUser();

            $user->setPassword($this->encoder->encodePassword($user, $newPassword));
            $request->use();

            $this->dispatcher->dispatch(
                UserPasswordResettedEvent::class,
                new UserPasswordResettedEvent($token)
            );

            $this->em->persist($user);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }
}
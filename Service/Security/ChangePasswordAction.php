<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Service\Security;

use Doctrine\ORM\EntityManagerInterface;
use MKebza\SonataExt\Entity\User;
use MKebza\SonataExt\Event\Security\UserPasswordChangedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChangePasswordAction
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UserChangePassword constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     * @param EventDispatcherInterface     $dispatcher
     * @param EntityManagerInterface       $em
     */
    public function __construct(
        UserPasswordEncoderInterface $encoder,
        EventDispatcherInterface $dispatcher,
        EntityManagerInterface $em
    ) {
        $this->encoder = $encoder;
        $this->dispatcher = $dispatcher;
        $this->em = $em;
    }

    /**
     * Change user password and trigger event.
     *
     * @param User $user
     * @param $newPassword
     */
    public function change(User $user, $newPassword)
    {
        $this->em->beginTransaction();

        try {
            $user->setPassword($this->encoder->encodePassword($user, $newPassword));
            $this->em->persist($user);
            $this->em->flush();

            $this->dispatcher->dispatch(UserPasswordChangedEvent::class, new UserPasswordChangedEvent($user, $newPassword));

            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();

            throw $e;
        }
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 11/08/2018
 * Time: 15:03
 */

namespace MKebza\SonataExt\Security;


use Doctrine\ORM\EntityManagerInterface;
use MKebza\SonataExt\Entity\User;
use MKebza\SonataExt\Service\AppMailer;
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
     * UserResetPasswordRequest constructor.
     * @param EntityManagerInterface $em
     * @param AppMailer $mailer
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EntityManagerInterface $em, AppMailer $mailer, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->dispatcher = $dispatcher;
    }


    public function request(User $user, \DateInterval $expiration = null)
    {
        $this->em->beginTransaction();
        try {


            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }
}
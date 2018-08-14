<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 11/08/2018
 * Time: 20:48
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Event\Security;

use MKebza\SonataExt\Entity\User;
use Symfony\Component\EventDispatcher\Event;

final class UserPasswordResetRequestedEvent extends Event
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $token;

    /**
     * @var \DateTime
     */
    private $validity;

    /**
     * UserPasswordResetRequestedEvent constructor.
     * @param User $user
     * @param string $token
     */
    public function __construct(User $user, string $token, \DateTime $validity)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * @return \DateTime
     */
    public function getValidity(): \DateTime
    {
        return $this->validity;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }



}
<?php

declare(strict_types=1);


namespace MKebza\SonataExt\Event\Security;


use MKebza\SonataExt\Entity\ResetPasswordRequest;
use MKebza\SonataExt\ORM\SonataExtUserInterface;

final class UserPasswordResettedEvent
{
    /**
     * @var ResetPasswordRequest
     */
    private $user;

    /**
     * UserPasswordResettedEvent constructor.
     * @param SonataExtUserInterface $user
     */
    public function __construct(ResetPasswordRequest $user)
    {
        $this->user = $user;
    }

    /**
     * @return ResetPasswordRequest
     */
    public function getUser(): ResetPasswordRequest
    {
        return $this->user;
    }
}
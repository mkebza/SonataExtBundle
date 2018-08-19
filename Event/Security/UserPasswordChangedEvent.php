<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Event\Security;

use MKebza\SonataExt\Entity\User;
use Symfony\Component\EventDispatcher\Event;

final class UserPasswordChangedEvent extends Event
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $newPassword;

    /**
     * UserPasswordChangedEvent constructor.
     *
     * @param User   $user
     * @param string $newPassword
     */
    public function __construct(User $user, string $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
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
    public function getNewPassword(): string
    {
        return $this->newPassword;
    }
}

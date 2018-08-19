<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Event\Security;

use MKebza\SonataExt\Entity\ResetPasswordRequest;
use MKebza\SonataExt\ORM\SonataExtUserInterface;
use Symfony\Component\EventDispatcher\Event;

final class UserPasswordResettedEvent extends Event
{
    /**
     * @var ResetPasswordRequest
     */
    private $user;

    /**
     * UserPasswordResettedEvent constructor.
     *
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

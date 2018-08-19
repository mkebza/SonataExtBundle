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

final class ResetPasswordRequestedEvent extends Event
{
    /**
     * @var SonataExtUserInterface
     */
    private $user;

    /**
     * @var ResetPasswordRequest
     */
    private $request;

    /**
     * UserPasswordResetRequestedEvent constructor.
     *
     * @param SonataExtUserInterface $user
     * @param PasswordResetRequest   $request
     */
    public function __construct(SonataExtUserInterface $user, ResetPasswordRequest $request)
    {
        $this->user = $user;
        $this->request = $request;
    }

    /**
     * @return SonataExtUserInterface
     */
    public function getUser(): SonataExtUserInterface
    {
        return $this->user;
    }

    /**
     * @return PasswordResetRequest
     */
    public function getRequest(): ResetPasswordRequest
    {
        return $this->request;
    }
}

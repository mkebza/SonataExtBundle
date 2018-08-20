<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Service\Logger;

use MKebza\SonataExt\ORM\Logger\LoggableUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider implements UserProviderInterface
{
    private $token;

    /**
     * TokenEntityHistoryProvider constructor.
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage;
    }

    public function getUser(): ?LoggableUserInterface
    {
        $token = $this->token->getToken();
        if (null === $token || null === $token->getUser()) {
            return null;
        }

        $user = $token->getUser();
        if ($user instanceof LoggableUserInterface && $user instanceof UserInterface) {
            return $user;
        }

        return null;
    }

    public function getName(): ?string
    {
        return $this->getUser() ? $this->getUser()->getLoggableName() : null;
    }
}

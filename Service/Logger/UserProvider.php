<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Service\Logger;

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

    public function getUser(): ?UserInterface
    {
        dd('AA');
        $token = $this->token->getToken();
        if (null === $token || null === $token->getUser()) {
            return null;
        }

        $user = $token->getUser();
        $refl = new \ReflectionClass($user);
        if ($refl->implementsInterface(ActionLogUserInterface::class)) {
            return $user;
        }

        return null;
    }

    public function getName(): ?string
    {
        return $this->getUser() ? $this->getUser()->getActionLogName() : null;
    }
}

<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 10/06/2018
 * Time: 11:53
 */

namespace MKebza\SonataExt\ActionLog;

use MKebza\SonataExt\ORM\ActionLog\ActionLoggableInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CurrentUserProvider implements CurrentUserProviderInterface
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
        $token = $this->token->getToken();
        if (null === $token->getUser()) {
            return null;

        }

        $user = $token->getUser();
        $refl = new \ReflectionClass($user);
        if ($refl->implementsInterface(ActionLogUserInterface::class)) {
            return $user;
        }

        return null;
    }

    public function getUsername(): ?string
    {
        return $this->getUser() ? $this->getUser()->getActionLogName() : null;
    }


}
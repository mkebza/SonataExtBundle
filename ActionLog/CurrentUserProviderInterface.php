<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 10/06/2018
 * Time: 11:51
 */

namespace MKebza\SonataExt\ActionLog;


use Symfony\Component\Security\Core\User\UserInterface;

interface CurrentUserProviderInterface
{
    public function getUsername(): ?string;
    public function getUser(): ?UserInterface;
}
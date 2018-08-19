<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\ActionLog;

use Symfony\Component\Security\Core\User\UserInterface;

interface CurrentUserProviderInterface
{
    public function getUsername(): ?string;

    public function getUser(): ?UserInterface;
}

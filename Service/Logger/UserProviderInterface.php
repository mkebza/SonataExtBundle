<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Service\Logger;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserProviderInterface
{
    public function getName(): ?string;

    public function getUser(): ?UserInterface;
}

<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Service\Logger;

use MKebza\SonataExt\ORM\Logger\LoggableUserInterface;

interface UserProviderInterface
{
    public function getName(): ?string;

    public function getUser(): ?LoggableUserInterface;
}

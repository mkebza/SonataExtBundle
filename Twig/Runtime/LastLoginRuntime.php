<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Twig\Runtime;

use MKebza\SonataExt\ORM\SonataExtUserInterface;
use MKebza\SonataExt\Repository\UserLoginAttemptRepository;
use Twig\Extension\RuntimeExtensionInterface;

class LastLoginRuntime implements RuntimeExtensionInterface
{
    /**
     * @var UserLoginAttemptRepository
     */
    private $repository;

    /**
     * LastLoginRuntime constructor.
     *
     * @param UserLoginAttemptRepository $repository
     */
    public function __construct(UserLoginAttemptRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getLastLogin(SonataExtUserInterface $user): ?\DateTime
    {
        $attempt = $this->repository->getLastLogin($user);

        return null === $attempt ?: $attempt->getCreated();
    }
}

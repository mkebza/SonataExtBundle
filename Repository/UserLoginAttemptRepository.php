<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use MKebza\SonataExt\Entity\UserLoginAttempt;
use MKebza\SonataExt\Enum\LoginAttemptResult;
use MKebza\SonataExt\ORM\SonataExtUserInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|Banner find($id, $lockMode = null, $lockVersion = null)
 * @method null|Banner findOneBy(array $criteria, array $orderBy = null)
 * @method Banner[]    findAll()
 * @method Banner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLoginAttemptRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserLoginAttempt::class);
    }

    public function getLastLogin(SonataExtUserInterface $user): ?UserLoginAttempt
    {
        return $this->findOneBy(
            ['user' => $user, 'result' => LoginAttemptResult::SUCCESS()],
            ['created' => 'DESC']
        );
    }
}

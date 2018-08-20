<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use MKebza\SonataExt\Entity\Log;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|Banner find($id, $lockMode = null, $lockVersion = null)
 * @method null|Banner findOneBy(array $criteria, array $orderBy = null)
 * @method Banner[]    findAll()
 * @method Banner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Log::class);
    }

    public function getChannels(): array
    {
        $records = $this->createQueryBuilder('e')
            ->select('e.channel')
            ->groupBy('e.channel')
            ->orderBy('e.channel', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map(function ($v) {
            return $v['channel'];
        }, $records);
    }
}

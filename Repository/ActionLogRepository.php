<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 21/06/2018
 * Time: 13:04
 */

namespace MKebza\SonataExt\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use MKebza\SonataExt\Entity\ActionLog;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Banner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Banner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Banner[]    findAll()
 * @method Banner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ActionLog::class);
    }
}

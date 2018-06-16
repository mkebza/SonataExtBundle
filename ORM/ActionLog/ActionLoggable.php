<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 10/06/2018
 * Time: 09:57
 */

namespace MKebza\SonataExt\ORM\ActionLog;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

use MKebza\SonataExt\Entity\ActionLog;

trait ActionLoggable
{
    /**
     * @var ArrayCollection
     */
    protected $loggedActions;


    public function logAction(ActionLog $object): self
    {
        $this->loggedActions->add($object);

        return $this;
    }

    /**
     * @return ActionLog[]
     */
    public function getLoggedActions(int $limit = null): Collection
    {
        $limit = ($limit === null ? $this->getLoggedActionsDefaultLimit() : $limit);

        return $this->loggedActions->matching(
            Criteria::create()
                ->setMaxResults($limit)
                ->orderBy(['createdAt' => 'DESC'])
        );
    }

    /**
     * @return int
     */
    protected function getLoggedActionsDefaultLimit(): int
    {
        // Cannot have constat so we use magic number
        return 50;
    }
}
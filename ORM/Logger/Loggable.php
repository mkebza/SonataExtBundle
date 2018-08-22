<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\ORM\Logger;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use MKebza\SonataExt\Entity\ActionLog;
use MKebza\SonataExt\Entity\Log;

trait Loggable
{
    /**
     * @var ArrayCollection
     */
    protected $log;

    public function log(Log $object): LoggableInterface
    {
        $this->log->add($object);

        return $this;
    }

    /**
     * @return ActionLog[]
     */
    public function getLog(int $limit = null): iterable
    {
        $criteria = Criteria::create()->orderBy(['created' => 'DESC']);

        if (null !== $limit) {
            $criteria->setMaxResults($limit);
        }

        return $this->log->matching($criteria);
    }
}

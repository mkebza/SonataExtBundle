<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\ORM\AppLog;

use Doctrine\Common\Collections\Collection;
use MKebza\SonataExt\Entity\AppLog;

interface LoggableInterface
{
    /**
     * On purpose omitted return type.
     *
     * @param ActionLog $object
     *
     * @return mixed
     */
    public function log(AppLog $event): LoggableInterface;

    public function getLog(): ?Collection;
}

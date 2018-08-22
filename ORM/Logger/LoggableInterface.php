<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\ORM\Logger;

use Doctrine\Common\Collections\Collection;
use MKebza\SonataExt\Entity\Log;

interface LoggableInterface
{
    /**
     * On purpose omitted return type.
     *
     * @param ActionLog $object
     *
     * @return mixed
     */
    public function log(Log $event): self;

    /**
     * @return null|Collection
     */
    public function getLog(): iterable;
}

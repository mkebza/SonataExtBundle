<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\ORM\Logger;

use Doctrine\Common\Collections\ArrayCollection;

trait Loggable
{
    /**
     * @var ArrayCollection
     */
    protected $log;

    abstract public function getLogEntityFQN(): string;
}

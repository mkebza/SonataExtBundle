<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\ORM;

use Doctrine\ORM\Mapping as ORM;

trait EntityPrioritizable
{
    /**
     * @var null|int
     * @ORM\Column(type="smallint", options={"unsigned": true}, nullable=true)
     */
    private $priority = 1000;

    /**
     * @return null|int
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param null|int $priority
     *
     * @return Prioritizable
     */
    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }
}

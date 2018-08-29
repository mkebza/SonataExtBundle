<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\ORM;

use Doctrine\ORM\Mapping as ORM;

trait EntityId
{
    /**
     * @var null|int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}

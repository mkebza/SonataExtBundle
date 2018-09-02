<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\ORM;

use Doctrine\ORM\Mapping as ORM;

trait EntityKey
{
    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=100, name="_key", unique=true, nullable=true)
     */
    protected $key;

    /**
     * @return null|string
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param null|string $key
     *
     * @return EntityKey
     */
    public function setKey(?string $key): self
    {
        $this->key = $key;

        return $this;
    }
}

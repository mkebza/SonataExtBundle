<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\ORM\Timestampable;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

trait Timestampable
{
    /**
     * @var null|Carbon
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var null|Carbon
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updated;

    /**
     * @return null|Carbon
     */
    public function getCreated(): ?Carbon
    {
        return $this->created;
    }

    /**
     * @param null|Carbon $created
     *
     * @return Timestampable
     */
    public function setCreated(?Carbon $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return null|Carbon
     */
    public function getUpdated(): ?Carbon
    {
        return $this->updated;
    }

    /**
     * @param null|Carbon $updated
     *
     * @return Timestampable
     */
    public function setUpdated(?Carbon $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}

<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\ORM;

use Doctrine\ORM\Mapping as ORM;

trait EntityActive
{
    /**
     * @var null|bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $active = false;

    /**
     * @return null|bool
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param null|bool $active
     */
    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function activate(): self
    {
        $this->setActive(true);

        return $this;
    }

    public function deactivate(): self
    {
        $this->setActive(false);

        return $this;
    }
}

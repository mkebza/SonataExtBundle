<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\ORM\Sluggable;

use Doctrine\ORM\Mapping as ORM;

trait Sluggable
{
    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $slug;

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param null|string $slug
     *
     * @return self
     */
    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlugSource(): ?string
    {
        return $this->getName();
    }

    public function shouldUpdateSlugOnUpdate(): bool
    {
        return true;
    }
}

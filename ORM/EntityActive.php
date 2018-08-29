<?php

declare(strict_types=1);


namespace MKebza\SonataExt\ORM;


use Doctrine\ORM\Mapping as ORM;

trait EntityActive
{
    /**
     * @var boolean|null
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $active = false;

    /**
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool|null $active
     */
    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
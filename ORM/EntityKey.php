<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 27/08/2018
 * Time: 14:58
 */

namespace MKebza\SonataExt\ORM;


use Doctrine\ORM\Mapping as ORM;

trait EntityKey
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", name="_key", unique=true, nullable=true)
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
     * @return EntityKey
     */
    public function setKey(?string $key): self
    {
        $this->key = $key;

        return $this;
    }
}
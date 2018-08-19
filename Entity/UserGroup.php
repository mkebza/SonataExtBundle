<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Entity;

use Doctrine\ORM\Mapping as ORM;
use MKebza\SonataExt\ORM\SonataExtUserGroupInterface;
use MKebza\SonataExt\ORM\Timestampable\Timestampable;

/**
 * @ORM\Table(name="user_group")
 * @ORM\MappedSuperclass()
 */
abstract class UserGroup implements SonataExtUserGroupInterface
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=100, name="internal_key", nullable=true)
     */
    protected $key;

    /**
     * @var array
     *
     * @ORM\Column(type="simple_array", nullable=false)
     */
    protected $roles;

    /**
     * Group constructor.
     *
     * @param string $name
     * @param array  $roles
     */
    public function __construct()
    {
        $this->roles = [];
    }

    public function __toString()
    {
        return sprintf('%s [#%s]', $this->getName(), ($this->getId() ? $this->getId() : '?'));
    }

    public function addRole($role): self
    {
        if (!$this->hasRole($role)) {
            $this->roles[] = strtoupper($role);
        }

        return $this;
    }

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
     * @return UserGroup
     */
    public function setKey(?string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function hasRole($role): bool
    {
        return in_array(strtoupper($role), $this->roles, true);
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function removeRole($role): self
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}

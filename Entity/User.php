<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MKebza\Notificator\NotifiableInterface;
use MKebza\SonataExt\ORM\Logger\Loggable;
use MKebza\SonataExt\ORM\Logger\LoggableInterface;
use MKebza\SonataExt\ORM\SonataExtUserInterface;
use MKebza\SonataExt\ORM\Timestampable\Timestampable;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\MappedSuperclass()
 */
abstract class User implements NotifiableInterface, UserInterface, \Serializable, LoggableInterface, EquatableInterface, SonataExtUserInterface
{
    use Loggable, Timestampable;

    /**
     * @var null|int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var null|string
     * @ORM\Column(type="string", length=100, unique=true)
     */
    protected $email;

    /**
     * @var null|string
     * @ORM\Column(type="string", length=64)
     */
    protected $password;

    /**
     * @var null|bool
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @ORM\ManyToMany(targetEntity="MKebza\SonataExt\ORM\SonataExtUserGroupInterface")
     * @ORM\JoinTable(name="user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->log = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s [#%s]', $this->getUsername(), ($this->getId() ? $this->getId() : '?'));
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param null|string $password
     *
     * @return User
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     *
     * @return User
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param null|bool $active
     *
     * @return User
     */
    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function getRoles()
    {
        $roles = [];
        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        // we need to make sure to have at least one role
        return array_unique($roles);
    }

    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
            $this->active,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            $this->active
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param mixed $groups
     *
     * @return User
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    public function getLoggableName(): string
    {
        return (string) $this;
    }

    public function isEqualTo(UserInterface $user)
    {
        return true;
    }

    public function getSalutation(): string
    {
        return (string) $this;
    }

    public function getNotificationChannels(): array
    {
        return [
            'email' => $this->getEmail(),
        ];
    }
}

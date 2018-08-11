<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 16/06/2018
 * Time: 12:03
 */
declare(strict_types=1);

namespace MKebza\SonataExt\Entity;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MKebza\EntityHistory\ORM\EntityHistory;
use MKebza\EntityHistory\ORM\EntityHistoryInterface;
use MKebza\EntityHistory\ORM\EntityHistoryUserInterface;
use MKebza\SonataExt\ActionLog\ActionLogUserInterface;
use MKebza\SonataExt\ORM\ActionLog\ActionLoggable;
use MKebza\SonataExt\ORM\ActionLog\ActionLoggableInterface;
use MKebza\SonataExt\ORM\Timestampable\Timestampable;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\MappedSuperclass()
 */
class User implements UserInterface, \Serializable, ActionLoggableInterface, ActionLogUserInterface, EquatableInterface
{
    use ActionLoggable, Timestampable;

    /**
     * @var integer|null
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=254, unique=true)
     */
    protected $email;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=64)
     */
    protected $password;

    /**
     * @var boolean|null
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UserGroup")
     * @ORM\JoinTable(name="user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @var string|null
     */
    protected $plainPassword;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->loggedActions = new ArrayCollection();
    }

    /**
     * @return int|null
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
     * @return User
     */
    public function setPassword(?string $password): User
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
     * @return User
     */
    public function setEmail(?string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool|null $active
     * @return User
     */
    public function setActive(?bool $active): User
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime|null $lastLogin
     * @return User
     */
    public function setLastLogin(?\DateTime $lastLogin): User
    {
        $this->lastLogin = $lastLogin;

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
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->active
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            $this->active
            ) = unserialize($serialized, array('allowed_classes' => false));
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

    public function __toString()
    {
        return sprintf('%s [#%s]', $this->getUsername(), ($this->getId() ? $this->getId() : '?'));
    }

    public function getActionLogName(): string
    {
        return (string)$this;
    }

    public function isEqualTo(UserInterface $user)
    {
        return true;
    }
}

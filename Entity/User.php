<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 16/06/2018
 * Time: 12:03
 */

namespace MKebza\SonataExt\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MKebza\EntityHistory\ORM\EntityHistory;
use MKebza\EntityHistory\ORM\EntityHistoryInterface;
use MKebza\EntityHistory\ORM\EntityHistoryUserInterface;
use MKebza\SonataExt\ActionLog\ActionLogUserInterface;
use MKebza\SonataExt\ORM\ActionLog\ActionLoggable;
use MKebza\SonataExt\ORM\ActionLog\ActionLoggableInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\MappedSuperclass()
 */
class User extends \FOS\UserBundle\Model\User implements ActionLoggableInterface, ActionLogUserInterface, EquatableInterface
{
    use ActionLoggable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UserGroup")
     * @ORM\JoinTable(name="user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
        $this->loggedActions = new ArrayCollection();
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

    public function setEmailCanonical($emailCanonical)
    {
        parent::setEmailCanonical($emailCanonical);

        $this->setUsername($emailCanonical);
        $this->setUsernameCanonical($emailCanonical);
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preSaveUpdateUsername()
    {
        $this->setUsername($this->getEmailCanonical());
        $this->setUsernameCanonical($this->getEmailCanonical());
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

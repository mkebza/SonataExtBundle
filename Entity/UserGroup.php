<?php
/*
 * (c) Marek Kebza <marek@kebza.cz>
 */

namespace MKebza\SonataExt\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Table(name="user_group")
 * @ORM\MappedSuperclass()
 *
 */
class UserGroup extends BaseGroup
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct($name = null, array $roles = [])
    {
        parent::__construct($name, $roles);
    }

    public function __toString()
    {
        return sprintf('%s [#%s]', $this->getName(), ($this->getId() ? $this->getId() : '?'));
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 10/08/2018
 * Time: 17:02
 */
namespace MKebza\SonataExt\ORM\Timestampable;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

trait Timestampable
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return TimestampableProperties
     */
    public function setCreated(\DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function updateCreated(): void
    {
        $this->setCreated(new \DateTime());
    }
}
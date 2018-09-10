<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MKebza\SonataExt\Enum\LogLevel;
use MKebza\SonataExt\ORM\EntityId;
use MKebza\SonataExt\ORM\SonataExtUserInterface;

/**
 * Class HistoryEntry.
 *
 * @ORM\Entity(
 *     repositoryClass="MKebza\SonataExt\Repository\LogRepository",
 *     readOnly=true
 * )
 * @ORM\Table(
 *     name="log",
 *     indexes={
 *          @ORM\Index(name="created", columns={"created"})
 *     }
 * )
 */
class Log
{
    use EntityId;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $channel;

    /**
     * @var string
     * @ORM\Column(type="string", length=500, nullable=false)
     */
    private $message;

    /**
     * @var LogLevel
     * @ORM\Column(type="log_level")
     */
    private $level;

    /**
     * @var null|string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var null|SonataExtUserInterface
     * @ORM\ManyToOne(targetEntity="MKebza\SonataExt\ORM\SonataExtUserInterface")
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     */
    private $user;

    /**
     * @var null|array
     * @ORM\Column(type="json", nullable=true)
     */
    private $extra;

    /**
     * @var null|\DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var ArrayCollection|LogReference[]
     * @ORM\OneToMany(targetEntity="MKebza\SonataExt\Entity\LogReference", mappedBy="log")
     */
    private $references;

    /**
     * AppLog constructor.
     *
     * @param string                      $message
     * @param string                      $level
     * @param null|string                 $name
     * @param null|SonataExtUserInterface $user
     * @param null|array                  $extra
     */
    public function __construct(
        string $channel,
        string $message,
        LogLevel $level,
        ?string $name,
        ?SonataExtUserInterface $user,
        ?array $extra
    ) {
        $this->channel = preg_replace('/^(app_)/', '', $channel);
        $this->message = $message;
        $this->level = $level;
        $this->name = $name;
        $this->user = $user;
        $this->extra = (empty($extra) ? null : $extra);

        $this->references = new ArrayCollection();

        $this->created = new \DateTime();
    }

    public function __toString()
    {
        return sprintf('[%s] - %s ', $this->getId(), $this->getMessage());
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getLevel(): LogLevel
    {
        return $this->level;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return null|SonataExtUserInterface
     */
    public function getUser(): ?SonataExtUserInterface
    {
        return $this->user;
    }

    /**
     * @return null|array
     */
    public function getExtra(): ?array
    {
        return $this->extra;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @return ArrayCollection|LogReference[]
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * @param ArrayCollection|LogReference[] $references
     */
    public function setReferences($references): void
    {
        $this->references = $references;
    }
}

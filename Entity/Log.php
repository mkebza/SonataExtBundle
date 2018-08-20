<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Entity;

use Doctrine\ORM\Mapping as ORM;
use MKebza\SonataExt\Enum\LogLevel;
use MKebza\SonataExt\ORM\SonataExtUserInterface;
use MKebza\SonataExt\ORM\Timestampable\Timestampable;

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
 *          @ORM\Index(name="created_at", columns={"created"})
 *     }
 * )
 */
class Log
{
    use Timestampable;

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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
        $this->channel = $channel;
        $this->message = $message;
        $this->level = $level;
        $this->name = $name;
        $this->user = $user;
        $this->extra = (empty($extra) ? null : $extra);
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
}

<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Entity;

use Doctrine\ORM\Mapping as ORM;
use MKebza\SonataExt\Enum\LogLevel;
use MKebza\SonataExt\ORM\DiscriminatorMapEntryInterface;
use MKebza\SonataExt\ORM\EntityId;
use MKebza\SonataExt\ORM\SonataExtUserInterface;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 * @ORM\Table(
 *     name="log_reference",
 *     indexes={
 *          @ORM\Index(name="refererence", columns={"reference_id", "model"})
 *     }
 * )
 */
class LogReference implements DiscriminatorMapEntryInterface
{
    use EntityId;

    /**
     * @var Log
     * @ORM\ManyToOne(targetEntity="MKebza\SonataExt\Entity\Log", inversedBy="references", fetch="EAGER", cascade={"persist"})
     */
    protected $log;

    protected $reference;

    /**
     * LogReference constructor.
     *
     * @param Log $log
     * @param $reference
     */
    public function __construct(Log $log, object $reference)
    {
        $this->log = $log;
        $this->reference = $reference;
    }

    public function getReference(): object
    {
        return $this->reference;
    }

    /**
     * @param null|object $reference
     *
     * @return Image
     */
    public function setReference(object $reference): self
    {
        return $this;
    }

    /**
     * @return Log
     */
    public function getLog(): Log
    {
        return $this->log;
    }

    public static function getDiscriminatorEntryName(): string
    {
        return 'log';
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->getLog()->getMessage();
    }

    /**
     * @return int
     */
    public function getLevel(): LogLevel
    {
        return $this->getLog()->getLevel();
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->getLog()->getName();
    }

    /**
     * @return null|SonataExtUserInterface
     */
    public function getUser(): ?SonataExtUserInterface
    {
        return $this->getLog()->getUser();
    }

    /**
     * @return null|array
     */
    public function getExtra(): ?array
    {
        return $this->getLog()->getExtra();
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->getLog()->getChannel();
    }

    public function getCreated(): \DateTime
    {
        return $this->getLog()->getCreated();
    }
}

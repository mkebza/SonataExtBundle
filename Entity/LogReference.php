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
use MKebza\SonataExt\ORM\DiscriminatorMapEntryInterface;
use MKebza\SonataExt\ORM\EntityId;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 */
class LogReference implements DiscriminatorMapEntryInterface
{
    use EntityId;

    /**
     * @var Log
     * @ORM\ManyToOne(targetEntity="MKebza\SonataExt\Entity\Log", inversedBy="")
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
}

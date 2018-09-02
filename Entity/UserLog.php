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

/**
 * @ORM\Entity()
 */
class UserLog extends LogReference
{
    /**
     * @ORM\ManyToOne(targetEntity="MKebza\SonataExt\ORM\SonataExtUserInterface")
     */
    protected $reference;

    public static function getDiscriminatorEntryName(): string
    {
        return 'user';
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference): void
    {
        $this->reference = $reference;
    }
}

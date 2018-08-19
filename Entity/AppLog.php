<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Entity;

use Doctrine\ORM\Mapping as ORM;
use MKebza\SonataExt\ActionLog\ActionLogUserInterface;
use MKebza\SonataExt\ORM\SonataExtUserInterface;
use MKebza\SonataExt\ORM\Timestampable\Timestampable;

/**
 * Class HistoryEntry.
 *
 * @ORM\Entity(
 *     repositoryClass="MKebza\SonataExt\Repository\AppLogRepository",
 *     readOnly=true
 * )
 * @ORM\Table(
 *     indexes={
 *          @ORM\Index(name="created_at", columns={"created_at"})
 *     }
 * )
 */
class AppLog
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
     * @ORM\Column(type="string", length=500, nullable=false)
     */
    private $message;

    /**
     * @var string
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $level;

    /**
     * @var null|string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var SonataExtUserInterface
     * @ORM\ManyToOne(targetEntity="MKebza\SonataExt\ORM\SonataExtUserInterface")
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     */
    private $user;


}

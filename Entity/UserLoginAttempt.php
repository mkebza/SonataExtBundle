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
use MKebza\SonataExt\Enum\LoginAttemptResult;
use MKebza\SonataExt\ORM\SonataExtUserInterface;
use MKebza\SonataExt\ORM\Timestampable\Timestampable;

/**
 * @ORM\Entity(
 *     readOnly=true,
 *     repositoryClass="MKebza\SonataExt\Repository\UserLoginAttemptRepository"
 * )
 * @ORM\Table(
 *     name="user_login_attempt",
 *     indexes={
 *          @ORM\Index(name="created", columns={"created"})
 *     }
 * )
 */
class UserLoginAttempt
{
    use Timestampable;

    /**
     * @var SonataExtUserInterface
     * @ORM\ManyToOne(targetEntity="\MKebza\SonataExt\ORM\SonataExtUserInterface")
     */
    protected $user;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $browser;

    /**
     * Possible to store IPv6.
     *
     * @var null|string
     *
     * @ORM\Column(type="string", length=39, nullable=true)
     */
    protected $ip;

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var LoginAttemptResult
     * @ORM\Column(type="login_attempt_result")
     */
    private $result;

    /**
     * UserLoginAttempt constructor.
     *
     * @param SonataExtUserInterface $user
     * @param LoginAttemptResult     $result
     * @param null|string            $browser
     * @param null|string            $ip
     */
    public function __construct(SonataExtUserInterface $user, LoginAttemptResult $result, ?string $browser, ?string $ip)
    {
        $this->user = $user;
        $this->result = $result;
        $this->browser = $browser;
        $this->ip = $ip;
    }

    /**
     * @return SonataExtUserInterface
     */
    public function getUser(): SonataExtUserInterface
    {
        return $this->user;
    }

    /**
     * @return LoginAttemptResult
     */
    public function getResult(): LoginAttemptResult
    {
        return $this->result;
    }

    /**
     * @return null|string
     */
    public function getBrowser(): ?string
    {
        return $this->browser;
    }

    /**
     * @return null|string
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }
}

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
use MKebza\SonataExt\ORM\SonataExtUserInterface;
use MKebza\SonataExt\ORM\Timestampable\Timestampable;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="user_password_reset_request",
 *     indexes={
 *          @ORM\Index(name="token", columns={"token"})
 *     }
 * )
 */
class ResetPasswordRequest
{
    use Timestampable;

    /**
     * @var null|int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, length=20, unique=true)
     */
    protected $token;

    /**
     * @var null|\DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $expire;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)
     */
    protected $browser;

    /**
     * Possible to store IPv6.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=39, nullable=true)
     */
    protected $ip;

    /**
     * @var SonataExtUserInterface
     * @ORM\ManyToOne(targetEntity="\MKebza\SonataExt\ORM\SonataExtUserInterface")
     */
    protected $user;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $used;

    /**
     * PasswordResetRequest constructor.
     *
     * @param string         $token
     * @param null|\DateTime $expire
     * @param string         $browser
     * @param string         $ip
     * @param $user
     */
    public function __construct(SonataExtUserInterface $user, string $token, ?\DateTime $expire, string $browser, string $ip)
    {
        $this->token = $token;
        $this->expire = $expire;
        $this->browser = $browser;
        $this->ip = $ip;
        $this->user = $user;
        $this->used = false;
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return null|\DateTime
     */
    public function getExpire(): ?\DateTime
    {
        return $this->expire;
    }

    /**
     * @return string
     */
    public function getBrowser(): string
    {
        return $this->browser;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @return SonataExtUserInterface
     */
    public function getUser(): SonataExtUserInterface
    {
        return $this->user;
    }

    /**
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->used;
    }

    /**
     * Set request as used.
     */
    public function use(): void
    {
        $this->used = true;
    }
}

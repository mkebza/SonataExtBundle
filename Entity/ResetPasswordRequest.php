<?php

declare(strict_types=1);


namespace MKebza\SonataExt\Entity;

use MKebza\SonataExt\ORM\SonataExtUserInterface;
use MKebza\SonataExt\ORM\Timestampable\Timestampable;
use Doctrine\ORM\Mapping as ORM;

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
     * @var integer|null
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
     * @var \DateTime|null
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
     * Possible to store IPv6
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
     * @param string $token
     * @param \DateTime|null $expire
     * @param string $browser
     * @param string $ip
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
     * @return int|null
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
     * @return \DateTime|null
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
     * Set request as used
     */
    public function use(): void
    {
        $this->used = true;
    }
}
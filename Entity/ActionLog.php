<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 10/06/2018
 * Time: 09:36
 */

namespace MKebza\SonataExt\Entity;

use Doctrine\ORM\Mapping as ORM;
use MKebza\SonataExt\ActionLog\ActionLogUserInterface;

/**
 * Class HistoryEntry
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     indexes={
 *          @ORM\Index(name="created_at", columns={"created_at"})
 *     }
 * )
 * @ORM\EntityListeners({"MKebza\SonataExt\EventListener\ActionLog\InjectUserSubscriber"})
 */
class ActionLog
{
    /**
     * @var integer
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
     * @ORM\Column(type="string", length=10, nullable=false)
     */
    private $level;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $user;

    /**
     * @var ActionLogUserInterface
     * @ORM\ManyToOne(targetEntity="MKebza\SonataExt\ActionLog\ActionLogUserInterface")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $userObject;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * EntityHistory constructor.
     */
    public function __construct(string $level, string $message, string $user = null, string $extra = null)
    {
        if (!isset(self::levels()[$level])) {
            throw new \InvalidArgumentException(sprintf('Undefined level "%s" for entity history', $level));
        }

        $this->level = $level;
        $this->message = $message;
        $this->user = $user;
        $this->content = $extra;

        $this->createdAt = new \DateTime();
    }

    public static function create(string $level, string $message, string $user = null, string $extra = null): self
    {
        return new self($level, $message, $user, $extra);
    }

    public static function success(string $message, string $user = null, string $extra = null) {
        return self::create(self::SUCCESS, $message, $user, $extra);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return null|string
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public const MESSAGE = 'message';
    public const SUCCESS = 'success';
    public const WARNING = 'warning';
    public const ERROR = 'error';

    public static function levels(): array
    {
        return [
            self::MESSAGE => 'Message',
            self::SUCCESS => 'Success',
            self::WARNING => 'Warning',
            self::ERROR => 'Error',
        ];
    }
}
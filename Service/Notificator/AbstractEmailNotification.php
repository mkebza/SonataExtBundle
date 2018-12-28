<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Service\Notificator;

use Doctrine\Common\Annotations\Annotation\Required;
use MKebza\Notificator\NotifiableInterface;
use MKebza\Notificator\Notification;
use MKebza\Notificator\NotificationInterface;
use Swift_Message;
use Symfony\Component\Templating\EngineInterface;

abstract class AbstractEmailNotification implements NotificationInterface
{
    /**
     * @var EngineInterface
     * @Required()
     */
    protected $templating;

    /**
     * AbstractEmailNotification constructor.
     *
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * @param EngineInterface $templating
     */
    public function setTemplating(EngineInterface $templating): void
    {
        $this->templating = $templating;
    }

    public function getChannels(NotifiableInterface $target): array
    {
        return ['email'];
    }

    public function email(Notification $notification, array $options): Notification
    {
        return $notification->setContent($this->emailBuildMessage($notification, $options));
    }

    protected function emailBuildMessage(Notification $notification, array $options): Swift_Message
    {
        $body = $this->emailBody($notification, $options);

        $message = (new Swift_Message())
            ->setSubject($this->emailSubject($notification, $options))
            ->setBody($body)
            ->setContentType('text/html')
        ;

        return $message;
    }

    abstract protected function emailBody(Notification $notification, array $options): string;

    abstract protected function emailSubject(Notification $notification, array $options): string;
}

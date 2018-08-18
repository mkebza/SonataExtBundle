<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 14/08/2018
 * Time: 15:08
 */
declare(strict_types=1);

namespace MKebza\SonataExt\Notification\Security;

use MKebza\Notificator\NotifiableInterface;
use MKebza\Notificator\Notification;
use MKebza\Notificator\NotificationInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ResetPasswordRequestNotification implements NotificationInterface
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ResetPasswordRequestNotification constructor.
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     */
    public function __construct(EngineInterface $templating, TranslatorInterface $translator)
    {
        $this->templating = $templating;
        $this->translator = $translator;
    }

    public function getChannels(NotifiableInterface $target): array
    {
        return [
            'email'
        ];
    }

    public function email(Notification $notification, array $options): Notification
    {
        $options = $this->configure($options);

        $message = (new \Swift_Message())
            ->setSubject($this->translator->trans('resetPasswordRequest.subject', [], 'email'))
            ->setBody(
                $this->templating->render('@Email/security/reset_password_request.mjml.twig', $options),
                'text/mjml'
            )
        ;

        return $notification->setContent($message);
    }

    protected function configure(array $options): array
    {
        return (new OptionsResolver())
            ->setDefaults([
                'user' => null,
                'request' => null
            ])
            ->resolve($options);
    }
}
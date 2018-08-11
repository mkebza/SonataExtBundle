<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 11/08/2018
 * Time: 15:09
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Service;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class AppMailer
{
    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $fromEmail;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var string
     */
    protected $translationDomain;

    /**
     * AppMailer constructor.
     * @param string $from
     * @param string $fromEmail
     * @param \Swift_Mailer $mailer
     * @param TranslatorInterface $translator
     * @param EngineInterface $templating
     * @param string $translationDomain
     */
    public function __construct(
        string $fromName,
        string $fromEmail,
        \Swift_Mailer $mailer,
        TranslatorInterface $translator,
        EngineInterface $templating,
        string $translationDomain = 'email'
    ) {
        $this->from = $from;
        $this->fromEmail = $fromEmail;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->templating = $templating;
        $this->translationDomain = $translationDomain;
    }


    public function createMessage(): \Swift_Message
    {
        $message = (new \Swift_Message())->setFrom($this->fromEmail, $this->from);

        return $message;
    }

    public function prepare(string $to, string $subject, string $template, array $templateVars = [], array $subjectVars = [], string $contentType = 'text/html'): \Swift_Message
    {
        $subject = $this->translator->trans($subject, $subjectVars, $this->translationDomain);
        $body = $this->templating->render($template, $templateVars);

        $message = $this->createMessage()
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body, $contentType);

        return $message;
    }
    
    public function send(\Swift_Message $message): void
    {
        $this->mailer->send($message);
    }

    public function prepareAndSend(string $to, string $subject, string $template, array $templateVars = [], array $subjectVars = [], string $contentType = 'text/html'): void
    {
        $this->send($this->prepare($to, $subject, $template, $templateVars, $subjectVars, $contentType));
    }
}
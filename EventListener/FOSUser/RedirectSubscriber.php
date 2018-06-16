<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 12/06/2018
 * Time: 22:01
 */

namespace MKebza\SonataExt\EventListener\FOSUser;


use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RedirectSubscriber implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * ChangePasswordSubscriber constructor.
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onChangePassword',
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onResetPassword'
        ];
    }

    public function onChangePassword(FormEvent $event): void
    {
        $event->setResponse(
            new RedirectResponse(
                $this->router->generate('sonata_admin_dashboard')
            )
        );
    }


    public function onResetPassword(FormEvent $event): void
    {
        $event->setResponse(
            new RedirectResponse(
                $this->router->generate('fos_user_security_login')
            )
        );
    }
}
<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 10/06/2018
 * Time: 11:08
 */

namespace MKebza\SonataExt\EventListener\ActionLog;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use MKebza\SonataExt\ActionLog\CurrentUserProviderInterface;
use MKebza\SonataExt\Entity\ActionLog;

class InjectUserSubscriber
{

    /**
     * @var CurrentUserProviderInterface
     */
    private $userProvider;

    /**
     * InjectUserSubscriber constructor.
     */
    public function __construct(CurrentUserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function prePersist(ActionLog $object, LifecycleEventArgs $args) {
        if (null !== $object->getUser()) {
            return;
        }

        $refl = new \ReflectionClass($object);

        $property = $refl->getProperty('user');
        $property->setAccessible(true);
        $property->setValue($object, $this->userProvider->getUsername());


        $property = $refl->getProperty('userObject');
        $property->setAccessible(true);
        $property->setValue($object, $this->userProvider->getUser());
    }
}
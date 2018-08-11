<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 10/08/2018
 * Time: 17:19
 */

namespace MKebza\SonataExt\EventListener\Timestampable;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use MKebza\SonataExt\ORM\Timestampable\Timestampable;

class TimestampableSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata
        ];
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        // the $metadata is the whole mapping info for this class
        $metadata = $eventArgs->getClassMetadata();

        if (in_array(Timestampable::class, $metadata->getReflectionClass()->getTraitNames())) {
            $metadata->addLifecycleCallback('updateCreated', Events::prePersist);
        }
    }
}
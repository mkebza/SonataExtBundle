<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
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
            Events::loadClassMetadata,
        ];
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        // the $metadata is the whole mapping info for this class
        $metadata = $eventArgs->getClassMetadata();

        if (
                null !== $metadata->getReflectionClass() &&
                in_array(Timestampable::class, $metadata->getReflectionClass()->getTraitNames(), true)
        ) {
            $metadata->addLifecycleCallback('updateCreated', Events::prePersist);
        }
    }
}

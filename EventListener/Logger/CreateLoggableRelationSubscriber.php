<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\EventListener\Logger;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use MKebza\SonataExt\Entity\Log;
use MKebza\SonataExt\ORM\Logger\LoggableInterface;

class CreateLoggableRelationSubscriber implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
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
            $metadata->isMappedSuperclass ||
            !$metadata->getReflectionClass()->implementsInterface(LoggableInterface::class)
        ) {
            return;
        }

        $metadata->mapOneToMany(
            [
                'targetEntity' => $metadata->getName()::getLogEntityFQN(),
                'fieldName' => 'log',
                'cascade' => ['persist'],
                'mappedBy' => 'reference',
                'cascade' => ['persist'],
            ]
        );
    }
}

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
use MKebza\SonataExt\Entity\LogReference;
use MKebza\SonataExt\ORM\DiscriminatorMapEntryInterface;

class LoggableMapDiscriminatorSubscriber implements EventSubscriber
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
    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        $metadata = $event->getClassMetadata();
        $classes = [];

        if (
                LogReference::class !== $metadata->getName() &&
                (null === $metadata->getReflectionClass() || !$metadata->getReflectionClass()->isSubclassOf(LogReference::class))
        ) {
            return;
        }

        $newMap = [];
        foreach ($metadata->discriminatorMap as $alias => $fqn) {
            $fqnReflection = (new \ReflectionClass($fqn));

            $newName = null;

            if (
                $fqnReflection->implementsInterface(DiscriminatorMapEntryInterface::class) &&
                !$fqnReflection->getMethod('getDiscriminatorEntryName')->isAbstract()
            ) {
                $newName = $fqn::getDiscriminatorEntryName();
            } else {
                $shortName = $fqnReflection->getShortName();
                $newName = strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $shortName));
            }

            $newMap[$newName] = $fqn;
        }

        $metadata->discriminatorMap = $newMap;
    }
}

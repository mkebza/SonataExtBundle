<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\EventListener\Timestampable;

use Carbon\Carbon;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use MKebza\SonataExt\ORM\Timestampable\Timestampable;
use MKebza\SonataExt\Utils\ClassAnalyzer;

class TimestampableSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        if (!ClassAnalyzer::hasTrait($event->getObject(), Timestampable::class)) {
            return;
        }

        $object = $event->getObject();
        if (null === $object->getCreated()) {
            $now = Carbon::now();
            $object->setCreated($now);
            $object->setUpdated($now);
        }
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        if (!ClassAnalyzer::hasTrait($event->getObject(), Timestampable::class)) {
            return;
        }

        $now = Carbon::now();
        $object = $event->getObject()->setUpdated($now);
    }
}

<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\EventListener\Sluggable;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use MKebza\SonataExt\ORM\Sluggable\Sluggable;

class SluggableSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $event): void
    {
        $reflection = new \ReflectionClass($event->getEntity());
        if (!in_array(Sluggable::class, $reflection->getTraitNames(), true)) {
            return;
        }

        dd($event->getEntity());
    }
}

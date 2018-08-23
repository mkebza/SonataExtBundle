<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\EventListener\Sluggable;

use Cocur\Slugify\SlugifyInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use MKebza\SonataExt\ORM\Sluggable\Sluggable;

class SluggableSubscriber implements EventSubscriber
{
    /**
     * @var SlugifyInterface
     */
    private $slugger;

    /**
     * SluggableSubscriber constructor.
     *
     * @param SlugifyInterface $slugger
     */
    public function __construct(SlugifyInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $event): void
    {
        $entity = $event->getEntity();
        if ($this->hasTrait($entity)) {
            $entity->setSlug($this->slug($entity->getSlugSource()));
        }
    }

    public function preUpdate(LifecycleEventArgs $event): void
    {
        $entity = $event->getEntity();
        if ($this->hasTrait($entity) && $entity->shouldUpdateSlugOnUpdate()) {
            $entity->setSlug($this->slug($entity->getSlugSource()));
        }
    }

    protected function slug(string $string): string
    {
        return $this->slugger->slugify($string);
    }

    protected function hasTrait($entity): bool
    {
        $reflection = new \ReflectionClass($entity);

        return in_array(Sluggable::class, $reflection->getTraitNames(), true);
    }
}

<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use MKebza\SonataExt\Entity\Log;
use MKebza\SonataExt\Enum\LogLevel;
use MKebza\SonataExt\ORM\Logger\LoggableInterface;
use MKebza\SonataExt\Service\Logger\UserProviderInterface;

class EntityLoggerSubscriber implements EventSubscriber
{
    /**
     * @var string
     */
    private $channel;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * EntityLoggerSubscriber constructor.
     *
     * @param string                $channel
     * @param UserProviderInterface $userProvider
     */
    public function __construct(string $channel, UserProviderInterface $userProvider)
    {
        $this->channel = $channel;
        $this->userProvider = $userProvider;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $event->getEntityManager()->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $key => $entity) {
            if ($entity instanceof LoggableInterface) {
                $changeSet = $uow->getEntityChangeSet($entity);
                $this->createRecord(
                    $em,
                    $entity,
                    sprintf('Entity %s updated', $entity->getLogString()),
                    $uow->getEntityChangeSet($entity));
            }
        }

        foreach ($uow->getScheduledEntityInsertions() as $key => $entity) {
            if ($entity instanceof LoggableInterface) {
                $this->createRecord($em, $entity, sprintf('Entity %s created', $entity->getLogString()));
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $key => $entity) {
            if ($entity instanceof LoggableInterface) {
                if ($entity instanceof LoggableInterface) {
                    $this->createRecord($em, $entity, sprintf('Entity %s deleted', $entity->getLogString()));
                }
            }
        }
    }

    private function createRecord(EntityManagerInterface $em, object $entity, string $message, array $extra = null): void
    {
        $uow = $em->getUnitOfWork();

        $entry = new Log(
            $this->channel,
            $message,
            LogLevel::INFO(),
            $this->userProvider->getName(),
            $this->userProvider->getUser(),
            $extra
        );

        $referenceClassName = $entity->getLogEntityFQN();
        $reference = new $referenceClassName($entry, $entity);

        $em->persist($entry);
        $uow->computeChangeSet($em->getClassMetadata(Log::class), $entry);
        $em->persist($reference);
        $uow->computeChangeSet($em->getClassMetadata($referenceClassName), $reference);
    }
}

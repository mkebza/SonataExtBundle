<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\EventListener\AppLog;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use MKebza\SonataExt\Entity\ActionLog;
use MKebza\SonataExt\Entity\AppLog;
use MKebza\SonataExt\ORM\ActionLog\ActionLoggableInterface;
use MKebza\SonataExt\ORM\AppLog\LoggableInterface;

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

        $namingStrategy = $eventArgs
            ->getEntityManager()
            ->getConfiguration()
            ->getNamingStrategy();

        $relationInfo = [
            'table' => strtolower($namingStrategy->classToTableName($metadata->getName().'Log')),
            'entity_name' => $metadata->getName(),
            'entity_column' => $namingStrategy->joinKeyColumnName($metadata->getName()),
            'log_column' => 'log_id',
        ];

        $metadata->mapManyToMany(
            [
                'orderBy' => ['createdAt' => 'DESC'],
                'targetEntity' => AppLog::class,
                'fieldName' => 'log',
                'cascade' => ['persist'],
                'joinTable' => [
                    'name' => $relationInfo['table'],
                    'joinColumns' => [
                        [
                            'name' => $relationInfo['entity_column'],
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                    'inverseJoinColumns' => [
                        [
                            'name' => $relationInfo['log_column'],
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ],
                    ],
                ],
            ]
        );
    }
}

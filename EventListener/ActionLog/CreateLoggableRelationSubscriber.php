<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 10/06/2018
 * Time: 10:24
 */

namespace MKebza\SonataExt\EventListener\ActionLog;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use MKebza\EntityHistory\Entity\EntityHistory;
use MKebza\EntityHistory\ORM\EntityHistoryInterface;
use MKebza\SonataExt\Entity\ActionLog;
use MKebza\SonataExt\ORM\ActionLog\ActionLoggableInterface;


class CreateLoggableRelationSubscriber implements EventSubscriber
{
    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
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
            !$metadata->getReflectionClass()->implementsInterface(ActionLoggableInterface::class)
        ) {
            return;
        }

        $namingStrategy = $eventArgs
            ->getEntityManager()
            ->getConfiguration()
            ->getNamingStrategy();

        $relationInfo = [
            'table' => strtolower($namingStrategy->classToTableName($metadata->getName().'ActionLog')),
            'entity_name' => $metadata->getName(),
            'entity_column' => $namingStrategy->joinKeyColumnName($metadata->getName()),
            'log_column' => 'log_id'
        ];

        $metadata->mapManyToMany(
            array(
                'orderBy' => ['createdAt' => 'DESC'],
                'targetEntity' => ActionLog::class,
                'fieldName' => 'loggedActions',
                'cascade' => array('persist'),
                'joinTable' => array(
                    'name' => $relationInfo['table'],
                    'joinColumns' => array(
                        array(
                            'name' => $relationInfo['entity_column'],
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ),
                    ),
                    'inverseJoinColumns' => array(
                        array(
                            'name' => $relationInfo['log_column'],
                            'referencedColumnName' => $namingStrategy->referenceColumnName(),
                            'onDelete' => 'CASCADE',
                            'onUpdate' => 'CASCADE',
                        ),
                    )
                )
            )
        );
    }
}

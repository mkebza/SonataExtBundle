<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Service\Logger;

use Doctrine\ORM\EntityManagerInterface;
use MKebza\SonataExt\Entity\Log;
use MKebza\SonataExt\Exception\ObjectNotLoggableException;
use MKebza\SonataExt\ORM\Logger\LoggableInterface;
use Monolog\Handler\AbstractHandler;

class AssignToEntityHandler extends AbstractHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * AssignToEntityHandler constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function handle(array $record)
    {
        $message = new Log($record['channel'], $record['message'], $record['level'], null, null, $record['extra']);
        $this->em->persist($message);

        if (isset($record['context']['references'])) {
            $references = $this->normalizeReferences($record['context']['references']);
            foreach ($references as $reference) {
                $reference->log($message);
            }
        }

        $this->em->flush();
    }

    /**
     * @param $references
     *
     * @return LoggableInterface[]
     */
    protected function normalizeReferences($references): array
    {
        if (!is_array($references)) {
            $references = [$references];
        }

        foreach ($references as $reference) {
            if (!$reference instanceof LoggableInterface) {
                throw new ObjectNotLoggableException(sprintf('Object %s is not loggable', get_class($reference)));
            }
        }

        return $references;
    }
}
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
use Psr\Log\LoggerInterface;

class EntityLoggerSubscriber implements EventSubscriber
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * EntityLoggerSubscriber constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $appLogger)
    {
        $this->logger = $appLogger;
    }

    public function getSubscribedEvents()
    {
        return [];
    }
}

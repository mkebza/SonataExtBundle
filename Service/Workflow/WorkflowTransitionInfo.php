<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Service\Workflow;

use MKebza\SonataExt\ORM\Logger\LoggableInterface;

class WorkflowTransitionInfo
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var LoggableInterface[]
     */
    private $references;

    /**
     * WorkflowTransitionInfo constructor.
     *
     * @param string              $message
     * @param LoggableInterface[] $references
     */
    public function __construct(string $message, array $references = [])
    {
        $this->message = $message;
        $this->references = $references;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return LoggableInterface[]
     */
    public function getReferences(): array
    {
        return $this->references;
    }
}

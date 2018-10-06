<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\ORM\Logger;

use Doctrine\Common\Collections\ArrayCollection;

trait Loggable
{
    /**
     * @var ArrayCollection
     */
    protected $log;

    public static function getLogEntityFQCN(): string
    {
        return self::class.'Log';
    }

    final public function getLogString(): string
    {
        try {
            $reflection = new \ReflectionClass($this);

            $idPart = '?';
            $namePart = $reflection->getName();

            if ($reflection->hasMethod('getId') && null !== $this->getId()) {
                $idPart = $this->getId();
            }

            $checkMethods = ['getCode', 'getUsername', 'getName', 'getTitle', '__toString'];
            foreach ($checkMethods as $method) {
                if (
                    $reflection->hasMethod($method) &&
                    0 === $reflection->getMethod($method)->getNumberOfParameters() &&
                    null !== $this->{$method}()
                ) {
                    $namePart = $this->{$method}();

                    break;
                }
            }

            return sprintf("'%s' [#%s]", $namePart, $idPart);
        } catch (\Exception $e) {
            // for deleted entiteis, we still can return some info, unfortunately ID will stay in database
            return '-- entity deleted --';
        }
    }
}

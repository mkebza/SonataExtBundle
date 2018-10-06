<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\ORM\Logger;

interface LoggableInterface
{
    public static function getLogEntityFQCN(): string;

    public function getLogString(): string;
}

<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Utils;

use RandomLib\Generator;

class TokenGenerator implements TokenGeneratorInterface
{
    public function generate(int $length): string
    {
        $factory = new \RandomLib\Factory();

        return $factory->getLowStrengthGenerator()->generateString($length, Generator::CHAR_ALNUM);
    }
}

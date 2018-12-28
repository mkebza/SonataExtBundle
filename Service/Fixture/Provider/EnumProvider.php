<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Service\Fixture\Provider;

use Faker\Generator;
use Faker\Provider\Base;

final class EnumProvider extends Base
{
    /**
     * DateIntervalProvider constructor.
     */
    public function __construct()
    {
        parent::__construct(new Generator());
    }

    public function enum($enum)
    {
//        list($class, $method) = explode(':', $enum);
//        dd($class);
        return $enum();
    }
}

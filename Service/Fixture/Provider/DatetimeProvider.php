<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Service\Fixture\Provider;

use Carbon\Carbon;
use Faker\Generator;
use Faker\Provider\Base;

final class DatetimeProvider extends Base
{
    /**
     * DateIntervalProvider constructor.
     */
    public function __construct()
    {
        parent::__construct(new Generator());
    }

    public function datetime($date)
    {
        return Carbon::parse($date);
    }
}

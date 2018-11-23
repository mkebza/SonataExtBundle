<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Service\Fixture\Provider;

use Faker\Provider\Base;
use Money\Money;

class MoneyProvider extends Base
{
    public function money($min = 100, $max = 999999, $currency = 'USD')
    {
        $amount = self::numberBetween($min, $max);

        return Money::$currency($amount);
    }
}

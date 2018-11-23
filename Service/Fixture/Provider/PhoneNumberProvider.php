<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Service\Fixture\Provider;

use Faker\Provider\Base;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberProvider extends Base
{
    /**
     * @var PhoneNumberUtil
     */
    private $phoneNumberUtil;

    /**
     * PhoneNumberProvider constructor.
     *
     * @param PhoneNumberUtil $phoneNumberUtil
     */
    public function __construct(PhoneNumberUtil $phoneNumberUtil)
    {
        $this->phoneNumberUtil = $phoneNumberUtil;
    }

    public function phoneNumberObject($str)
    {
        // Dirty hack but most of generated numbers don't go through region validation
        $str = '+420'.substr($str, 4);

        return $this->phoneNumberUtil->parse($str, PhoneNumberUtil::UNKNOWN_REGION);
    }
}

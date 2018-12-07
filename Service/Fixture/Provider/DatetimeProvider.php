<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 2018-12-06
 * Time: 19:03
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
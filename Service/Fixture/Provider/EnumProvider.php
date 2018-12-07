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
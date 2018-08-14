<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 11/08/2018
 * Time: 16:14
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
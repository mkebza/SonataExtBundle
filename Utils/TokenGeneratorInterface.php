<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 11/08/2018
 * Time: 16:18
 */

namespace MKebza\SonataExt\Utils;


interface TokenGeneratorInterface
{
    public function generate(int $length, string $allowed): string;
}
<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 27/08/2018
 * Time: 10:42
 */

namespace MKebza\SonataExt\ORM;


interface DiscriminatorMapEntryInterface
{
    public static function getDiscriminatorEntryName(): string;

}
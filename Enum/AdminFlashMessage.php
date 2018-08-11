<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 11/08/2018
 * Time: 12:27
 */
declare(strict_types=1);

namespace MKebza\SonataExt\Enum;

final class AdminFlashMessage
{
    public const SUCCESS = 'sonata_flash_success';
    public const ERROR = 'sonata_flash_error';
    public const WARNING = 'sonata_flash_warning';
}
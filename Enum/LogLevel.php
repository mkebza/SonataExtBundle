<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Enum;

use Elao\Enum\EnumInterface;
use MKebza\SonataExt\Utils\TranslatableEnum;
use Monolog\Logger;

/**
 * Class LogLevel.
 *
 * @method static LogLevel DEBUG()
 * @method static LogLevel INFO()
 * @method static LogLevel NOTICE()
 * @method static LogLevel WARNING()
 * @method static LogLevel ERROR()
 * @method static LogLevel CRITICAL()
 * @method static LogLevel ALERT()
 * @method static LogLevel EMERGENCY()
 */
final class LogLevel extends TranslatableEnum
{
    public const DEBUG = 'debug';
    public const INFO = 'info';
    const NOTICE = 'notice';
    const WARNING = 'warning';
    const ERROR = 'error';
    const CRITICAL = 'critical';
    const ALERT = 'alert';
    const EMERGENCY = 'emergency';

    public static function fromMonologLevel(int $level): EnumInterface
    {
        $map = [
            Logger::DEBUG => self::DEBUG(),
            Logger::INFO => self::INFO(),
            Logger::NOTICE => self::NOTICE(),
            Logger::WARNING => self::WARNING(),
            Logger::ERROR => self::ERROR(),
            Logger::CRITICAL => self::CRITICAL(),
            Logger::ALERT => self::ALERT(),
            Logger::EMERGENCY => self::EMERGENCY(),
        ];

        return $map[$level];
    }
}

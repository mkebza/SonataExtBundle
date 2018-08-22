<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Enum;

use MKebza\SonataExt\Utils\TranslatableEnum;

/**
 * @method static LoginAttemptResult SUCCESS()
 * @method static LoginAttemptResult FAILURE()
 */
final class LoginAttemptResult extends TranslatableEnum
{
    const SUCCESS = 'success';
    const FAILURE = 'failure';
}

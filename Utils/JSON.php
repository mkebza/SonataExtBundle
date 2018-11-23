<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Utils;

use MKebza\SonataExt\Exception\JsonDecodeException;

class JSON
{
    /**
     * @param string $string
     * @param bool   $assoc
     * @param int    $depth
     * @param int    $options
     *
     * @return array|object
     */
    public static function decode(string $string, bool $assoc = true, int $depth = 512, int $options = 0)
    {
        $decoded = json_decode($string, $assoc, $depth, $options);
        if (JSON_ERROR_NONE === json_last_error()) {
            return $decoded;
        }

        throw new JsonDecodeException(json_last_error_msg());
    }
}

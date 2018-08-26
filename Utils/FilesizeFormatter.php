<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Utils;

class FilesizeFormatter
{
    /**
     * Formats filesize with base of 1000.
     *
     * @param int $size
     * @param int $precision
     *
     * @return string
     */
    public static function format(int $size, int $precision = 2)
    {
        if ($size < 1000) {
            return $size.' B';
        }

        $factor = floor(log($size, 1000));

        return sprintf("%.{$precision}f ", $size / pow(1000, $factor)).['B', 'KB', 'MB', 'GB', 'TB', 'PB'][$factor];
    }

    /**
     * Formats filesize with base of 1024.
     *
     * @param int $size
     * @param int $precision
     *
     * @return string
     */
    public static function binary(int $size, int $precision = 2)
    {
        if ($size < 1024) {
            return $size.' B';
        }

        $factor = floor(log($size, 1024));

        return sprintf("%.{$precision}f ", $size / pow(1024, $factor)).['B', 'KB', 'MB', 'GB', 'TB', 'PB'][$factor];
    }
}

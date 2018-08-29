<?php

declare(strict_types=1);


namespace MKebza\SonataExt\Utils;


class ClassAnalyzer
{
    public static function hasTrait($class, $trait): bool
    {
        return in_array($trait, self::getAllTraits($class));
    }

    public static function getAllTraits($class, bool $autoload = true): array
    {
        $traits = [];

        // Get traits of all parent classes
        do {
            $traits = array_merge(class_uses($class, $autoload), $traits);
        } while ($class = get_parent_class($class));

        // Get traits of all parent traits
        $traitsToSearch = $traits;
        while (!empty($traitsToSearch)) {
            $newTraits = class_uses(array_pop($traitsToSearch), $autoload);
            $traits = array_merge($newTraits, $traits);
            $traitsToSearch = array_merge($newTraits, $traitsToSearch);
        };

        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait, $autoload), $traits);
        }

        return array_values(array_merge(array_unique($traits)));
    }
}
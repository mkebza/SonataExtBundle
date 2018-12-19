<?php

declare(strict_types=1);


namespace MKebza\SonataExt\Twig\Runtime;


use App\Enum\BookingStatus;
use Elao\Enum\EnumInterface;
use Twig\Extension\RuntimeExtensionInterface;

class EnumRuntime implements RuntimeExtensionInterface
{
    public function choices(string $enum): array
    {
        $refClass = new \ReflectionClass($enum);
        if (!$refClass->implementsInterface(EnumInterface::class)) {
            throw new \LogicException(sprintf(
                "You have to provde name of enum class, provided '%s' and enum has to implement '%s'",
                $enum, EnumInterface::class));
        }

        return $enum::readables();
    }
}
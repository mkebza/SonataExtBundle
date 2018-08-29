<?php

declare(strict_types=1);


namespace MKebza\SonataExt\Twig\Extension;


use MKebza\SonataExt\Twig\Runtime\TextRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TextExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('text_itemize', [TextRuntime::class, 'itemize']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('text_itemize', [TextRuntime::class, 'itemize']),
        ];
    }


}
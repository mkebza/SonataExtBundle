<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard\Block;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface BlockInterface
{
    public function execute(array $options = []): ?string;

    public function options(OptionsResolver $resolver): void;

    public function getAlias(): string;

    public function hasAccess(): bool;
}

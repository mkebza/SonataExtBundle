<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard\Block\Type;

class SeparatorBlock extends AbstractBlock
{
    public function execute(array $options = []): ?string
    {
        return $this->render('@SonataExt/block/separator.html.twig', [], $options);
    }
}

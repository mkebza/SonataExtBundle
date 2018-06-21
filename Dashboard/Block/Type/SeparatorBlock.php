<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 21/06/2018
 * Time: 10:58
 */

namespace MKebza\SonataExt\Dashboard\Block\Type;


class SeparatorBlock extends AbstractBlock
{
    public function execute(array $options = []): ?string
    {
        return $this->render('@SonataExt/block/separator.html.twig', [], $options);
    }
}
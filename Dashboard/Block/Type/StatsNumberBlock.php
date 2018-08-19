<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard\Block\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class StatsNumberBlock extends AbstractBlock
{
    public function execute(array $options = []): ?string
    {
        return $this->render('@SonataExt/block/stats_number.html.twig', [
            'number' => $options['number'],
        ], $options);
    }

    protected function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'block_size' => 'col-xs-6 col-sm-4 col-md-3',
                'number' => null,
                'color' => 'primary',
                'icon' => null,
                'label' => null,
                'target' => null,
            ])
            ->setRequired('number')
        ;
    }
}

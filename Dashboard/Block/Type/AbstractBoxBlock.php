<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard\Block\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractBoxBlock extends AbstractBlock
{
    public function options(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'block_type' => 'primary',
            'block_size' => 'col-xs-12 col-md-6',
        ]);

        parent::options($resolver);
    }
}

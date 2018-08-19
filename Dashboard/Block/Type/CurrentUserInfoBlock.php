<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard\Block\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrentUserInfoBlock extends AbstractBoxBlock
{
    public function execute(array $options = []): ?string
    {
        return $this->render('@SonataExt/block/current_user_info.html.twig', [], $options);
    }

    protected function configure(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'block_size' => 'col-sm-12',
        ]);
    }
}

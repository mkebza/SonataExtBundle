<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 19:23
 */

namespace MKebza\SonataExt\Dashboard\Block\Type;


use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractBoxBlock extends AbstractBlock
{
    public function options(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'block_type' => 'primary',
        ]);

        parent::options($resolver);
    }
}
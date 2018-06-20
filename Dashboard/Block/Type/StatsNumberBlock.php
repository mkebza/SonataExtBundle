<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 20:43
 */

namespace MKebza\SonataExt\Dashboard\Block\Type;


use Symfony\Component\OptionsResolver\OptionsResolver;

class StatsNumberBlock extends AbstractBlock
{
    public function execute(array $options = []): ?string
    {
    }

    protected function configure(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'model' => null,
            'repository_method' => null,
            'color' => null,
            'icon' => null,
        ]);
    }


}
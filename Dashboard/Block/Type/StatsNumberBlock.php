<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 20:43
 */

namespace MKebza\SonataExt\Dashboard\Block\Type;


use MKebza\SonataExt\Dashboard\Block\StatsNumberProvider\StatsNumberProviderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatsNumberBlock extends AbstractBlock
{
    public function execute(array $options = []): ?string
    {
        return $this->render('@SonataExt/block/stats_number.html.twig', [
            'number' => $options['number']
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
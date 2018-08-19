<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard;

use MKebza\SonataExt\Dashboard\Block\BlockRegistryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DashboardRenderer
{
    /**
     * @var BlockRegistryInterface
     */
    private $registry;

    /**
     * DashboardRenderer constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param BlockRegistryInterface   $registry
     */
    public function __construct(BlockRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function render(DashboardInterface $dashboard): string
    {
        $builder = new DashboardBuilder();

        $dashboard->build($builder);

        $output = '';
        foreach ($builder->resolve() as $name => $info) {
            $output .= $this->renderBlock($info['block'], $info['options']);
        }

        return $output;
    }

    public function renderBlock(string $alias, array $options = []): ?string
    {
        $block = $this->registry->get($alias);
        if (!$block->hasAccess()) {
            return null;
        }

        $resolver = new OptionsResolver();
        $block->options($resolver);

        return $block->execute($resolver->resolve($options));
    }
}

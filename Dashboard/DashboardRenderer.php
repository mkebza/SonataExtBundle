<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 15:47
 */

namespace MKebza\SonataExt\Dashboard;


use MKebza\SonataExt\Dashboard\Block\BlockRegistryInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DashboardRenderer
{

    /**
     * @var BlockRegistryInterface
     */
    private $registry;

    /**
     * DashboardRenderer constructor.
     * @param EventDispatcherInterface $dispatcher
     * @param BlockRegistryInterface $registry
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
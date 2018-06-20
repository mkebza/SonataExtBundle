<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 15:32
 */

namespace MKebza\SonataExt\Dashboard\Block;


use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

interface BlockInterface
{
    public function execute(array $options = []): ?string;
    public function options(OptionsResolver $resolver): void;
    public function getAlias(): string;
    public function hasAccess(): bool;
}
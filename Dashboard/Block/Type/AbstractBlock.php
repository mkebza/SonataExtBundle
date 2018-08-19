<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard\Block\Type;

use MKebza\SonataExt\Dashboard\Block\BlockInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Templating\EngineInterface;

abstract class AbstractBlock implements BlockInterface
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @param EngineInterface $engine
     * @required
     */
    public function setTemplating(EngineInterface $engine): void
    {
        $this->templating = $engine;
    }

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function setAuthorizationChecker($authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function getAlias(): string
    {
        return static::class;
    }

    public function options(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);

        $this->configure($resolver);
    }

    public function hasAccess(): bool
    {
        foreach ($this->getRoles() as $role) {
            if ($this->authorizationChecker->isGranted($role, $this)) {
                return true;
            }
        }

        return false;
    }

    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }

    protected function configure(OptionsResolver $resolver): void
    {
    }

    protected function render($template, array $vars, array $options): string
    {
        if (!is_array($options)) {
            throw new \LogicException('You have to provide options for rendering if using abstract block');
        }

        return $this->templating->render($template,
            $vars + ['_block' => $options]
        );
    }
}

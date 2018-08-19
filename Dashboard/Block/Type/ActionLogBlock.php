<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard\Block\Type;

use MKebza\SonataExt\Repository\ActionLogRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionLogBlock extends AbstractBoxBlock
{
    /**
     * @var ActionLogRepository
     */
    protected $repository;

    /**
     * ActionLogBlock constructor.
     *
     * @param ActionLogRepository $repository
     */
    public function __construct(ActionLogRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(array $options = []): ?string
    {
        $qb = $this->repository
            ->createQueryBuilder('entry')
            ->select('entry')
            ->setMaxResults($options['limit'])
            ->orderBy('entry.createdAt', 'DESC')
        ;

        if (null !== $options['level']) {
            $levels = $options['level'];
            if (is_string($levels)) {
                $levels = [$levels];
            }

            $qb
                ->where('entry.level IN (:levels)')
                ->setParameter('levels', $levels);
        }

        return $this->render('@SonataExt/block/action_log.html.twig', [
            'records' => $qb->getQuery()->getResult(),
        ], $options);
    }

    protected function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'level' => null,
                'limit' => 20,
                'label' => 'Action log',
            ])
        ;
    }
}

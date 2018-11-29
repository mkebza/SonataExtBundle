<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Dashboard\Block\Type;

use MKebza\SonataExt\Repository\ActionLogRepository;
use MKebza\SonataExt\Repository\AppLogRepository;
use MKebza\SonataExt\Repository\LogRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class LogBlock extends AbstractBoxBlock
{
    /**
     * @var AppLogRepository
     */
    protected $repository;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * ActionLogBlock constructor.
     *
     * @param ActionLogRepository $repository
     */
    public function __construct(LogRepository $repository, TranslatorInterface $translator)
    {
        $this->repository = $repository;
        $this->translator = $translator;
    }

    public function execute(array $options = []): ?string
    {
        $qb = $this->repository
            ->createQueryBuilder('entry')
            ->select('entry')
            ->setMaxResults($options['limit'])
            ->orderBy('entry.created', 'DESC')
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

        return $this->render('@SonataExt/block/log.html.twig', [
            'records' => $qb->getQuery()->getResult(),
        ], $options);
    }

    protected function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'level' => null,
                'limit' => 20,
                'label' => $this->translator->trans('block.Log.title', [], 'admin'),
            ])
        ;
    }
}

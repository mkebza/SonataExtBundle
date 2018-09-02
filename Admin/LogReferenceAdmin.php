<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;

class LogReferenceAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'reference';
    protected $baseRouteName = 'reference';

    public function getBatchActions()
    {
        return [];
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('log.channel')
            ->add('log.message')
            ->add('log.level')
            ->add('log.user')
            ->add('log.created')
        ;
    }
}

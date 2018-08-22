<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin as BaseAbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

abstract class AbstractAdmin extends BaseAbstractAdmin
{
    protected $translationDomain = 'admin';

    public function getExportFormats()
    {
        return [];
    }

    /**
     * @return \App\Entity\User
     */
    public function getUser()
    {
        return $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
    }

    public function isEditing()
    {
        return $this->id($this->getSubject());
    }

    public function isCreating()
    {
        return null === $this->id($this->getSubject());
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
        $collection->remove('export');
    }
}

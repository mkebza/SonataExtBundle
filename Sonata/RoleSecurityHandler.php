<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Sonata;

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Security\Handler\RoleSecurityHandler as BaseRoleSecurityHandler;

class RoleSecurityHandler extends BaseRoleSecurityHandler
{
    public function getBaseRole(AdminInterface $admin)
    {
        return str_replace('ROLE_SONATA_ADMIN_', 'ROLE_ADMIN_', parent::getBaseRole($admin));
    }
}

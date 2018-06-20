<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 10:21
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

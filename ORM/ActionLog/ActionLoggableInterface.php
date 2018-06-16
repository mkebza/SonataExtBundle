<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 10/06/2018
 * Time: 09:56
 */

namespace MKebza\SonataExt\ORM\ActionLog;


use MKebza\SonataExt\Entity\ActionLog;

interface ActionLoggableInterface
{
    /**
     * On purpose omitted return type
     * @param ActionLog $object
     * @return mixed
     */
    public function logAction(ActionLog $object);
}
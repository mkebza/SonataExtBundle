<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 16/06/2018
 * Time: 13:18
 */

namespace MKebza\SonataExt\ActionLog;


interface ActionLogUserInterface
{
    public function getActionLogName(): string;
}
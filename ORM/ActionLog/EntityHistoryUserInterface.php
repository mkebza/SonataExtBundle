<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 10/06/2018
 * Time: 12:23
 */

namespace MKebza\EntityHistory\ORM;


interface EntityHistoryUserInterface
{
    public function getHistoryUsername(): ?string;
}
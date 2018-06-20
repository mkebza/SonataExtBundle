<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 15:39
 */

namespace MKebza\SonataExt\Dashboard\Block\Type;

class CurrentUserInfoBlock extends AbstractBoxBlock
{
    public function execute(array $options = []): ?string
    {
        return $this->render('@SonataExt/block/current_user_info.html.twig', [], $options);
    }
}
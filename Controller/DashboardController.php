<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Controller;

use MKebza\SonataExt\Dashboard\DashboardInterface;
use MKebza\SonataExt\Dashboard\DashboardRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    private $renderer;
    private $dashboard;

    /**
     * DashboardController constructor.
     */
    public function __construct(DashboardRenderer $renderer, DashboardInterface $dashboard)
    {
        $this->renderer = $renderer;
        $this->dashboard = $dashboard;
    }

    public function dashboardAction(): Response
    {
        return $this->render('@SonataExt/dashboard/dashboard.html.twig', [
            'renderer' => $this->renderer,
            'dashboard' => $this->dashboard,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\{Config, Controller};
use App\Service\ShowSiteService;

class ShowSiteController extends Controller
{
    public function showSiteAction(array $request, array $session): array
    {
        $config = new Config();

        $showSiteService = new ShowSiteService($this, $config);
        $array = $showSiteService->wwwAction((int) $session['id']);

        return $array;
    }
}

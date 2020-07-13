<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Controller, Token};
use App\Repository\SiteRepository;
use App\Service\EditSiteService;
use App\Validator\EditSiteValidator;

class EditSiteController extends Controller
{
    public function editSiteAction(array $request, array $session): array
    {
        $config = new Config();
        $html = new Html();
        $csrfToken = new Token();
        $editSiteValidator = new EditSiteValidator($csrfToken);
        $rm = $this->getManager();

        $userSiteId = $rm->getRepository(SiteRepository::class)
            ->isUserSiteId((int) $session['id'], (int) $request['site']);
        if (!$userSiteId) {
            return $this->redirectToRoute('login_page');
        }

        $editSiteService = new EditSiteService(
            $this,
            $config,
            $html,
            $csrfToken,
            $editSiteValidator
        );
        $array = $editSiteService->variableAction(
            (string) $request['name'],
            (string) $request['www'],
            (int) (bool) $request['visible'],
            (bool) $request['delete'],
            (bool) $request['submit'],
            (string) $request['token'],
            (int) $request['site']
        );

        return $array;
    }
}

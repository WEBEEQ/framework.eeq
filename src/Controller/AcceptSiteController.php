<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Controller, Email, Token};
use App\Repository\SiteRepository;
use App\Service\AcceptSiteService;
use App\Validator\AcceptSiteValidator;

class AcceptSiteController extends Controller
{
    public function acceptSiteAction(array $request, array $session): array
    {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $csrfToken = new Token();
        $acceptSiteValidator = new AcceptSiteValidator($csrfToken);
        $rm = $this->getManager();

        $siteId = $rm->getRepository(SiteRepository::class)
            ->isSiteId((int) $request['site']);
        if (!$siteId) {
            return $this->redirectToRoute('login_page');
        }

        $acceptSiteService = new AcceptSiteService(
            $this,
            $config,
            $mail,
            $html,
            $csrfToken,
            $acceptSiteValidator
        );
        $array = $acceptSiteService->variableAction(
            (string) $request['name'],
            (string) $request['www'],
            (int) (bool) $request['active'],
            (int) (bool) $request['visible'],
            (bool) $request['delete'],
            (bool) $request['submit'],
            (string) $request['token'],
            (int) $request['site']
        );

        return $array;
    }
}

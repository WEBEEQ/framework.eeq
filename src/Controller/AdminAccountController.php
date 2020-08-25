<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Controller};
use App\Repository\UserRepository;
use App\Service\AdminAccountService;

class AdminAccountController extends Controller
{
    public function adminAccountAction(array $request, array $session): array
    {
        $config = new Config();
        $html = new Html();
        $rm = $this->getManager();

        if (
            (int) $request['account']
            && (int) $request['account'] !== (int) $session['id']
        ) {
            return $this->redirectToRoute('login_page');
        }

        $userId = $rm->getRepository(UserRepository::class)
            ->isUserId((int) $session['id']);
        if (!$userId) {
            return $this->redirectToRoute('login_page');
        }

        $adminAccountService = new AdminAccountService(
            $this,
            $config,
            $html
        );
        $array = $adminAccountService->variableAction(
            (int) ($request['level'] ?? 1),
            (int) $session['id']
        );

        return $array;
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Controller, Token};
use App\Repository\UserRepository;
use App\Service\UserAccountService;
use App\Validator\UserAccountValidator;

class UserAccountController extends Controller
{
    public function userAccountAction(array $request, array $session): array
    {
        $config = new Config();
        $html = new Html();
        $csrfToken = new Token();
        $userAccountValidator = new UserAccountValidator($csrfToken);
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

        $userAccountService = new UserAccountService(
            $this,
            $config,
            $html,
            $csrfToken,
            $userAccountValidator
        );
        $array = $userAccountService->variableAction(
            (string) $request['name'],
            (string) $request['www'],
            (bool) $request['submit'],
            (string) $request['token'],
            (int) ($request['level'] ?? 1),
            (int) $session['id']
        );

        return $array;
    }
}

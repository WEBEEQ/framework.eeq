<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Controller, Email, Token};
use App\Service\LogInUserService;
use App\Validator\LogInUserValidator;

class LogInUserController extends Controller
{
    public function logInUserAction(array $request, array $session): array
    {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $csrfToken = new Token();
        $logInUserValidator = new LogInUserValidator($csrfToken);

        $logInUserService = new LogInUserService(
            $this,
            $config,
            $mail,
            $html,
            $csrfToken,
            $logInUserValidator
        );
        $array = $logInUserService->variableAction(
            (string) $request['login'],
            (string) $request['password'],
            (bool) $request['remember'],
            (bool) $request['submit'],
            (string) $request['token']
        );

        return $array;
    }
}

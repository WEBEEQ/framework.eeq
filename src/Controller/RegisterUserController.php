<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\{Html, Key};
use App\Core\{Config, Controller, Email, Token};
use App\Service\RegisterUserService;
use App\Validator\RegisterUserValidator;

class RegisterUserController extends Controller
{
    public function registerUserAction(array $request, array $session): array
    {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $key = new Key();
        $csrfToken = new Token();
        $registerUserValidator = new RegisterUserValidator(
            $csrfToken,
            $this->getManager()
        );

        $registerUserService = new RegisterUserService(
            $this,
            $config,
            $mail,
            $html,
            $key,
            $csrfToken,
            $registerUserValidator
        );
        $array = $registerUserService->variableAction(
            (string) $request['name'],
            (string) $request['surname'],
            (string) $request['login'],
            (string) $request['password'],
            (string) $request['repeat_password'],
            (string) $request['email'],
            (string) $request['repeat_email'],
            (bool) $request['accept'],
            (bool) $request['submit'],
            (string) $request['token']
        );

        return $array;
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\{Html, Key};
use App\Core\{Config, Controller, Email, Token};
use App\Repository\UserRepository;
use App\Service\EditUserService;
use App\Validator\EditUserValidator;

class EditUserController extends Controller
{
    public function editUserAction(array $request, array $session): array
    {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $key = new Key();
        $csrfToken = new Token();
        $editUserValidator = new EditUserValidator(
            $csrfToken,
            $rm = $this->getManager()
        );

        $userUserId = $rm->getRepository(UserRepository::class)
            ->isUserUserId((int) $session['id'], (int) $request['user']);
        if (!$userUserId) {
            return $this->redirectToRoute('login_page');
        }

        $editUserService = new EditUserService(
            $this,
            $config,
            $mail,
            $html,
            $key,
            $csrfToken,
            $editUserValidator
        );
        $array = $editUserService->variableAction(
            (string) $request['login'],
            (string) $request['password'],
            (string) $request['new_password'],
            (string) $request['repeat_password'],
            (string) $request['name'],
            (string) $request['surname'],
            (string) $request['street'],
            (string) $request['postcode'],
            (int) $request['province'],
            (int) $request['city'],
            (string) $request['phone'],
            (string) $request['email'],
            (string) $request['new_email'],
            (string) $request['repeat_email'],
            (string) $request['www'],
            (string) $request['description'],
            (bool) $request['submit'],
            (string) $request['token'],
            (int) $request['user'],
            (string) $session['user']
        );

        return $array;
    }
}

<?php

declare(strict_types=1);

namespace App\Validator\Api;

use App\Bundle\Message;

class AddSiteValidator extends Message
{
    protected object $addSiteModel;

    public function __construct(object $addSiteModel)
    {
        parent::__construct();
        $this->addSiteModel = $addSiteModel;
    }

    public function validate(
        string $user,
        string $password,
        string $name,
        string $www,
        ?int &$id
    ): void {
        $userPassword = $this->addSiteModel->getUserPassword($user, $id) ?? '';
        if (!password_verify($password, $userPassword)) {
            $this->addMessage('Błędna autoryzacja przesyłanych danych.');

            return;
        }
        if (strlen($name) < 1) {
            $this->addMessage(
                'Nazwa strony www musi zostać podana.'
            );
        } elseif (strlen($name) > 100) {
            $this->addMessage(
                'Nazwa strony www może zawierać maksymalnie 100 znaków.'
            );
        }
        if (
            substr($www, 0, 7) != 'http://'
            && substr($www, 0, 8) != 'https://'
        ) {
            $this->addMessage(
                'Url musi rozpoczynać się od znaków: http://'
            );
        }
        if (strlen($www) > 100) {
            $this->addMessage(
                'Url może zawierać maksymalnie 100 znaków.'
            );
        }
    }
}

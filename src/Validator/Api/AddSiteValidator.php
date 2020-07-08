<?php

declare(strict_types=1);

namespace App\Validator\Api;

use App\Bundle\Message;
use App\Repository\UserRepository;

class AddSiteValidator extends Message
{
    protected object $rm;

    public function __construct(object $rm)
    {
        parent::__construct();
        $this->rm = $rm;
    }

    public function validate(
        string $user,
        string $password,
        string $name,
        string $www
    ): void {
        $apiUserData = $this->rm->getRepository(UserRepository::class)
            ->getApiUserData($user);
        if (!password_verify($password, $apiUserData['user_password'] ?? '')) {
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
            substr($www, 0, 7) !== 'http://'
            && substr($www, 0, 8) !== 'https://'
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

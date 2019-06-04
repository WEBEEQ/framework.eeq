<?php declare(strict_types=1);

// src/Validator/Api/UpdateSiteValidator.php
namespace App\Validator\Api;

use App\Bundle\Message;

class UpdateSiteValidator extends Message
{
    protected $updateSiteModel;

    public function __construct(object $updateSiteModel)
    {
        parent::__construct();
        $this->updateSiteModel = $updateSiteModel;
    }

    public function validate(
        string $user,
        string $password,
        int $site,
        string $name
    ): void {
        $userPassword =
            $this->updateSiteModel->getUserPassword($user, $id) ?? '';
        if (!password_verify($password, $userPassword)) {
            $this->addMessage('Błędna autoryzacja przesyłanych danych.');

            return;
        }
        if (!$this->updateSiteModel->isUserSite((int) $id, $site)) {
            $this->addMessage(
                'Baza nie zawiera podanej strony dla autoryzacji.'
            );
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
    }
}

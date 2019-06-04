<?php declare(strict_types=1);

// src/Validator/Api/DeleteSiteValidator.php
namespace App\Validator\Api;

use App\Bundle\Message;

class DeleteSiteValidator extends Message
{
    protected $deleteSiteModel;

    public function __construct(object $deleteSiteModel)
    {
        parent::__construct();
        $this->deleteSiteModel = $deleteSiteModel;
    }

    public function validate(string $user, string $password, int $site): void
    {
        $userPassword =
            $this->deleteSiteModel->getUserPassword($user, $id) ?? '';
        if (!password_verify($password, $userPassword)) {
            $this->addMessage('Błędna autoryzacja przesyłanych danych.');

            return;
        }
        if (!$this->deleteSiteModel->isUserSite((int) $id, $site)) {
            $this->addMessage(
                'Baza nie zawiera podanej strony dla autoryzacji.'
            );
        }
    }
}

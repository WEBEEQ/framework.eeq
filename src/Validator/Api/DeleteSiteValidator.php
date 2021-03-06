<?php

declare(strict_types=1);

namespace App\Validator\Api;

use App\Bundle\Message;
use App\Repository\{SiteRepository, UserRepository};

class DeleteSiteValidator extends Message
{
    protected object $rm;

    public function __construct(object $rm)
    {
        parent::__construct();
        $this->rm = $rm;
    }

    public function validate(string $user, string $password, int $site): void
    {
        $apiUserData = $this->rm->getRepository(UserRepository::class)
            ->getApiUserData($user);
        if (!password_verify($password, $apiUserData['user_password'] ?? '')) {
            $this->addMessage('Błędna autoryzacja przesyłanych danych.');

            return;
        }
        $apiUserSite = $this->rm->getRepository(SiteRepository::class)
            ->isApiUserSite($apiUserData['user_id'], $site);
        if (!$apiUserSite) {
            $this->addMessage(
                'Baza nie zawiera podanej strony dla autoryzacji.'
            );
        }
    }
}

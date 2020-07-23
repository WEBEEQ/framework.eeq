<?php

declare(strict_types=1);

namespace App\Controller;

class UserPrivacyController
{
    public function userPrivacyAction(): array
    {
        return array(
            'content' => 'user-privacy/user-privacy.php',
            'activeMenu' => 'user-privacy',
            'title' => 'Prywatność'
        );
    }
}

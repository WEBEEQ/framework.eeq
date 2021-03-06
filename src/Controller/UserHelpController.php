<?php

declare(strict_types=1);

namespace App\Controller;

class UserHelpController
{
    public function userHelpAction(): array
    {
        return array(
            'content' => 'user-help/user-help.php',
            'activeMenu' => 'user-help',
            'title' => 'Pomoc'
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

class UserRegulationController
{
    public function userRegulationAction(): array
    {
        return array(
            'content' => 'src/View/user-regulation/user-regulation.php',
            'activeMenu' => 'user-regulation',
            'title' => 'Regulamin'
        );
    }
}

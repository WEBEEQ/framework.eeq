<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\{Database, Manager};

class Controller
{
    protected object $database;
    protected object $manager;

    public function setManager(): void
    {
        if (!isset($this->manager)) {
            $this->database = new Database();
            $this->database->dbConnect();
            $this->manager = new Manager($this->database);
        }
    }

    public function getManager(): object
    {
        $this->setManager();

        return $this->manager;
    }

    public function redirectToRoute(string $route): array
    {
        switch ($route) {
            case 'login_page':
                $path = '/logowanie';
                break;
            default:
                $path = '/';
                break;
        }

        return array('redirection' => true, 'path' => $path);
    }
}

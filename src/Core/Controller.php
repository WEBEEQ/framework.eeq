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
}

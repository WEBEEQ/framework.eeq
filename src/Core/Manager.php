<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Query;

class Manager
{
    protected object $database;
    protected array $repository;

    public function __construct(object $database)
    {
        $this->database = $database;
    }

    public function getRepository(string $class): object
    {
        $this->repository[$class] ??= new $class($this->database, $this);

        return $this->repository[$class];
    }

    public function createQuery(string $query): object
    {
        return new Query($query);
    }
}

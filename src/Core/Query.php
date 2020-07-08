<?php

declare(strict_types=1);

namespace App\Core;

class Query
{
    protected string $query;

    public function __construct(string $query)
    {
        $this->query = $query;
    }

    public function setParameter(string $search, $replace): object
    {
        $this->query = str_replace(
            ':' . $search,
            $this->prepare((string) $replace),
            $this->query
        );

        return $this;
    }

    public function getStrQuery(): string
    {
        return $this->query;
    }

    private function prepare(string $string): string
    {
        $string = trim($string);
        $string = addslashes($string);

        return $string;
    }
}

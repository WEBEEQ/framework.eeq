<?php

declare(strict_types=1);

namespace App\Core;

class Param
{
    public function getParameter(array $parameterList): string
    {
        $parameter = '';

        foreach ($parameterList as $key => $value) {
            $parameter .= ($key !== 'level') ? $key . '='
                . urlencode(stripslashes((string) $value)) . '&amp;' : '';
        }

        return $parameter;
    }

    public function prepareString(?string $string): string
    {
        $string = trim($string ?? '');
        $string = stripslashes($string);
        $string = str_replace('&#8208;', '-', $string);
        $string = str_replace('&#8209;', '-', $string);
        $string = str_replace('&#8210;', '-', $string);
        $string = str_replace('&#8211;', '-', $string);
        $string = str_replace('&#8216;', "'", $string);
        $string = str_replace('&#8217;', "'", $string);
        $string = str_replace('&#8218;', "'", $string);
        $string = str_replace('&#8219;', "'", $string);
        $string = str_replace('&#8220;', '"', $string);
        $string = str_replace('&#8221;', '"', $string);
        $string = str_replace('&#8222;', '"', $string);
        $string = str_replace('&#8223;', '"', $string);
        $string = str_replace('&#8228;', '.', $string);
        $string = str_replace('&#8229;', '..', $string);
        $string = str_replace('&#8230;', '...', $string);
        $string = htmlspecialchars($string);
        $string = addslashes($string);

        return $string;
    }

    public function preparePassString(?string $passString): string
    {
        $passString = str_replace(
            '&amp;',
            '&',
            $this->prepareString($passString)
        );

        return $passString;
    }

    public function prepareInt(?string $int, int $default = 0): int
    {
        return (int) ($int ?? $default);
    }

    public function prepareIntBool(?string $intBool): int
    {
        return (int) (bool) $intBool;
    }

    public function prepareBool(?string $bool): bool
    {
        return (bool) $bool;
    }
}

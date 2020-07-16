<?php

declare(strict_types=1);

namespace App\Core;

class Preparation
{
    public function prepare(array $array): array
    {
        foreach ($array as $key => $value) {
            if (
                $key !== 'error'
                && $key !== 'message'
                && $key !== 'pageNavigator'
                && is_string($value)
            ) {
                $array[$key] = htmlspecialchars($value);
            } elseif (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    if (is_string($value2)) {
                        $array[$key][$key2] = htmlspecialchars($value2);
                    } elseif (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            if (is_string($value3)) {
                                $array[$key][$key2][$key3] =
                                    htmlspecialchars($value3);
                            }
                        }
                    }
                }
            }
        }

        return $array;
    }
}

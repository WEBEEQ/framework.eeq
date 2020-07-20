<?php

declare(strict_types=1);

namespace App\Bundle;

class Html
{
    public function prepareError(?array $array): string
    {
        $error = '';

        if ($array) {
            $error .= '<ul>';
            foreach ($array as $key => $value) {
                $error .= '<li>' . htmlspecialchars($value) . '</li>';
            }
            $error .= '</ul>' . "\n";
        }

        return $error;
    }

    public function prepareMessage(string $message, bool $ok): string
    {
        return ($message !== '') ? '<p class="' . (($ok) ? 'ok' : 'bad') . '">'
            . str_replace("\n", '<br />', htmlspecialchars($message)) . '</p>'
            . "\n" : '';
    }

    public function preparePageNavigator(
        string $url,
        int $level,
        int $listLimit,
        int $count,
        int $levelLimit
    ): string {
        $pageNavigator = '';

        if ($count > $listLimit) {
            $minLevel = 1;
            $maxLevel = number_format($count / $listLimit, 0, '.', '');
            $number = number_format($count / $listLimit, 2, '.', '');
            $maxLevel = ($number > $maxLevel) ? $maxLevel + 1 : $maxLevel;
            $number = $level - $levelLimit;
            $fromLevel = ($number < $minLevel) ? $minLevel : $number;
            $number = $level + $levelLimit;
            $toLevel = ($number > $maxLevel) ? $maxLevel : $number;
            $previousLevel = $level - 1;
            $nextLevel = $level + 1;
            $url = htmlspecialchars($url);
            if ($maxLevel > $levelLimit) {
                $pageNavigator .= ($level > $minLevel) ? '<a href="' . $url
                    . $minLevel . '">...</a>' : '';
            }
            $pageNavigator .= ($level > $minLevel) ? '<a href="' . $url
                . $previousLevel . '">&nbsp;&laquo&nbsp;</a>' : '';
            for ($i = $fromLevel; $i <= $toLevel; $i++) {
                $pageNavigator .= ($i !== $level) ? '<a href="' . $url
                    . $i . '">&nbsp;' . $i . '&nbsp;</a>' : '[' . $i . ']';
            }
            $pageNavigator .= ($level < $maxLevel) ? '<a href="' . $url
                . $nextLevel . '">&nbsp;&raquo;&nbsp;</a>' : '';
            if ($maxLevel > $levelLimit) {
                $pageNavigator .= ($level < $maxLevel) ? '<a href="' . $url
                    . $maxLevel . '">...</a>' : '';
            }
        }

        return $pageNavigator;
    }

    public function prepareData(array $array): array
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

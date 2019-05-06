<?php
declare(strict_types = 1);

// src/Bundle/Html.php
namespace AppBundle\Bundle;

class Html
{
    public static function prepareMessage(string $message, bool $ok): string
    {
        return ($message != '') ? '                <p class="' . (($ok) ? 'ok' : 'bad')
            . '">' . str_replace("\r\n", '<br />', $message) . '</p>' . "\r\n" : '';
    }

    public static function preparePageNavigator(
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
            $maxLevel = (number_format($count / $listLimit, 2, '.', '') > $maxLevel) ? $maxLevel + 1 : $maxLevel;
            $fromLevel = ($level - $levelLimit < $minLevel) ? $minLevel : $level - $levelLimit;
            $toLevel = ($level + $levelLimit > $maxLevel) ? $maxLevel : $level + $levelLimit;
            $previousLevel = $level - 1;
            $nextLevel = $level + 1;
            if ($maxLevel > $levelLimit) {
                $pageNavigator .= ($level > $minLevel) ? '<a href="' . $url
                    . $minLevel . '">...</a>' : '';
            }
            $pageNavigator .= ($level > $minLevel) ? '<a href="' . $url
                . $previousLevel . '">&nbsp;&laquo&nbsp;</a>' : '';
            for ($i = $fromLevel; $i <= $toLevel; $i++) {
                $pageNavigator .= ($i != $level) ? '<a href="' . $url
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
}

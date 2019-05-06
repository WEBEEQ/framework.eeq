<?php
declare(strict_types = 1);

// src/Core/Cache.php
namespace AppBundle\Core;

class Cache
{
    public static function cachePage(string $url, array $arrayData, string $initialFile, string $finalFile): void
    {
        ob_start();
        include($initialFile);
        $content = ob_get_contents();
        ob_end_clean();
        if (file_exists($finalFile)) {
            $file = fopen($finalFile, "r+");
        } else {
            $file = fopen($finalFile, "w");
        }
        if ($file) {
            if (flock($file, LOCK_EX)) {
                ftruncate($file, 0);
                fwrite($file, $content);
                flock($file, LOCK_UN);
            }
            fclose($file);
        }
    }
}

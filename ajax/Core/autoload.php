<?php
spl_autoload_register(function ($class) {
    $directory = $_SERVER['DOCUMENT_ROOT'] . '/ajax/';
    $prefix = 'Ajax\\';
    $length = strlen($prefix);

    if (strncmp($class, $prefix, $length) !== 0) {
        return;
    }

    $relativeClass = substr($class, $length);
    $file = $directory . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once($file);
    }
});

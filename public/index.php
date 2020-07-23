<?php

declare(strict_types=1);

require('../config/config.php');
require('../src/autoload.php');
require('../vendor/autoload.php');

use App\Bundle\Html;
use App\Core\{Config, CookieLogin};

$config = new Config();
$cookieLogin = new CookieLogin($config);
$cookieLogin->setCookieLogin();

$appSettings = require(__DIR__ . '/../config/settings.php');
$settings = $appSettings[$_GET['action']];

switch ($settings['role']) {
    case 'user':
        if (!$_SESSION['user']) {
            header('Location: ' . $config->getUrl() . '/logowanie');
            exit;
        }
        break;
    case 'admin':
        if (!$_SESSION['admin']) {
            header('Location: ' . $config->getUrl() . '/logowanie');
            exit;
        }
        break;
    default:
        break;
}

switch ($settings['option']) {
    case 'page':
        $class = 'App\\Controller\\' . $settings['name'] . 'Controller';
        $method = $settings['name'] . 'Action';

        $controller = new $class();
        $array = $controller->$method($_REQUEST, $_SESSION);
        break;
    case 'ajax':
        $class = 'App\\Controller\\Ajax\\' . $settings['name'] . 'Controller';
        $method = $settings['name'] . 'Action';

        $controller = new $class();
        $array = $controller->$method($_REQUEST);
        break;
    case 'api':
        $class = 'App\\Controller\\Api\\' . $settings['name'] . 'Controller';
        $method = $settings['name'] . 'Action';

        $controller = new $class();
        $array = $controller->$method(
            $_SERVER,
            json_decode(file_get_contents('php://input'), true)
        );

        if ($array['redirection']) break;
        echo json_encode($array);
        exit;
    default:
        $array = array();

        $array['layout'] = 'layout/main/main.php';
        $array['content'] = 'default/default.php';
        $array['activeMenu'] = '';
        $array['title'] = 'Pusta strona';
        break;
}

if ($array['redirection']) {
    header('Location: ' . $config->getUrl() . $array['path']);
    exit;
}

$array['url'] = $config->getUrl();

$html = new Html();
$array = $html->prepareData($array);

if ($settings['option'] === 'ajax') {
    include('../templates/' . $array['content']);
} else {
    include('../templates/' . ($array['layout'] ?? 'layout/main/main.php'));
}

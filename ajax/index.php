<?php
declare(strict_types = 1);

require('../src/Core/core-ajax.php');

use AppBundle\Controller\AjaxCityController;

switch ($_GET['option']) {
    case 'place':
        $province = (is_numeric($_GET['province']) && $_GET['province'] >= 1) ? (int) $_GET['province'] : 0;

        $content = '../src/View/ajax-city.php';
        $ajaxCityController = new AjaxCityController();
        $array = $ajaxCityController->ajaxCityAction($province);
        break;
    default:
        $content = '';
        $array = null;
        break;
}

include($content);

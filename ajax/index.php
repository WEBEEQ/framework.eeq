<?php
declare(strict_types = 1);

require('../ajax/Core/core.php');

use Ajax\Controller\CityListController;
use AppBundle\Core\Param;

switch ($_GET['option']) {
    case 'city-list':
        $province = Param::prepareInt($_GET['province']);

        $content = '../ajax/View/city-list.php';
        $cityListController = new CityListController();
        $array = $cityListController->cityListAction($province);
        break;
    default:
        $content = '';
        $array = null;
        break;
}

include($content);

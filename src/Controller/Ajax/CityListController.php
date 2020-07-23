<?php

declare(strict_types=1);

namespace App\Controller\Ajax;

use App\Core\Controller;
use App\Repository\CityRepository;

class CityListController extends Controller
{
    public function cityListAction(array $request): array
    {
        $rm = $this->getManager();

        $cityList = $rm->getRepository(CityRepository::class)
            ->getCityList((int) $request['province']);

        return array(
            'content' => 'ajax/city-list.php',
            'cityList' => $cityList
        );
    }
}

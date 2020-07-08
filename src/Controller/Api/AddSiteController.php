<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\{Config, Controller};
use App\Service\Api\AddSiteService;
use App\Validator\Api\AddSiteValidator;

class AddSiteController extends Controller
{
    public function addSiteAction(array $server, array $data): array
    {
        $config = new Config();
        $addSiteValidator = new AddSiteValidator($this->getManager());

        $addSiteService = new AddSiteService(
            $this,
            $config,
            $addSiteValidator
        );
        $message = $addSiteService->addSiteMessage(
            (string) $server['PHP_AUTH_USER'],
            (string) $server['PHP_AUTH_PW'],
            (string) $data['name'],
            (string) $data['www']
        );

        return array(
            'message' => $message->getStrMessage(),
            'success' => $message->getOk()
        );
    }
}

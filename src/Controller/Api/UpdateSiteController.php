<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\{Config, Controller};
use App\Service\Api\UpdateSiteService;
use App\Validator\Api\UpdateSiteValidator;

class UpdateSiteController extends Controller
{
    public function updateSiteAction(array $server, array $data): array
    {
        $config = new Config();
        $updateSiteValidator = new UpdateSiteValidator($this->getManager());

        $updateSiteService = new UpdateSiteService(
            $this,
            $config,
            $updateSiteValidator
        );
        $message = $updateSiteService->updateSiteMessage(
            (string) $server['PHP_AUTH_USER'],
            (string) $server['PHP_AUTH_PW'],
            (int) $data['id'],
            (string) $data['name'],
            (int) (bool) $data['visible']
        );

        return array(
            'success' => $message->getOk(),
            'message' => $message->getStrMessage()
        );
    }
}

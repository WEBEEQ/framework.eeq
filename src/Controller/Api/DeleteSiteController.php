<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\Controller;
use App\Service\Api\DeleteSiteService;
use App\Validator\Api\DeleteSiteValidator;

class DeleteSiteController extends Controller
{
    public function deleteSiteAction(array $server, array $data): array
    {
        $deleteSiteValidator = new DeleteSiteValidator($this->getManager());

        $deleteSiteService = new DeleteSiteService(
            $this,
            $deleteSiteValidator
        );
        $message = $deleteSiteService->deleteSiteMessage(
            (string) $server['PHP_AUTH_USER'],
            (string) $server['PHP_AUTH_PW'],
            (int) $data['id']
        );

        return array(
            'success' => $message->getOk(),
            'message' => $message->getStrMessage()
        );
    }
}

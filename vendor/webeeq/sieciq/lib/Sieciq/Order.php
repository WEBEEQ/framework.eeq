<?php declare(strict_types=1);

namespace Library\Sieciq;

use Library\Sieciq\{Config, Http};

class Order
{
    public static function addSite(array $auth, array $data): array
    {
        $response = array();

        try {
            $response = Http::doPost(
                Config::getAddSitePathUrl(),
                $auth,
                $data
            );
        } catch (SieciqException $e) {
            $response['response']['message'] = $e->getMessage();
        }

        return $response;
    }

    public static function updateSite(array $auth, array $data): array
    {
        $response = array();

        try {
            $response = Http::doPut(
                Config::getUpdateSitePathUrl(),
                $auth,
                $data
            );
        } catch (SieciqException $e) {
            $response['response']['message'] = $e->getMessage();
        }

        return $response;
    }

    public static function deleteSite(array $auth, array $data): array
    {
        $response = array();

        try {
            $response = Http::doDelete(
                Config::getDeleteSitePathUrl(),
                $auth,
                $data
            );
        } catch (SieciqException $e) {
            $response['response']['message'] = $e->getMessage();
        }

        return $response;
    }
}

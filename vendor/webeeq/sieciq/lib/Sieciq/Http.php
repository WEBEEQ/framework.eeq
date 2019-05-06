<?php declare(strict_types=1);

namespace Library\Sieciq;

use Library\Sieciq\HttpCurl;

class Http
{
    public static function doGet(
        string $pathUrl,
        array $auth
    ): array {
        $response = HttpCurl::doRequest('GET', $pathUrl, $auth);

        return $response;
    }

    public static function doPost(
        string $pathUrl,
        array $auth,
        array $data
    ): array {
        $response = HttpCurl::doRequest('POST', $pathUrl, $auth, $data);

        return $response;
    }

    public static function doPut(
        string $pathUrl,
        array $auth,
        array $data
    ): array {
        $response = HttpCurl::doRequest('PUT', $pathUrl, $auth, $data);

        return $response;
    }

    public static function doDelete(
        string $pathUrl,
        array $auth,
        array $data
    ): array {
        $response = HttpCurl::doRequest('DELETE', $pathUrl, $auth, $data);

        return $response;
    }
}

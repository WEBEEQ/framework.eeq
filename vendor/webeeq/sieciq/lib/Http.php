<?php declare(strict_types=1);

namespace Webeeq\Sieciq;

use Webeeq\Sieciq\HttpCurl;

class Http extends HttpCurl
{
    public function doGet(
        string $pathUrl,
        array $auth
    ): array {
        $response = $this->doRequest('GET', $pathUrl, $auth);

        return $response;
    }

    public function doPost(
        string $pathUrl,
        array $auth,
        array $data
    ): array {
        $response = $this->doRequest('POST', $pathUrl, $auth, $data);

        return $response;
    }

    public function doPut(
        string $pathUrl,
        array $auth,
        array $data
    ): array {
        $response = $this->doRequest('PUT', $pathUrl, $auth, $data);

        return $response;
    }

    public function doDelete(
        string $pathUrl,
        array $auth,
        array $data
    ): array {
        $response = $this->doRequest('DELETE', $pathUrl, $auth, $data);

        return $response;
    }
}

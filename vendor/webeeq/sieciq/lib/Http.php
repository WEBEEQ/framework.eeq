<?php

declare(strict_types=1);

namespace Webeeq\Sieciq;

use Webeeq\Sieciq\HttpCurl;

class Http extends HttpCurl
{
    public function doGet(string $pathUrl, array $auth): array
    {
        return $this->doRequest('GET', $pathUrl, $auth);
    }

    public function doPost(string $pathUrl, array $auth, array $data): array
    {
        return $this->doRequest('POST', $pathUrl, $auth, $data);
    }

    public function doPut(string $pathUrl, array $auth, array $data): array
    {
        return $this->doRequest('PUT', $pathUrl, $auth, $data);
    }

    public function doDelete(string $pathUrl, array $auth, array $data): array
    {
        return $this->doRequest('DELETE', $pathUrl, $auth, $data);
    }
}

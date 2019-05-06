<?php declare(strict_types=1);

namespace Webeeq\Sieciq;

use Webeeq\Sieciq\SieciqException;

class HttpCurl
{
    public function doRequest(
        string $requestType,
        string $pathUrl,
        array $auth,
        array $data
    ): array {
        if (empty($pathUrl)) {
            throw new SieciqException('The endpoint is empty');
        }

        if (empty($auth)) {
            throw new SieciqException('No auth to set');
        }

        if (empty($data)) {
            throw new SieciqException('No data to send');
        }

        $ch = curl_init($pathUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        curl_setopt($ch, CURLOPT_USERPWD,
            $auth['user'] . ':' . $auth['password']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            throw new SieciqException(curl_error($ch));
        }

        curl_close($ch);

        return array(
            'code' => $httpStatus,
            'response' => json_decode($response, true)
        );
    }
}

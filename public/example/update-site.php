<?php

declare(strict_types=1);

require('config.php');
require('../../vendor/webeeq/sieciq/autoload.php');

use Webeeq\Sieciq\{Config, Order};

$auth = array();
$data = array();

$auth['user'] = 'user';
$auth['password'] = '!@#$%^&*()';

$data['id'] = 8;
$data['name'] = 'Fachowcy';
$data['visible'] = 1;

$config = new Config();
$order = new Order($config);

try {
    $response = $order->updateSite($auth, $data);
} catch (SieciqException $e) {
    echo $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8" />
        <title>Update Site</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <div class="page-header">
                <h1>Update Site</h1>
            </div>
<?php
if ($response['code'] === 200 && $response['response']['success']) {
?>
            <div class="alert alert-success">SUCCESS</div>
<?php
} else {
?>
            <div class="alert alert-danger">DEFEAT</div>
<?php
}

if ($response['response']['message']) {
?>
            <pre><?= str_replace("\n", '<br />', htmlspecialchars($response['response']['message'])) ?></pre>
<?php
}
?>
            <h1>Request</h1>
            <div>
<?php
var_dump($data);
?>
            </div>
            <h1>Response</h1>
            <div>
<?php
var_dump($response);
?>
            </div>
        </div>
    </body>
</html>

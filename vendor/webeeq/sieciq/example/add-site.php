<?php declare(strict_types=1);

require('config.php');
require('../autoload.php');

use Webeeq\Sieciq\{Config, Order};

$auth = array();
$data = array();

$auth['user'] = 'user';
$auth['password'] = '!@#$%^&*()';

$data['name'] = 'Nasz Fach';
$data['www'] = 'http://www.naszfach.pl';

$config = new Config();
$order = new Order($config);
$response = $order->addSite($auth, $data);
?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8" />
        <title>Add Site</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <div class="page-header">
                <h1>Add Site</h1>
            </div>
<?php
if ($response['code'] == 200 && $response['response']['success']) {
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
            <pre><?php echo str_replace("\r\n", '<br />', $response['response']['message']); ?></pre>
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

<?php

require_once('../config/config.php');

$code = (int) ($_GET['code'] ?? 404);
?>
<!DOCTYPE html>
<?php ob_start(); ?>
<html lang="en-US">
    <head>
        <meta charset="utf-8" />
        <title>Error <?= $code ?></title>
        <?php if ($code === 403 || $code === 404) { ?>
            <meta name="robots" content="noindex, nofollow" />
        <?php } ?>
        <style type="text/css">
            body {
                margin: 30px;
                padding: 0;
                border: 0;
                background: #FFFFFF;
            }

            h1, a, p {
                width: auto;
                height: auto;
                margin: 0;
                padding: 0;
                border: 0;
                background: transparent;
                color: #000000;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 20px;
                font-style: normal;
                font-weight: bold;
                text-decoration: none;
                text-transform: uppercase;
                text-align: center;
                line-height: 22px;
                letter-spacing: 0;
            }

            h1, h1 a {
                margin-bottom: 6px;
                font-size: 40px;
                line-height: 38px;
            }

            a:hover {
                color: #FF0000;
            }

            p {
                margin-bottom: 4px;
            }
        </style>
    </head>
    <body>
        <h1>Error <?= $code ?></h1>
        <p><a href="/">Back to the main page</a></p>
    </body>
</html>
<?php ob_end_flush(); ?>

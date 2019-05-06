<?php
require_once('src/Config/config.php');

$name = ($_GET['name'] != '') ? $_GET['name'] : '404';
?>
<!DOCTYPE html>
<?php
ob_start();
?>
<html lang="en-US">
    <head>
        <meta charset="UTF-8" />
        <title>Error <?php echo $name; ?></title>
<?php
if ($name == '403' || $name == '404') {
?>
        <meta name="robots" content="noindex, nofollow" />
<?php
}
?>
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
        <h1>Error <?php echo $name; ?></h1>
        <p><a href="<?php echo $url; ?>/">Back to the main page</a></p>
    </body>
</html>
<?php
ob_end_flush();
?>

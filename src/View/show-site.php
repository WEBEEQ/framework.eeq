<!DOCTYPE html>
<?php
ob_start();
?>
<html lang="pl-PL">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <title><?php echo $title; ?></title>
        <link rel="icon" type="image/x-icon" href="<?php echo $url; ?>/image/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/css/style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/css/rwd.css" />
    </head>
    <body>
        <div id="menu">
            <nav>
<?php
include('src/Layout/menu.php');
?>
            </nav>
        </div>
        <div id="header">
            <header>
<?php
include('src/Layout/header.php');
?>
            </header>
        </div>
        <iframe id="frame" src="<?php echo $array['www']; ?>"></iframe>
        <div id="footer">
            <footer>
<?php
include('src/Layout/footer.php');
?>
            </footer>
        </div>
        <script type="text/javascript" src="<?php echo $url; ?>/js/jquery.js"></script>
        <script type="text/javascript" src="<?php echo $url; ?>/js/script.js"></script>
    </body>
</html>
<?php
ob_end_flush();
?>

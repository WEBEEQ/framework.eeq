<!DOCTYPE html>
<?php
ob_start();
?>
<html lang="pl-PL">
    <head>
<?php
include('head.php');
?>
    </head>
    <body>
        <div id="menu">
            <nav>
<?php
include('menu.php');
?>
            </nav>
        </div>
        <div id="header">
            <header>
<?php
include('header.php');
?>
            </header>
        </div>
<?php
include($array['content']);
?>
        <div id="footer">
            <footer>
<?php
include('footer.php');
?>
            </footer>
        </div>
<?php
include('foot.php');
?>
    </body>
</html>
<?php
ob_end_flush();
?>

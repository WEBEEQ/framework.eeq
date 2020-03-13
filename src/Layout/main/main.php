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
        <div id="slider">
<?php
include('slider.php');
?>
        </div>
        <div id="content">
            <section>
<?php
include($array['content']);
?>
            </section>
        </div>
        <div id="bottombar">
            <aside>
<?php
include('bottombar.php');
?>
            </aside>
        </div>
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

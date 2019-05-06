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
        <div id="content">
            <section>
<?php
include($array['content']);
?>
            </section>
        </div>
    </body>
</html>
<?php
ob_end_flush();
?>

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
        <div id="content">
            <section>
                <p class="info">Osiągnięto maksymalną liczbę możliwych do wykonania wyświetleń stron innych użytkowników systemu. Jest ona równa ilości aktywnych stron w portalu. Dalsze odsłony stron nie będą możliwe, dopóki inni użytkownicy systemu nie odwiedzą Państwa stron. Doradzamy zajrzeć tu ponownie dopiero jutro.</p>
            </section>
        </div>
    </body>
</html>
<?php
ob_end_flush();
?>

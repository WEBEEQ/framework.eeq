<?php
$value = $array['userData'];
?>
                <p class="info">Znajdujesz się na stronie rankingu osoby <?php echo $value['user_name']; ?> <?php echo $value['user_surname']; ?>. Możesz tu znaleźć kilka użytecznych informacji w opisie, region pochodzenia oraz pozycję w rankingu. Aby wpłynąć na pozycję w rankingu, jaką zajmuje <?php echo $value['user_name']; ?> <?php echo $value['user_surname']; ?>, kliknij przycisk Like it! Na stronie głównej znajduje się Top Lista wszystkich osób, które są dodane do rankingu.</p>
                <h2>Dane osoby</h2>
                <table class="form">
                    <tr><td class="description">Imię:</td><td><?php echo $value['user_name']; ?></td><td class="ranking" rowspan="6">Ranking:<br /><?php echo $value['user_ranking']; ?><form method="post"><input class="link" type="submit" name="like" value="Like it!" /></form></td></tr>
                    <tr><td class="description">Nazwisko:</td><td><?php echo $value['user_surname']; ?></td></tr>
                    <tr><td class="description">Województwo:</td><td><?php echo $value['province_name']; ?></td></tr>
                    <tr><td class="description">Miasto:</td><td><?php echo $value['city_name']; ?></td></tr>
                    <tr><td class="description">Opis:</td><td><?php echo str_replace("\r", '<br />', str_replace("\n", '<br />', str_replace("\r\n", '<br />', $value['user_description']))); ?></td></tr>
                    <tr><td>&nbsp;</td><td><input class="link" type="button" name="button" value="Zmień" onclick="document.location = '/kontakt';" /></td></tr>
                </table>
<?php
if (file_exists($array['cacheFile'])) {
    include($array['cacheFile']);
}
?>

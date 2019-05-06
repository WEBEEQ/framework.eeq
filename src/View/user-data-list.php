                <h2>Powiązane osoby</h2>
                <table class="list">
                    <tr class="head">
                        <td>Imię</td>
                        <td>Nazwisko</td>
                        <td>Województwo</td>
                        <td>Miasto</td>
                        <td>Ranking</td>
                    </tr>
<?php
if (!$arrayData) {
?>
                    <tr>
                        <td colspan="5">Brak osób do wyświetlenia!</td>
                    </tr>
<?php
} else {
    foreach ($arrayData as $key => $value) {
?>
                    <tr onmouseover="swapClass(this, 'choose');" onmouseout="swapClass(this, 'row');">
                        <td><a href="<?php echo $url; ?>/osoba,<?php echo $key; ?>"><?php echo $value['user_name']; ?></a></td>
                        <td><a href="<?php echo $url; ?>/osoba,<?php echo $key; ?>"><?php echo $value['user_surname']; ?></a></td>
                        <td><?php echo ($value['province_name']) ? $value['province_name'] : 'Brak'; ?></td>
                        <td><?php echo ($value['city_name']) ? $value['city_name'] : 'Brak'; ?></td>
                        <td><?php echo $value['user_ranking']; ?></td>
                    </tr>
<?php
    }
}
?>
                </table>

                <p class="info">Witaj na Top Liście osób. Znajdują się na niej nazwiska Polaków z całego kraju. Masz możliwość dodawania dowolnych osób. Są one sortowane na podstawie rankingu popularności. Możesz wpływać na pozycję w rankingu klikając Like it! Życzymy miłej zabawy w typowanie najpopularniejszych osób w Polsce.</p>
                <h2>Top osoby</h2>
                <table class="list">
                    <tr class="head">
                        <td class="number">Lp</td>
                        <td>Imię</td>
                        <td>Nazwisko</td>
                        <td>Województwo</td>
                        <td>Miasto</td>
                        <td>Ranking</td>
                    </tr>
<?php
if (!$array['userList']) {
?>
                    <tr>
                        <td colspan="6">Brak osób do wyświetlenia!</td>
                    </tr>
<?php
} else {
    $i = ($level - 1) * $array['listLimit'];

    foreach ($array['userList'] as $key => $value) {
?>
                    <tr onmouseover="swapClass(this, 'choose');" onmouseout="swapClass(this, 'row');">
                        <td class="number"><?php echo ++$i; ?>.</td>
                        <td><a href="<?php echo $url; ?>/osoba,<?php echo $key; ?>"><?php echo $value['user_name']; ?></a></td>
                        <td><a href="<?php echo $url; ?>/osoba,<?php echo $key; ?>"><?php echo $value['user_surname']; ?></a></td>
                        <td><?php echo ($value['province_name']) ? $value['province_name'] : 'Brak'; ?></td>
                        <td><?php echo ($value['city_name']) ? $value['city_name'] : 'Brak'; ?></td>
                        <td><?php echo $value['user_ranking']; ?></td>
                    </tr>
<?php
    }
}

if ($array['pageNavigator']) {
?>
                    <tr>
                        <td colspan="6"><?php echo $array['pageNavigator']; ?></td>
                    </tr>
<?php
}
?>
                </table>

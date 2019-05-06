                <h2>Szukanie osób</h2>
                <form method="get">
                    <table class="form">
                        <tr><td class="description">Imię:</td><td><input type="text" name="name" value="<?php echo stripslashes($name); ?>" size="50" maxlength="50" /></td></tr>
                        <tr><td class="description">Nazwisko:</td><td><input type="text" name="surname" value="<?php echo stripslashes($surname); ?>" size="50" maxlength="100" /></td></tr>
                        <tr>
                            <td class="description">Województwo:</td>
                            <td>
                                <select name="province" onchange="ajaxData('select1', '<?php echo $url; ?>/ajax/miejsce,' + this.value);">
                                    <option value="0">&nbsp;</option>
<?php
foreach ($array['provinceList'] as $key => $value) {
?>
                                    <option value="<?php echo $key; ?>"<?php if ($key == $province) { ?> selected="selected"<?php } ?>><?php echo $value['province_name']; ?></option>
<?php
}
?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="description">Miasto:</td>
                            <td id="select1">
                                <select name="city">
                                    <option value="0">&nbsp;</option>
<?php
foreach ($array['cityList'] as $key => $value) {
?>
                                    <option value="<?php echo $key; ?>"<?php if ($key == $city) { ?> selected="selected"<?php } ?>><?php echo $value['city_name']; ?></option>
<?php
}
?>
                                </select>
                            </td>
                        </tr>
                        <tr><td>&nbsp;</td><td><input type="submit" name="submit" value="Zatwierdź" /> <input type="reset" name="reset" value="Wyczyść" /></td></tr>
                    </table>
                </form>
                <h2>Lista osób</h2>
                <table class="list">
                    <tr class="head">
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
                        <td colspan="5">Brak osób do wyświetlenia!</td>
                    </tr>
<?php
} else {
    foreach ($array['userList'] as $key => $value) {
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

if ($array['pageNavigator']) {
?>
                    <tr>
                        <td colspan="5"><?php echo $array['pageNavigator']; ?></td>
                    </tr>
<?php
}
?>
                </table>

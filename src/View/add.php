                <p class="info">Podaj najlepiej pełne dane i opis osoby, którą chcesz umieścić w rankingu. Jeśli wolisz, ogranicz się do samego imienia i nazwiska, które są wymagane, aby dodać nowy wpis. Nie używaj słów wulgarnych i nieprzyzwoitych! Dodaj opis osoby wyłącznie zgodny z prawdą i polskim prawem. Dane osób niezgodne z tymi wytycznymi będą usuwane.</p>
                <h2>Dodawanie osoby</h2>
<?php
echo $array['message'];
?>
                <form method="post">
                    <table class="form">
                        <tr><td class="description">Imię:</td><td><input type="text" name="name" value="<?php echo stripslashes($array['name']); ?>" size="50" maxlength="50" /></td></tr>
                        <tr><td class="description">Nazwisko:</td><td><input type="text" name="surname" value="<?php echo stripslashes($array['surname']); ?>" size="50" maxlength="100" /></td></tr>
                        <tr>
                            <td class="description">Województwo:</td>
                            <td>
                                <select name="province" onchange="ajaxData('select1', '<?php echo $url; ?>/ajax/miejsce,' + this.value);">
                                    <option value="0">&nbsp;</option>
<?php
foreach ($array['provinceList'] as $key => $value) {
?>
                                    <option value="<?php echo $key; ?>"<?php if ($key == $array['province']) { ?> selected="selected"<?php } ?>><?php echo $value['province_name']; ?></option>
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
                                    <option value="<?php echo $key; ?>"<?php if ($key == $array['city']) { ?> selected="selected"<?php } ?>><?php echo $value['city_name']; ?></option>
<?php
}
?>
                                </select>
                            </td>
                        </tr>
                        <tr><td class="description">Opis:</td><td><textarea name="description" cols="47" rows="8"><?php echo stripslashes($array['description']); ?></textarea></td></tr>
                        <tr><td>&nbsp;</td><td><input type="submit" name="submit" value="Zatwierdź" /> <input type="reset" name="reset" value="Wyczyść" /></td></tr>
                    </table>
                </form>

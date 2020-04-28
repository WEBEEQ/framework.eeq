<h2>Edycja użytkownika</h2>
<p>Proszę podać możliwie najpełniejsze dane, aby zapewnić jak najlepszy kontakt. Zmiana adresu e-mail spowoduje konieczność ponownej aktywacji konta. Jeśli nie chcą Państwo zmieniać adresu e-mail, proszę pozostawić dwa pola dotyczące zmiany adresu e-mail puste. Jeśli nie chcą Państwo zmieniać hasła, proszę pozostawić trzy pola dotyczące zmiany hasła puste.</p>
<form method="post">
    <?= $array['error'] ?>
    <p>Dane użytkownika:</p>
    <table>
        <tr>
            <td>Imię:</td>
            <td><input type="text" name="name" value="<?= stripslashes($array['name']) ?>" size="30" maxlength="30" /></td>
        </tr>
        <tr>
            <td>Nazwisko:</td>
            <td><input type="text" name="surname" value="<?= stripslashes($array['surname']) ?>" size="50" maxlength="50" /></td>
        </tr>
        <tr>
            <td>Ulica:</td>
            <td><input type="text" name="street" value="<?= stripslashes($array['street']) ?>" size="60" maxlength="60" /></td>
        </tr>
        <tr>
            <td>Kod pocztowy:</td>
            <td><input type="text" name="postcode" value="<?= stripslashes($array['postcode']) ?>" size="6" maxlength="6" /></td>
        </tr>
        <tr>
            <td>Województwo:</td>
            <td>
                <select name="province" onchange="ajaxData('select1', '<?= $array['url'] ?>/ajax/miejsce,' + this.value);">
                    <option value="0">&nbsp;</option>
                    <?php foreach ($array['provinceList'] as $key => $value) { ?>
                        <option value="<?= $key ?>"<?php if ($key === $array['province']) { ?> selected="selected"<?php } ?>><?= $value['province_name'] ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Miasto:</td>
            <td id="select1">
                <select name="city">
                    <option value="0">&nbsp;</option>
                    <?php foreach ($array['cityList'] as $key => $value) { ?>
                        <option value="<?= $key ?>"<?php if ($key === $array['city']) { ?> selected="selected"<?php } ?>><?= $value['city_name'] ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Telefon:</td>
            <td><input type="text" name="phone" value="<?= stripslashes($array['phone']) ?>" size="20" maxlength="20" /></td>
        </tr>
        <tr>
            <td>E-mail:</td>
            <td><input type="text" name="email" value="<?= stripslashes($array['email']) ?>" size="50" maxlength="100" readonly="readonly" /></td>
        </tr>
        <tr>
            <td>Strona www:</td>
            <td><input type="text" name="www" value="<?= stripslashes($array['www']) ?>" size="50" maxlength="100" /></td>
        </tr>
        <tr>
            <td>Opis:</td>
            <td><textarea name="description" cols="47" rows="8"><?= stripslashes($array['description']) ?></textarea></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" value="Zatwierdź" /> <input type="reset" name="reset" value="Wyczyść" /></td>
        </tr>
    </table>
    <p>Zmień e-mail:</p>
    <table>
        <tr>
            <td>Nowy e-mail:</td>
            <td><input type="text" name="new_email" value="" size="50" maxlength="100" /></td>
        </tr>
        <tr>
            <td>Powtórz e-mail:</td>
            <td><input type="text" name="repeat_email" value="" size="50" maxlength="100" /></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" value="Zatwierdź" /> <input type="reset" name="reset" value="Wyczyść" /></td>
        </tr>
    </table>
    <p>Zmień hasło:</p>
    <table>
        <tr>
            <td>Login:</td>
            <td><input type="text" name="login" value="<?= stripslashes($array['login']) ?>" size="20" maxlength="20" readonly="readonly" /></td>
        </tr>
        <tr>
            <td>Stare hasło:</td>
            <td><input type="password" name="password" value="" size="30" maxlength="30" /></td>
        </tr>
        <tr>
            <td>Nowe hasło:</td>
            <td><input type="password" name="new_password" value="" size="30" maxlength="30" /></td>
        </tr>
        <tr>
            <td>Powtórz hasło:</td>
            <td><input type="password" name="repeat_password" value="" size="30" maxlength="30" /></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" value="Zatwierdź" /> <input type="reset" name="reset" value="Wyczyść" /></td>
        </tr>
    </table>
    <input type="hidden" name="token" value="<?= $array['token'] ?>" />
</form>

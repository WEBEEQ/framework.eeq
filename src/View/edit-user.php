                <h2>Edycja użytkownika</h2>
                <p>Proszę podać możliwie najpełniejsze dane, aby zapewnić jak najlepszy kontakt. Zmiana adresu e-mail spowoduje konieczność ponownej aktywacji konta. Jeśli nie chcą Państwo zmieniać adresu e-mail, proszę pozostawić dwa pola dotyczące zmiany adresu e-mail puste. Jeśli nie chcą Państwo zmieniać hasła, proszę pozostawić trzy pola dotyczące zmiany hasła puste.</p>
<?php
echo $array['message'];
?>
                <form method="post">
                    <p>Dane użytkownika:</p>
                    <table>
                        <tr>
                            <td>Imię:</td>
                            <td><input type="text" name="name" value="<?php echo stripslashes($array['name']); ?>" size="50" maxlength="50" /></td>
                        </tr>
                        <tr>
                            <td>Nazwisko:</td>
                            <td><input type="text" name="surname" value="<?php echo stripslashes($array['surname']); ?>" size="50" maxlength="100" /></td>
                        </tr>
                        <tr>
                            <td>Ulica:</td>
                            <td><input type="text" name="street" value="<?php echo stripslashes($array['street']); ?>" size="30" maxlength="30" /></td>
                        </tr>
                        <tr>
                            <td>Kod pocztowy:</td>
                            <td><input type="text" name="postcode" value="<?php echo stripslashes($array['postcode']); ?>" size="6" maxlength="6" /></td>
                        </tr>
                        <tr>
                            <td>Województwo:</td>
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
                            <td>Miasto:</td>
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
                        <tr>
                            <td>Telefon:</td>
                            <td><input type="text" name="phone" value="<?php echo stripslashes($array['phone']); ?>" size="12" maxlength="12" /></td>
                        </tr>
                        <tr>
                            <td>E-mail:</td>
                            <td><input type="text" name="email" value="<?php echo stripslashes($array['email']); ?>" size="50" maxlength="100" readonly="readonly" /></td>
                        </tr>
                        <tr>
                            <td>Strona www:</td>
                            <td><input type="text" name="www" value="<?php echo stripslashes($array['www']); ?>" size="50" maxlength="100" /></td>
                        </tr>
                        <tr>
                            <td>Opis:</td>
                            <td><textarea name="description" cols="47" rows="8"><?php echo stripslashes($array['description']); ?></textarea></td>
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
                            <td><input type="text" name="login" value="<?php echo stripslashes($array['login']); ?>" size="20" maxlength="20" readonly="readonly" /></td>
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
                </form>

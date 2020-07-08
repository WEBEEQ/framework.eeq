<h2>Rejestracja</h2>
<p>Rejestracja w systemie powoduje dodanie nowego użytkownika. Login i hasło mogą składać się wyłącznie z liter i cyfr. Nie mogą zawierać polskich znaków. Proszę pamiętać, aby wpisać trudne do odgadnięcia hasło. Należy podać koniecznie istniejący adres e-mail, na który wyślemy kod aktywacyjny. Podanie błędnego adresu e-mail uniemożliwi aktywację konta.</p>
<form method="post">
    <?= $array['error'] ?>
    <table>
        <tr>
            <td>Imię:</td>
            <td><input type="text" name="name" value="<?= $array['name'] ?>" size="30" maxlength="30" /></td>
        </tr>
        <tr>
            <td>Nazwisko:</td>
            <td><input type="text" name="surname" value="<?= $array['surname'] ?>" size="50" maxlength="50" /></td>
        </tr>
        <tr>
            <td>Login:</td>
            <td><input type="text" name="login" value="<?= $array['login'] ?>" size="20" maxlength="20" /></td>
        </tr>
        <tr>
            <td>Hasło:</td>
            <td><input type="password" name="password" value="" size="30" maxlength="30" /></td>
        </tr>
        <tr>
            <td>Powtórz hasło:</td>
            <td><input type="password" name="repeat_password" value="" size="30" maxlength="30" /></td>
        </tr>
        <tr>
            <td>E-mail:</td>
            <td><input type="text" name="email" value="<?= $array['email'] ?>" size="50" maxlength="100" /></td>
        </tr>
        <tr>
            <td>Powtórz e-mail:</td>
            <td><input type="text" name="repeat_email" value="" size="50" maxlength="100" /></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="checkbox" name="accept" value="1"<?php if ($array['accept']) { ?> checked="checked"<?php } ?> /> Akceptuję <a href="<?= $array['url'] ?>/regulamin">regulamin</a></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" value="Zatwierdź" /></td>
        </tr>
    </table>
    <input type="hidden" name="token" value="<?= $array['token'] ?>" />
</form>

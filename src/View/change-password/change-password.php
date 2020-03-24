<h2>Resetowanie</h2>
<p>Opcja ta pozwala na zresetowanie hasła do systemu. Proszę podać nowe hasło do swojego konta.</p>
<form method="post">
    <?php echo $array['error']; ?>
    <table>
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
            <td><input type="submit" name="submit" value="Zatwierdź" /></td>
        </tr>
    </table>
    <input type="hidden" name="token" value="<?php echo $array['token']; ?>" />
</form>

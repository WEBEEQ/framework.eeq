                <h2>Logowanie</h2>
                <p>Opcja ta pozwala na zalogowanie użytkownika do systemu. Proszę podać login i hasło do swojego konta. Jeśli nie pamiętają Państwo hasła, system prześle na adres e-mail nowe. Można zapamiętać hasło w systemie, aby w przyszłości nie musieć się ponownie logować.</p>
                <form method="post">
<?php
echo $array['error'];
?>
                    <table>
                        <tr>
                            <td>Login:</td>
                            <td><input type="text" name="login" value="<?php echo stripslashes($array['login']); ?>" size="20" maxlength="20" /></td>
                        </tr>
                        <tr>
                            <td>Hasło:</td>
                            <td><input type="password" name="password" value="" size="30" maxlength="30" /></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type="checkbox" name="forget" value="1"<?php if ($array['forget']) { ?> checked="checked"<?php } ?> /> Nie pamiętam hasła</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type="checkbox" name="remember" value="1"<?php if ($array['remember']) { ?> checked="checked"<?php } ?> /> Zapamiętaj hasło</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type="submit" name="submit" value="Zatwierdź" /></td>
                        </tr>
                    </table>
                    <input type="hidden" name="token" value="<?php echo $array['token']; ?>" />
                </form>

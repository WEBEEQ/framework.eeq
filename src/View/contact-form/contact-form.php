                <h2>Kontakt</h2>
                <form method="post">
<?php
echo $array['error'];
?>
                    <table>
                        <tr>
                            <td>E-mail:</td>
                            <td><input type="text" name="email" value="<?php echo $array['email']; ?>" size="50" maxlength="100" /></td>
                        </tr>
                        <tr>
                            <td>Temat:</td>
                            <td><input type="text" name="subject" value="<?php echo $array['subject']; ?>" size="50" maxlength="100" /></td>
                        </tr>
                        <tr>
                            <td>Wiadomość:</td>
                            <td><textarea name="message" cols="50" rows="10"><?php echo $array['text']; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type="submit" name="submit" value="Wyślij" /> <input type="reset" name="reset" value="Wyczyść" /></td>
                        </tr>
                    </table>
                    <input type="hidden" name="token" value="<?php echo $array['token']; ?>" />
                </form>

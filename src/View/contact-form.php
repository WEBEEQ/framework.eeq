                <h2>Kontakt</h2>
<?php
echo $array['message'];
?>
                <form method="post">
                    <table>
                        <tr>
                            <td>E-mail:</td>
                            <td><input type="text" name="email" value="<?php echo $array['email']; ?>" size="73" maxlength="100" /></td>
                        </tr>
                        <tr>
                            <td>Temat:</td>
                            <td><input type="text" name="subject" value="<?php echo $array['subject']; ?>" size="73" maxlength="100" /></td>
                        </tr>
                        <tr>
                            <td>Wiadomość:</td>
                            <td><textarea name="message" cols="73" rows="10"><?php echo $array['text']; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type="submit" name="submit" value="Wyślij" /> <input type="reset" name="reset" value="Wyczyść" /></td>
                        </tr>
                    </table>
                </form>

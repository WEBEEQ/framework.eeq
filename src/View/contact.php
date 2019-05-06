                <h2>Kontakt</h2>
<?php
echo $array['message'];
?>
                <form method="post">
                    <table class="form">
                        <tr><td class="description">E-mail:</td><td><input type="text" name="email" value="<?php echo $array['email']; ?>" size="50" maxlength="100" /></td></tr>
                        <tr><td class="description">Temat:</td><td><input type="text" name="subject" value="<?php echo $array['subject']; ?>" size="50" maxlength="100" /></td></tr>
                        <tr><td class="description">Wiadomość:</td><td><textarea name="message" cols="47" rows="8"><?php echo $array['text']; ?></textarea></td></tr>
                        <tr><td>&nbsp;</td><td><input type="submit" name="submit" value="Zatwierdź" /> <input type="reset" name="reset" value="Wyczyść" /></td></tr>
                    </table>
                </form>

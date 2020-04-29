<h2>Akceptacja strony</h2>
<form method="post">
    <?= $array['error'] ?>
    <p>Dane do moderacji:</p>
    <table>
        <tr>
            <td>Link:</td>
            <td><a href="<?= $array['url'] ?>/link?www=<?= urlencode($array['www']) ?>"><?= $array['name'] ?></a></td>
        </tr>
        <tr>
            <td>Nazwa:</td>
            <td><input type="text" name="name" value="<?= stripslashes($array['name']) ?>" size="50" maxlength="100" /></td>
        </tr>
        <tr>
            <td>Url:</td>
            <td><input type="text" name="www" value="<?= stripslashes($array['www']) ?>" size="50" maxlength="100" /></td>
        </tr>
        <tr>
            <td>Aktywna:</td>
            <td><input type="radio" name="active" value="1"<?php if ((int) $array['active'] === 1) { ?> checked="checked"<?php } ?> /> Tak <input type="radio" name="active" value="0"<?php if ((int) $array['active'] === 0) { ?> checked="checked"<?php } ?> /> Nie</td>
        </tr>
        <tr>
            <td>Widoczna:</td>
            <td><input type="radio" name="visible" value="1"<?php if ((int) $array['visible'] === 1) { ?> checked="checked"<?php } ?> /> Tak <input type="radio" name="visible" value="0"<?php if ((int) $array['visible'] === 0) { ?> checked="checked"<?php } ?> /> Nie</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="checkbox" name="delete" value="1"<?php if ($array['delete']) { ?> checked="checked"<?php } ?> /> Usuń stronę</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" value="Zatwierdź" /> <input type="reset" name="reset" value="Wyczyść" /></td>
        </tr>
    </table>
    <input type="hidden" name="token" value="<?= $array['token'] ?>" />
</form>

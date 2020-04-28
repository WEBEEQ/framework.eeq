<?php

$value = $array['userData'];
?>
<h2>Konto</h2>
<p>Na tej podstronie można wybrać edycję danych użytkownika. Można także dodawać nowe strony i wybrać edycję ich widoczności w systemie. Państwa dane osobowe nie są wyświetlane innym członkom systemu. Służą jedynie do komunikacji z Państwem przez administrację systemu. Pozostałe kliknięcia informują, ile razy Państwa strony wyświetlą się innym użytkownikom systemu.</p>
<form method="post">
    <?= $array['error'] ?>
    <p>Użytkownik:</p>
    <table>
        <tr>
            <td><a href="<?= $array['url'] ?>/uzytkownik,<?= $value['user_id'] ?>,edycja"><?= $value['user_name'] ?> <?= $value['user_surname'] ?></a></td>
            <td class="option"><a href="<?= $array['url'] ?>/uzytkownik,<?= $value['user_id'] ?>,edycja">Edytuj</a></td>
        </tr>
    </table>
    <p>Pozostało kliknięć:</p>
    <p><?= $value['user_show'] ?></p>
    <p>Lista stron www:</p>
    <table>
        <?php if (!$array['siteList']) { ?>
            <tr>
                <td colspan="2">Brak</td>
            </tr>
        <?php } else { ?>
            <?php foreach ($array['siteList'] as $key => $value) { ?>
                <tr>
                    <td><a href="<?= $array['url'] ?>/strona,<?= $key ?>,edycja"><?= $value['site_name'] ?></a></td>
                    <td class="option"><a href="<?= $array['url'] ?>/strona,<?= $key ?>,edycja">Edytuj</a></td>
                </tr>
            <?php } ?>
            <?php if ($array['pageNavigator']) { ?>
                <tr>
                    <td colspan="2"><?= $array['pageNavigator'] ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>
    <p>Dodaj stronę www:</p>
    <table>
        <tr>
            <td>Nazwa:</td>
            <td><input type="text" name="name" value="<?= stripslashes($array['name']) ?>" size="50" maxlength="100" /></td>
        </tr>
        <tr>
            <td>Url:</td>
            <td><input type="text" name="www" value="<?= stripslashes($array['www']) ?>" size="50" maxlength="100" /></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" value="Dodaj" /></td>
        </tr>
    </table>
    <input type="hidden" name="token" value="<?= $array['token'] ?>" />
</form>

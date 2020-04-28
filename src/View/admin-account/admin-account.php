<h2>Admin</h2>
<p>Strony do moderacji:</p>
<table>
    <?php if (!$array['siteList']) { ?>
        <tr>
            <td colspan="2">Brak</td>
        </tr>
    <?php } else { ?>
        <?php foreach ($array['siteList'] as $key => $value) { ?>
            <tr>
                <td><a href="<?= $array['url'] ?>/strona,<?= $key ?>,akceptacja"><?= $value['site_name'] ?></a></td>
                <td class="option"><a href="<?= $array['url'] ?>/strona,<?= $key ?>,akceptacja">Edytuj</a></td>
            </tr>
        <?php } ?>
        <?php if ($array['pageNavigator']) { ?>
            <tr>
                <td colspan="2"><?= $array['pageNavigator'] ?></td>
            </tr>
        <?php } ?>
    <?php } ?>
</table>

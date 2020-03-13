                <h2>Admin</h2>
                <p>Strony do moderacji:</p>
                <table>
<?php
if (!$array['siteList']) {
?>
                    <tr>
                        <td colspan="2">Brak</td>
                    </tr>
<?php
} else {
    foreach ($array['siteList'] as $key => $value) {
?>
                    <tr>
                        <td><a href="<?php echo $array['url']; ?>/strona,<?php echo $key; ?>,akceptacja"><?php echo $value['site_name']; ?></a></td>
                        <td class="option"><a href="<?php echo $array['url']; ?>/strona,<?php echo $key; ?>,akceptacja">Edytuj</a></td>
                    </tr>
<?php
    }
    if ($array['pageNavigator']) {
?>
                    <tr>
                        <td colspan="2"><?php echo $array['pageNavigator']; ?></td>
                    </tr>
<?php
    }
}
?>
                </table>

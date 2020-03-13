                <h2>Informacja</h2>
<?php
if ($array['passwordChangeEmail']) {
?>
                <p class="ok">Sprawdź pocztę w celu poznania dalszych instrukcji.</p>
<?php
} else {
?>
                <p class="bad">Wysłanie e-mail'a z dalszymi instrukcjami nie powiodło się.</p>
<?php
}
?>

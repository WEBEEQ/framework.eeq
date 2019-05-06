                <h2>Informacja</h2>
<?php
if ($array['newPasswordEmail']) {
?>
                <p class="ok">
                    Hasło konta użytkownika zostało zmienione.<br />
                    Sprawdź pocztę w celu zapoznania z hasłem.
                </p>
<?php
} else {
?>
                <p class="bad">
                    Hasło konta użytkownika zostało zmienione.<br />
                    Wysłanie e-mail'a z hasłem nie powiodło się.
                </p>
<?php
}
?>

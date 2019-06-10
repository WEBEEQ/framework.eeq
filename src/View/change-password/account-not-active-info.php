                <h2>Informacja</h2>
<?php
if ($array['activationEmail']) {
?>
                <p class="bad">
                    Konto użytkownika nie jest aktywne.<br />
                    Sprawdź pocztę w celu aktywacji konta.
                </p>
<?php
} else {
?>
                <p class="bad">
                    Konto użytkownika nie jest aktywne.<br />
                    Wysłanie e-mail'a aktywacyjnego nie powiodło się.
                </p>
<?php
}
?>

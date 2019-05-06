                <h2>Informacja</h2>
<?php
if (!isset($array['password'])) {
?>
                <p class="bad">Konto użytkownika nie jest aktywne.</p>
<?php
} else {
    if ($array['activationEmail']) {
?>
                <p class="bad">
<?php
        if ($array['password']) {
?>
                    Konto o podanym loginie i haśle nie jest aktywne.<br />
<?php
        } else {
?>
                    Konto o podanym loginie nie jest aktywne.<br />
<?php
        }
?>
                    Sprawdź pocztę w celu aktywacji konta.
                </p>
<?php
    } else {
?>
                <p class="bad">
<?php
        if ($array['password']) {
?>
                    Konto o podanym loginie i haśle nie jest aktywne.<br />
<?php
        } else {
?>
                    Konto o podanym loginie nie jest aktywne.<br />
<?php
        }
?>
                    Wysłanie e-mail'a aktywacyjnego nie powiodło się.
                </p>
<?php
    }
}
?>

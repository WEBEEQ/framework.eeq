                <h2>Informacja</h2>
                <p class="<?php if ($array['newEmail'] && !$array['activationEmail']) { ?>bad<?php } else { ?>ok<?php } ?>">
                    Dane użytkownika zostały zapisane.<br />
<?php
if ($array['newPassword']) {
?>
                    Hasło użytkownika zostało zapisane.<br />
<?php
}
if ($array['newEmail']) {
?>
                    E-mail użytkownika został zapisany.<br />
<?php
    if ($array['activationEmail']) {
?>
                    Sprawdź pocztę w celu aktywacji konta.
<?php
    } else {
?>
                    Wysłanie e-mail'a aktywacyjnego nie powiodło się.
<?php
    }
}
?>
                </p>

                <h2>Informacja</h2>
                <p class="<?php if ($array['active'] && !$array['acceptationEmail']) { ?>bad<?php } else { ?>ok<?php } ?>">
<?php
if ($array['active']) {
?>
                    Strona www została zaakceptowana.<br />
<?php
}
?>
                    Dane strony www zostały zapisane.<br />
<?php
if ($array['active']) {
    if ($array['acceptationEmail']) {
?>
                    E-mail akceptacyjny został wysłany.
<?php
    } else {
?>
                    Wysłanie e-mail'a akceptacyjnego nie powiodło się.
<?php
    }
}
?>
                </p>

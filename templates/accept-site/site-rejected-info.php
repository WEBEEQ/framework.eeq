<h2>Informacja</h2>
<p class="<?php if (!$array['acceptationEmail']) { ?>bad<?php } else { ?>ok<?php } ?>">
    Strona www została odrzucona.<br />
    Dane strony www zostały usunięte.<br />
    <?php if ($array['acceptationEmail']) { ?>
        E-mail akceptacyjny został wysłany.
    <?php } else { ?>
        Wysłanie e-mail'a akceptacyjnego nie powiodło się.
    <?php } ?>
</p>

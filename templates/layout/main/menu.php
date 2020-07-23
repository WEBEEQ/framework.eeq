<ul>
    <li<?php if ($array['activeMenu'] === 'main-page') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/">Główna</a></li>
    <?php if (!$_SESSION['user']) { ?>
        <li<?php if ($array['activeMenu'] === 'register-user') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/rejestracja">Rejestracja</a></li>
        <li<?php if ($array['activeMenu'] === 'log-in-user') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/logowanie">Logowanie</a></li>
    <?php } else { ?>
        <li<?php if ($array['activeMenu'] === 'user-account') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/konto">Konto</a></li>
        <?php if ($_SESSION['admin']) { ?>
            <li<?php if ($array['activeMenu'] === 'admin-account') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/admin">Admin</a></li>
        <?php } ?>
        <li<?php if ($array['activeMenu'] === 'log-out-user') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/wylogowanie">Wylogowanie</a></li>
    <?php } ?>
    <li<?php if ($array['activeMenu'] === 'user-regulation') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/regulamin">Regulamin</a></li>
    <li<?php if ($array['activeMenu'] === 'user-privacy') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/prywatnosc">Prywatność</a></li>
    <li<?php if ($array['activeMenu'] === 'user-help') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/pomoc">Pomoc</a></li>
    <li<?php if ($array['activeMenu'] === 'contact-form') { echo ' class="active"'; } ?>><a href="<?= $array['url'] ?>/kontakt">Kontakt</a></li>
    <li><a href="https://www.facebook.com/SIECIQ/">Lubię&nbsp;to!</a></li>
</ul>

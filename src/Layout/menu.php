                <ul>
                    <li<?php if ($activeMenu == 'main-page') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/">Główna</a></li>
<?php
if ($_SESSION['user'] == '') {
?>
                    <li<?php if ($activeMenu == 'add-user') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/rejestracja">Rejestracja</a></li>
                    <li<?php if ($activeMenu == 'login-user') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/logowanie">Logowanie</a></li>
<?php
} else {
?>
                    <li<?php if ($activeMenu == 'user-account') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/konto">Konto</a></li>
<?php
    if ($_SESSION['admin'] == 1) {
?>
                    <li<?php if ($activeMenu == 'admin-account') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/admin">Admin</a></li>
<?php
    }
?>
                    <li<?php if ($activeMenu == 'logout-user') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/wylogowanie">Wylogowanie</a></li>
<?php
}
?>
                    <li<?php if ($activeMenu == 'user-regulation') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/regulamin">Regulamin</a></li>
                    <li<?php if ($activeMenu == 'user-privacy') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/prywatnosc">Prywatność</a></li>
                    <li<?php if ($activeMenu == 'user-help') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/pomoc">Pomoc</a></li>
                    <li<?php if ($activeMenu == 'contact-form') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/kontakt">Kontakt</a></li>
                    <li><a href="https://www.facebook.com/SIECIQ/">Lubię&nbsp;to!</a></li>
                </ul>

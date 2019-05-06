                <ul>
                    <li<?php if ($activeMenu == 'main-page') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/">Top osoby</a></li>
                    <li<?php if ($activeMenu == 'add-user') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/dodawanie">Dodaj osobę</a></li>
                    <li<?php if ($activeMenu == 'user-search') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/szukanie">Szukaj osób</a></li>
                    <li<?php if ($activeMenu == 'contact-form') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/kontakt">Kontakt</a></li>
                </ul>

                <ul>
                    <li<?php if ($activeMenu == 'main') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/">Top osoby</a></li>
                    <li<?php if ($activeMenu == 'addition') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/dodawanie">Dodaj osobę</a></li>
                    <li<?php if ($activeMenu == 'search') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/szukanie">Szukaj osób</a></li>
                    <li<?php if ($activeMenu == 'contact') { echo ' class="active"'; } ?>><a href="<?php echo $url; ?>/kontakt">Kontakt</a></li>
                </ul>

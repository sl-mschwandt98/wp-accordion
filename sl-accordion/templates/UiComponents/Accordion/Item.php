<?php
/**
 * Item.php
 *
 * Eine Template für ein Item der Akkordeon-Komponente.
 *
 * Ausschließen von Code-Sniffing, da die Datei kein reiner PHP-Code ist.
 * @codingStandardsIgnoreFile
 */

isset($viewBag) ? $viewBag : [];
?>

<details
    class="sl-accordion-item"
    id="akkordeon-<?= strtolower(urlencode($viewBag['title'])); ?>"
    <?= $viewBag['is_open'] ? 'open' : ''; ?>
>
    <summary>
        <?= $viewBag['title']; ?>
    </summary>
    <div>
        <?= $viewBag['content']; ?>
    </div>
</details>

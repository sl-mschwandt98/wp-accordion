<?php
/**
 * Container.php
 *
 * Eine Template für den Container der Akkordeon-Komponente.
 *
 * Ausschließen von Code-Sniffing, da die Datei kein reiner PHP-Code ist.
 * @codingStandardsIgnoreFile
 */

isset($viewBag) ? $viewBag : [];
?>

<div
    class="sl-accordion <?= $viewBag['css_class']; ?>"
    data-allow-collapse-all="<?= $viewBag['allow_collapse_all'] ? 'true' : 'false'; ?>"
    data-allow-expand-multiple="<?= $viewBag['allow_expand_multiple'] ? 'true' : 'false'; ?>"
>
    <?= $viewBag['content']; ?>
</div>

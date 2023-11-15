<?php
/**
 * AccordionComponent.php
 *
 * Enthält die Klasse AccordionComponent.
 */

namespace SelectLine\Accordion\UiComponents;

use SelectLine\Accordion\Models\AccordionItem;

/**
 * Erstellt eine Akkordeon.
 */
class AccordionComponent {

    /**
     * Die Items.
     *
     * @var AccordionItem[]
     */
    public readonly array $items;

    /**
     * Gibt an, ob mehrere Items gleichzeitig geöffnet sein dürfen.
     *
     * @var bool
     */
    public readonly bool $allowExpandMultiple;

    /**
     * Gibt an, ob alle Items geschlossen werden dürfen.
     *
     * @var bool
     */
    public readonly bool $allowCollapseAll;

    /**
     * Die CSS-Klasse des Containers.
     *
     * @var string
     */
    public readonly string $containerClass;

    /**
     * Der Inhalt des Containers.
     *
     * @var string
     */
    private string $content = '';

    /**
     * Erstellt eine neue Instanz der Klasse Accordion.
     *
     * @param array     $items                  Die Items.
     * @param bool      $allowExpandMultiple    Gibt an, ob mehrere Items
     *                                          gleichzeitig geöffnet sein
     *                                          dürfen.
     * @param bool      $allowCollapseAll       Gibt an, ob alle Items
     *                                          geschlossen werden dürfen.
     * @param string    $containerClass         Die CSS-Klasse des Containers.
     */
    public function __construct(
        array $items = [],
        bool $allowExpandMultiple = true,
        bool $allowCollapseAll = true,
        string $containerClass = ''
    ) {
        $this->items               = $items;
        $this->allowExpandMultiple = $allowExpandMultiple;
        $this->allowCollapseAll    = $allowCollapseAll;
        $this->containerClass      = $containerClass;
    }

    /**
     * Erstellt eine Akkordeon-Komponente.
     *
     * @return string Der HTML-Code der Akkordeon-Komponente.
     */
    public function build(): string {

        if (empty($this->items)) {
            return '';
        }

        $pluginDir = dirname(__DIR__);
        wp_enqueue_style(
            'sl-accordion-css',
            plugins_url('/assets/css/accordion.css', $pluginDir),
            [],
            SL_ACCORDION_VERSION
        );
        wp_enqueue_script(
            'sl-accordion-js',
            plugins_url('/assets/js/accordion.js', $pluginDir),
            [],
            SL_ACCORDION_VERSION,
            true
        );

        foreach ($this->items as $item) {
            $this->content .= self::buildItem($item);
        }

        return $this->buildContainer();
    }

    /**
     * Erstellt den Container der Akkordeon-Komponente.
     *
     * @return string Der HTML-Code des Containers.
     */
    public function buildContainer(?string $content = null): string {

        if (!is_null($content)) {
            $this->content = $content;
        }

        return sl_accordion('UiComponents/Accordion/Container', [
            'content'               => do_shortcode($this->content),
            'css_class'             => $this->containerClass,
            'allow_expand_multiple' => $this->allowExpandMultiple,
            'allow_collapse_all'    => $this->allowCollapseAll
        ], true);
    }

    /**
     * Erstellt ein Item der Akkordeon-Komponente.
     *
     * @param AccordionItem $item Das Item.
     *
     * @return string Der HTML-Code des Items.
     */
    public function buildItem(AccordionItem $item): string {
        return sl_accordion('UiComponents/Accordion/Item', [
            'title'   => $item->title,
            'content' => $item->content,
            'is_open' => $item->isOpen
        ], true);
    }
}

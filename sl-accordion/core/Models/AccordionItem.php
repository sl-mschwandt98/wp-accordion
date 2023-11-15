<?php
/**
 * AccordionItem.php
 *
 * Enthält die Klasse AccordionItem.
 */

namespace SelectLine\Accordion\Models;

/**
 * Die Model-Klasse für ein Item der Akkordeon-Komponente.
 */
class AccordionItem {

    /**
     * Der Titel des Items.
     *
     * @var string
     */
    public readonly string $title;

    /**
     * Der Inhalt des Items.
     *
     * @var string
     */
    public readonly string $content;

    /**
     * Gibt an, ob das Item geöffnet ist.
     *
     * @var boolean
     */
    public bool $isOpen;

    /**
     * Erstellt eine neue Instanz der Klasse AccordionItem.
     *
     * @param string    $title      Der Titel des Items.
     * @param string    $content    Der Inhalt des Items.
     * @param bool      $isOpen     Gibt an, ob das Item im Akkordeon geöffnet
     *                              ist.
     */
    public function __construct(string $title, string $content, bool $isOpen = false) {
        $this->title   = sanitize_text_field($title);
        $this->content = $content;
        $this->isOpen  = $isOpen;
    }
}

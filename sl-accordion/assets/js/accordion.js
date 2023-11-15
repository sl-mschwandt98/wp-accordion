/**
 * accordion.js
 *
 * Enthält die Funktionen für ein Akkordeon.
 */

'use strict';

(function (window, document) {
    /**
     * Initialisiert die Akkordeons, sobald der DOM geladen wurde.
     *
     * @returns {void}
     */
    document.addEventListener('DOMContentLoaded', function () {
        const accordions = document.querySelectorAll('.sl-accordion');
        accordions.forEach((accordion) => new Accordion(accordion));
    });

    /**
     * Fügt zusätzliche Funktionen für ein Akkordeon hinzu.
     *
     * Hinweise:
     * 1. Die Arrow-Functions werden verwendet, damit das this-Objekt
     * nicht überschrieben wird. Daher nicht durch normale bzw. anonyme
     * Funktionen ersetzen.
     * 2. Die Properties und  Methoden sind als private Methoden gekennzeichnet,
     * da sie bisher nur innerhalb der Klasse verwendet werden. Sollten sie
     * später doch noch benötigt werden, können sie einfach als public
     * gekennzeichnet werden.
     */
    class Accordion {
        /**
         * Die Items des Akkordeons.
         *
         * @type {NodeListOf<HTMLDetailsElement>}
         */
        #items;

        /**
         * Initialisiert das Akkordeon.
         *
         * @param {HTMLElement} element
         */
        constructor(element) {
            this.#items = element.querySelectorAll('details');

            if (element.dataset.allowCollapseAll === 'false') {
                this.#preventCollapseAll();
            }

            if (element.dataset.allowExpandMultiple === 'false') {
                this.#preventExpandMultiple();
            }

            const hash = window.location.hash.substring(1);
            if (hash?.length) {
                for (const item of this.#items) {
                    if (item.id === hash) {
                        item.open = true;
                        break;
                    }
                }
            }
        }

        /**
         * Verhindert, dass alle Elemente geschlossen werden können.
         *
         * @returns {void}
         */
        #preventCollapseAll() {
            if (!this.#items.length) {
                return;
            }

            let hasOpenItems = false;
            this.#items.forEach((item) => {
                if (item.open) {
                    hasOpenItems = true;
                }

                /**
                 * @param {ToggleEvent} event
                 */
                item.addEventListener('toggle', (event) => {
                    const element = event.target;
                    if (!element.open) {
                        element.removeAttribute('onclick');
                    }

                    let openItems = [];
                    this.#items.forEach((innerItem) => {
                        if (innerItem.open) {
                            openItems.push(innerItem);
                        }
                    });

                    if (openItems.length === 1) {
                        openItems[0].setAttribute('onclick', 'return false');
                    } else {
                        openItems.forEach((item) => item.removeAttribute('onclick'));
                    }
                });
            });

            // Sicherstellen, dass beim Laden mindestens ein Element geöffnet ist.
            if (!hasOpenItems) {
                this.#items[0].open = true;
            }
        }

        /**
         * Verhindert, dass mehrere Elemente gleichzeitig geöffnet werden
         * können.
         *
         * @returns {void}
         */
        #preventExpandMultiple() {
            let hasOpenItems = false;
            this.#items.forEach((item) => {
                // Sicherstellen, dass beim Laden nur ein Element geöffnet ist.
                if (item.open && !hasOpenItems) {
                    hasOpenItems = true;
                } else {
                    item.open = false;
                }

                /**
                 * @param {ToggleEvent} event
                 */
                item.addEventListener('toggle', (event) => {
                    const element = event.target;
                    if (!element.open) {
                        return;
                    }

                    this.#items.forEach((innerItem) => {
                        if (innerItem !== element) {
                            innerItem.open = false;
                        }
                    });
                });
            });
        }
    }
})(window, document);

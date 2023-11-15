<?php
/**
 * sl-accordion.php
 *
 * Plugin Name: SL Accordion
 */

define('SL_ACCORDION_VERSION', '23.11.2');

define('SL_ACCORDION_VC_CATEGORY', __('SL Accordion', 'sl-accordion'));

/**
 * Startroutine zur Überprüfung von Abhängigkeiten, bevor das Plugin
 * initialisiert werden kann.
 */
add_action('plugins_loaded', function () {

    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    include_once(__DIR__ . '/core/Models/AccordionItem.php');
    include_once(__DIR__ . '/core/UiComponents/AccordionComponent.php');
    include_once(__DIR__ . '/core/shortcodes/Accordion/Container.php');
    include_once(__DIR__ . '/core/shortcodes/Accordion/Item.php');
});

/**
 *  Routine zum Rendern von Templates.
 *
 *  @since 1.1.2
 *  @param string $template - Der Name des Templates
 *  @param array $viewBag   - Daten für das Template
 *  @param bool $return     - Soll das Template als String zurückgegeben werden
 */
function sl_accordion(
    $template,
    $viewBag = [],
    $return = false
) {
    if ($return) {
        ob_start();
        include(__DIR__ . '/templates/' . $template . '.php');
        return ob_get_clean();
    } else {
        include(__DIR__ . '/templates/' . $template . '.php');
    }
}

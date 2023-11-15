<?php
/**
 * Item.php
 *
 * Registriert den VC-Shortcode [sl_accordion_item].
 */

use SelectLine\Accordion\Models\AccordionItem;
use SelectLine\Accordion\UiComponents\AccordionComponent;

if (!class_exists('WPBakeryShortCode_Vc_Tta_Section')) {
    return;
}

add_action('vc_before_init', function () {

    vc_map([
        'name'                      => __('Akkordeon-Item', 'sl-accordion'),
        'base'                      => 'sl_accordion_item',
        'category'                  => SL_ACCORDION_VC_CATEGORY,
        'icon'                      => '', // TODO
        'is_container'              => true,
        'allowed_container_element' => 'vc_row',
        'as_child'                  => [
            'only' => 'sl_accordion_container'
        ],
        'as_parent'                 => [
            'except' => 'sl_accordion_container,sl_accordion_item'
        ],
        'js_view'                   => 'VcBackendTtaSectionView',
        'params'                    => [
            [
                'type'        => 'textfield',
                'param_name'  => 'title',
                'heading'     => __('Titel', 'sl-accordion'),
                'description' => __('Titel des Akkordeon-Items', 'sl-accordion'),
            ]
        ],
        'custom_markup'             => '<div class="vc_tta-panel-heading">
                <h4 class="vc_tta-panel-title vc_tta-controls-icon-position-left"><a href="javascript:;" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-accordion data-vc-container=".vc_tta-container"><span class="vc_tta-title-text">{{ section_title }}</span><i class="vc_tta-controls-icon vc_tta-controls-icon-plus"></i></a></h4>
            </div>
            <div class="vc_tta-panel-body">
                {{ editor_controls }}
                <div class="{{ container-class }}">
                {{ content }}
                </div>
            </div>',
        'default_content'           => ''
    ]);
});

/**
 * Klasse des Shortcodes "Akkordeon-Item".
 *
 * @codeCoverageIgnore
 * @SuppressWarnings(PHPMD.CamelCaseClassName)
 */
class WPBakeryShortCode_sl_accordion_item extends \WPBakeryShortCode_Vc_Tta_Section {

    /**
     * Rendert ein Akkordeon-Item.
     *
     * @param  array  $aParams  Die Einstellungen des Shortcodes.
     *
     * string title Der Titel des Akkordeon-Items.
     *
     * @param  string $aContent Der Inhalt der Kachel.
     *
     * @return string Die HTML der Kachel.
     */
    protected function content($aParams, $aContent = null) {

        $aParams = shortcode_atts([
            'title' => ''
        ], $aParams);

        $item      = new AccordionItem($aParams['title'], do_shortcode($aContent));
        $accordion = new AccordionComponent();
        return $accordion->buildItem($item);
    }

    public function getFileName() {
        return 'sl_accordion_item';
    }
}

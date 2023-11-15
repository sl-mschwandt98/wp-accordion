<?php
/**
 * Container.php
 *
 * Registriert den VC-Shortcode [sl_accordion_container].
 */

use SelectLine\Accordion\UiComponents\AccordionComponent;

if (!class_exists('WPBakeryShortCode_Vc_Tta_Accordion')) {
    return;
}

add_action('vc_before_init', function () {

    vc_map([
        'name'              => __('Akkordeon', 'sl-accordion'),
        'base'              => 'sl_accordion_container',
        'category'          => SL_ACCORDION_VC_CATEGORY,
        'icon'              => '', // TODO
        'is_container'      => true,
        'as_parent'         => [
            'only' => 'sl_accordion_item'
        ],
        'params'            => [
            [
                'type'        => 'checkbox',
                'param_name'  => 'allow_expand_multiple',
                'heading'     => __('Erweiterung mehrerer Items zulassen?', 'sl-accordion'),
                'description' => __(
                    'Steuert, ob mehrere Akkordeon-Items zeitgleich geöffnet werden können.',
                    'sl-accordion'
                ),
                'value'       => ['Ja' => true],
                'std'         => true,
                'admin_label' => true
            ],
            [
                'type'        => 'checkbox',
                'param_name'  => 'allow_collapse_all',
                'heading'     => __('Schließen aller Items zulassen?', 'sl-accordion'),
                'description' => __(
                    'Steuert, ob alle Akkordeon-Items zeitgleich geschlossen werden können.',
                    'sl-accordion'
                ),
                'value'       => ['Ja' => true],
                'std'         => true,
                'admin_label' => true
            ],
            // Designeinstellungen
            [
                'type'       => 'css_editor',
                'heading'    => esc_html__('CSS box', 'js_composer'),
                'param_name' => 'css',
                'group'      => esc_html__('Design Options', 'js_composer')
            ]
        ],
        'admin_enqueue_css' => preg_replace(
            '/\s/', '%20',
            plugins_url('/assets/css/admin/backend-accordion-container.css', dirname(__DIR__, 2))
        ),
        'js_view'           => 'VcBackendTtaAccordionView',
        'custom_markup'     => '<div class="vc_tta-container" data-vc-action="collapseAll">
                <div class="vc_general vc_tta vc_tta-accordion vc_tta-color-backend-accordion-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-o-shape-group vc_tta-controls-align-left vc_tta-gap-2">
                <div class="vc_tta-panels vc_clearfix {{container-class}}">
                    <div class="vc_tta-panel vc_tta-section-append">
                        <div class="vc_tta-panel-heading">
                            <h4 class="vc_tta-panel-title vc_tta-controls-icon-position-left">
                            <a href="javascript:;" aria-expanded="false" class="vc_tta-backend-add-control">
                                <span class="vc_tta-title-text">' . esc_html__('Add Section', 'js_composer') . '</span>
                                    <i class="vc_tta-controls-icon vc_tta-controls-icon-plus"></i>
                                </a>
                            </h4>
                        </div>
                    </div>
                </div>
                </div>
            </div>',
        'default_content'   => '[sl_accordion_item title="' . sprintf('%s %d', esc_html__('Section', 'js_composer'), 1) . '"][/sl_accordion_item][sl_accordion_item title="' . sprintf('%s %d', esc_html__('Section', 'js_composer'), 2) . '"][/sl_accordion_item]',
    ]);
});

/**
 * Klasse des Shortcodes "Akkordeon".
 *
 * @codeCoverageIgnore
 * @SuppressWarnings(PHPMD.CamelCaseClassName)
 */
class WPBakeryShortCode_sl_accordion_container extends \WPBakeryShortCode_Vc_Tta_Accordion {

    /**
     * Rendert den Akkordeon-Container.
     *
     * @param  array  $aParams  Die Einstellungen des Shortcodes.
     *
     * bool     allow_expand_multiple   Gibt an, ob mehrere Items gleichzeitig
     *                                  geöffnet sein dürfen.
     * bool     allow_collapse_all      Gibt an, ob alle Items geschlossen
     *                                  werden dürfen.
     * string   css                     Die CSS-Klasse des Containers.
     *
     * @param  string $aContent Der Inhalt der Kachel.
     *
     * @return string Die HTML der Kachel.
     */
    protected function content($aParams, $aContent = null) {

        $aParams = shortcode_atts([
            'allow_expand_multiple' => true,
            'allow_collapse_all'    => true,
            'css'                   => '',
        ], $aParams);

        $accordion = new AccordionComponent(
            [],
            filter_var($aParams['allow_expand_multiple'], FILTER_VALIDATE_BOOLEAN),
            filter_var($aParams['allow_collapse_all'], FILTER_VALIDATE_BOOLEAN),
            vc_shortcode_custom_css_class($aParams['css'], ' ')
        );

        return $accordion->buildContainer($aContent);
    }

    public function setGlobalTtaInfo() {
        $sectionClass       = wpbakery()->getShortCode('sl_accordion_item')->shortcodeClass();
        $this->sectionClass = $sectionClass;

        if (is_object($sectionClass)) {
            VcShortcodeAutoloader::getInstance()->includeClass('WPBakeryShortCode_sl_accordion_item');
            WPBakeryShortCode_sl_accordion_item::$tta_base_shortcode = $this;
            WPBakeryShortCode_sl_accordion_item::$self_count         = 0;
            WPBakeryShortCode_sl_accordion_item::$section_info       = array();

            return true;
        }

        return false;
    }

    public function getActiveSection($atts, $strict_bounds = false) {
        $active_section = intval($atts['active_section']);

        if ($strict_bounds) {
            VcShortcodeAutoloader::getInstance()->includeClass('WPBakeryShortCode_sl_accordion_item');
            if ($active_section < 1) {
                $active_section = 1;
            } elseif ($active_section > WPBakeryShortCode_sl_accordion_item::$self_count) {
                $active_section = WPBakeryShortCode_sl_accordion_item::$self_count;
            }
        }

        return $active_section;
    }

    public function getParamPaginationList($atts, $content) {
        if (empty($atts['pagination_style'])) {
            return null;
        }
        $isPageEditabe = vc_is_page_editable();

        $html   = array();
        $html[] = '<ul class="' . $this->getTtaPaginationClasses() . '">';

        if (!$isPageEditabe) {
            VcShortcodeAutoloader::getInstance()->includeClass('WPBakeryShortCode_sl_accordion_item');
            foreach (WPBakeryShortCode_sl_accordion_item::$section_info as $nth => $section) {
                $active_section = $this->getActiveSection($atts, false);

                $classes = array('vc_pagination-item');
                if (($nth + 1) === $active_section) {
                    $classes[] = $this->activeClass;
                }

                $a_html = '<a href="#' . $section['tab_id'] . '" class="vc_pagination-trigger" data-vc-tabs data-vc-container=".vc_tta"></a>';
                $html[] = '<li class="' . implode(' ', $classes) . '" data-vc-tab>' . $a_html . '</li>';
            }
        }

        $html[] = '</ul>';

        return implode('', $html);
    }

    public function getAddAllowed() {
        return vc_user_access_check_shortcode_all('sl_accordion_item');
    }
}

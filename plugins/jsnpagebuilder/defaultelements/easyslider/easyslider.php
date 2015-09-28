<?php
/**
 * @version    $Id$
 * @package    JSN_PageBuilder
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file.
defined('_JEXEC') || die('Restricted access');

include_once 'helpers/helper.php';

/**
 * JSN Easy Slider shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeEasyslider extends IG_Pb_Element {

    /**
     * Constructor
     *
     */
    public function __construct() {
        if (JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_easyslider")) {
            include_once JPATH_ROOT . '/administrator/components/com_easyslider/classes/jsn.easyslider.render.php';
        }
        parent::__construct();
    }

    /**
     * Include admin scripts
     *
     * @return type
     */
    public function backend_element_assets() {
    }

    /**
     * DEFINE configuration information of shortcode
     *
     * @return type
     */
    public function element_config() {
        $this->config['shortcode'] = 'pb_easyslider';
        $this->config['name'] = JText::_('JSN_PAGEBUILDER_ELEMENT_EASYSLIDER');
        $this->config['cat'] = JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_EXTRA');
        $this->config['icon'] = 'icon-pb-easyslider';
        $this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_EASYSLIDER_DES");
    }

    /**
     * DEFINE setting options of shortcode in backend
     */
    public function backend_element_items()
    {
        $allSlider = JSNPbEasySliderHelper::getAllSlider();
        $this->items = array(
            'content' => array(
                array(
                    'name'  => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE'),
                    'id'    => 'el_title',
                    'type'  => 'text_field',
                    'class' => 'jsn-input-xxlarge-fluid',
                    'std'   => JText::_('JSN_PAGEBUILDER_ELEMENT_EASYSLIDER_ELEMENT_TITLE_STD'),
                    'role'  => 'title',
                ),
                array(
                    'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_EASYSLIDER_ITEMS'),
                    'id'      => 'easyslider_id',
                    'type'    => 'select',
                    "class"   => "jsn-input-large-fluid",
                    'options' => $allSlider,
                    'std'     => JSNPagebuilderHelpersType::getFirstOption($allSlider),
                ),
            ),
            'styling' => array(
                array(
                    'type' => 'preview',
                ),
            ),
        );
    }

    /**
     * DEFINE setting options of shortcode in frontend
     */
    public function frontend_element_items()
    {
        $this->items = array(
            'content' => array(
                array(
                    'name'  => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE'),
                    'id'    => 'el_title',
                    'type'  => 'text_field',
                    'class' => 'jsn-input-xxlarge-fluid',
                    'std'   => JText::_('JSN_PAGEBUILDER_ELEMENT_EASYSLIDER_ELEMENT_TITLE_STD'),
                    'role'  => 'title',
                ),
                array(
                    'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_EASYSLIDER_ITEMS'),
                    'id'      => 'easyslider_id',
                    'type'    => 'select',
                    "class"   => "jsn-input-large-fluid",
                ),
            ),
            'styling' => array(
                array(
                    'type' => 'preview',
                ),
            ),
        );
    }

    /**
     * DEFINE setting options of shortcode
     *
     * @return type
     */
    public function element_shortcode($atts = null, $content = null) {
        $arr_params = JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts);
        extract($arr_params);

        if (!JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_easyslider")) {
            $html_element = JText::_('JSN_PAGEBUILDER_ELEMENT_EASYSLIDER_MSG_INSTALL_EASYSLIDER_AND_ENABLE');
            return $this->element_wrapper($html_element, $arr_params);
        }

        $easySliderId = (int) $arr_params['easyslider_id'];
        /** @var JSNEasySliderRender $objJSNEasySliderRender */
        $objJSNEasySliderRender = new JSNEasySliderRender();
        $html = $objJSNEasySliderRender->render($easySliderId, true);

        return $this->element_wrapper($html, $arr_params);
    }

}

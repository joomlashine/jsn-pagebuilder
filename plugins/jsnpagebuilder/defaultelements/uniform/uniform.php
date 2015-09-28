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
 * Uniform shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeUniform extends IG_Pb_Element {

    /**
     * Constructor
     *
     * @return type
     */
    public function __construct() {
        if (JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_uniform")) {
            include_once JPATH_ROOT . '/administrator/components/com_uniform/helpers/uniform.php';
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
        $this->config['shortcode'] = 'pb_uniform';
        $this->config['name'] = JText::_('JSN_PAGEBUILDER_ELEMENT_UNIFORM');
        $this->config['cat'] = JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_EXTRA');
        $this->config['icon'] = 'icon-pb-uniform';
        $this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_UNIFORM_DES");
    }

    /**
     * DEFINE setting options of shortcode in backend
     */
    public function backend_element_items()
    {
        $allUniform = JSNPbUniformHelper::getAllUniform();
        $this->items = array(
            'content' => array(
                array(
                    'name'  => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE'),
                    'id'    => 'el_title',
                    'type'  => 'text_field',
                    'class' => 'jsn-input-xxlarge-fluid',
                    'std'   => JText::_('JSN_PAGEBUILDER_ELEMENT_UNIFORM_ELEMENT_TITLE_STD'),
                    'role'  => 'title',
                ),
                array(
                    'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_UNIFORM_FORM'),
                    'id'      => 'uniform_id',
                    'type'    => 'select',
                    "class"   => "jsn-input-large-fluid",
                    'std'     => JSNPagebuilderHelpersType::getFirstOption($allUniform),
                    'options' => $allUniform,
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
                    'std'   => JText::_('JSN_PAGEBUILDER_ELEMENT_UNIFORM_ELEMENT_TITLE_STD'),
                    'role'  => 'title',
                ),
                array(
                    'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_UNIFORM_FORM'),
                    'id'      => 'uniform_id',
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
        /** @var JDocumentHTML $document */
        $document = JFactory::getDocument();
        $document->addScript(JSNPB_ELEMENT_URL . '/uniform/assets/js/uniform.js');
        $document->addStyleSheet(JSNPB_ELEMENT_URL . '/uniform/assets/css/uniform.css');

        $output = '';
        $arr_params = JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts);
        extract($arr_params);

        if (!JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_uniform")) {
            $html_element = JText::_('JSN_PAGEBUILDER_ELEMENT_UNIFORM_MSG_INSTALL_EASYSLIDER_AND_ENABLE');
            return $this->element_wrapper($html_element, $arr_params);
        }

        // No matches, skip this
        $formID = $arr_params['uniform_id'];

        if (isset($formID)) {
            $output = $this->loadJSNUniform($formID, $formID);
        }
        // We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
        $html = $output;

        // Disable submit button in backend
        $app = JFactory::getApplication();
        $isAdmin = $app->isAdmin() ? true : false;
        if ($isAdmin) {
            $html .= '<input type="hidden" id="form-preview-backend" name="form_preview_backend" value="1">';

            $_errorStyleSheets = array(
                '/administrator/components/com_uniform/assets/css/form.css',
                'task=generateStylePages',
            );
            $_styleSheets = $document->_styleSheets;
            foreach ($_styleSheets as $_key => $_value) {
                unset($document->_styleSheets[$_key]);
                foreach ($_errorStyleSheets as $_errorStyleSheet) {
                    if (strpos($_key, $_errorStyleSheet) !== false) {
                        $_key = preg_replace("/\/administrator/", "", $_key);
                        break;
                    }
                }
                $document->_styleSheets[$_key] = $_value;
            }

            $_errorScripts = array(
                '/administrator/components/com_uniform/assets/js/libs/json-2.3.min.js',
                '/administrator/components/com_uniform/assets/js/jsn_uf_jquery_safe.js',
                '/administrator/components/com_uniform/assets/js/jsn_uf_conflict.js',
                '/administrator/components/com_uniform/assets/js/form.js',
                '/administrator/components/com_uniform/assets/js/libs/jquery-ui-1.10.3.custom.min.js',
            );
            $_scripts = $document->_scripts;
            foreach ($_scripts as $_key => $_value) {
                unset($document->_scripts[$_key]);
                foreach ($_errorScripts as $_errorScript) {
                    if (strpos($_key, $_errorScript) !== false) {
                        $_key = preg_replace("/\/administrator/", "", $_key);
                        break;
                    }
                }
                $document->_scripts[$_key] = $_value;
            }
        } else {
            $html .= '<input type="hidden" id="form-preview-backend" name="form_preview_backend" value="0">';
        }

        return $this->element_wrapper($html, $arr_params);
    }

    /**
     * Load Form
     *
     * @param   Int $formID Form id
     * @param   Int $index Form Index
     *
     * @return void
     */
    public function loadJSNUniform($formID, $index) {
        require_once JPATH_ROOT . '/administrator/components/com_uniform/uniform.defines.php';
        $formName = md5(date("Y-m-d H:i:s") . $index);

        return JSNUniformHelper::generateHTMLPages($formID, $formName);
    }
}

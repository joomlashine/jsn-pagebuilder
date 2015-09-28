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

/**
 * Accordion Item shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeAccordionItem extends IG_Pb_Child {

	/**
	 * Constructor
	 *
	 * @return type
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Include admin scripts
	 * 
	 * @return type
	 */
	public function backend_element_assets() {
		$document = JFactory::getDocument();
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-select2/select2.min.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-select2/select2.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-iconselector.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-general.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-fonticomoon.css', 'css' );
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/font-awesome/css/font-awesome.min.css', 'css');
	}
		
	/**
	 * DEFINE configuration information of shortcode
	 * 
	 * @return type
	 */
	public function element_config() {
		$this->config['shortcode'] = 'pb_accordion_item';
		$this->config['exception'] = array(
			'data-modal-title' 	=> JText::_('JSN_PAGEBUILDER_ELEMENT_ACCORDION_ITEM_LABEL')
		);
		$this->config['shortcode_structure']	= '';
	}

    /**
     * DEFINE setting options of shortcode in backend
     */
    public function backend_element_items() {
        $this->frontend_element_items();
    }

	/**
	 * DEFINE setting options of shortcode in frontend
	 */
	public function frontend_element_items() {
		$this->items = array(
			"Notab" => array(
				array(
					"name" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_HEADING"),
					"id" => "heading",
					"type" => "text_field",
					"class" => "jsn-input-xxlarge-fluid",
					"role" => "title",
					"std" => JText::_('JSN_PAGEBUILDER_ELEMENT_ACCORDION_ITEM_HEADING_STD')
				),
				array(
					"name" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_BODY"),
					"id" => "body",
					"role" => "content",
					"type" => "tiny_mce",
					"std" => JSNPagebuilderHelpersType::loremText()
				),
				array(
					"name" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ICON"),
					"id" => "icon",
					"type" => "icons",
					"std" => "",
					"role" => "title_prepend",
					"role_type" => "icon"
				),
				array(
					"name" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_TAG"),
					"id" => "tag",
					"type" => "tag",
					"std" => ""
				),
			)
		);
	}

	/**
	 * DEFINE shortcode content
	 *
	 * @param type $atts
	 * @param type $content
	 * 
	 * @return string
	 */
	public function element_shortcode($atts = null, $content = null) {
		extract(JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts));

		// tag1,tag2 => tag1 tag2 , to filter
		$tag = str_replace(" ", "_", $tag);
		$tag = str_replace(",", " ", $tag);
		$content = JSNPagebuilderHelpersShortcode::removeAutop($content);
		return "
			<div class='panel panel-default' data-tag='$tag'>
				<div class='panel-heading'>
					<h4 class='panel-title'>
						<a data-toggle='collapse' data-parent='#accordion_{ID}' href='#collapse{index}'>
						<i class='$icon'></i>$heading
						</a>
					</h4>
				</div>
				<div id='collapse{index}' class='panel-collapse collapse {show_hide}'>
				  <div class='panel-body'>
				  " . JSNPagebuilderHelpersFunctions::add_absolute_path_to_image_url($content) . "
				  </div>
				</div>
			</div><!--seperate-->";
		
	}
}
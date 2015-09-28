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
 * Tab Item shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeTabItem extends IG_Pb_Child {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Include admin scripts
	 */
	public function backend_element_assets() {
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-iconselector.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-general.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-fonticomoon.css', 'css' );
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/font-awesome/css/font-awesome.min.css', 'css');
	}
	
	/**
	 * DEFINE configuration information of shortcode
	 */
	public function element_config() {
		$this->config['shortcode'] = 'pb_tab_item';
		$this->config['exception'] = array(
			'data-modal-title' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TAB_ITEM' )
		);
	}

    /**
     * DEFINE setting options of shortcode in backend
     */
    public function backend_element_items()
    {
        $this->frontend_element_items();
    }

    /**
     * DEFINE setting options of shortcode in frontend
     */
    public function frontend_element_items()
    {
		$this->items = array(
			'Notab' => array(
				array(
					'name'  => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_HEADING' ),
					'id'    => 'heading',
					'type'  => 'text_field',
					'class' => 'jsn-input-xxlarge-fluid',
					'role'  => 'title',
					'std'   => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TAB_ITEM_STD' )
				),
				array(
					'name' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_BODY' ),
					'id'   => 'body',
					'role' => 'content',
					'type' => 'tiny_mce',
					'std'  => JSNPagebuilderHelpersType::loremText()
				),
				array(
					'name'      => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ICON' ),
					'id'        => 'icon',
					'type'      => 'icons',
					'std'       => '',
					'role'      => 'title_prepend',
					'role_type' => 'icon',
				),
			)
		);
	}

	/**
	 * DEFINE shortcode content
	 *
	 * @param type $atts
	 * @param type $content
	 */
	public function element_shortcode( $atts = null, $content = null ) {
		extract( JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts ) );
		$inner_content = JSNPagebuilderHelpersShortcode::removeAutop( $content );
		$inner_content = JSNPagebuilderHelpersFunctions::add_absolute_path_to_image_url($inner_content);
		return "
			<div id='pane_{index}' class='tab-pane {active} {fade_effect}' STYLE>
				{$inner_content}
			</div><!--seperate-->";
	}

}

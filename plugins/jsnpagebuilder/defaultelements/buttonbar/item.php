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
 * Buttonbar Item shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeButtonbarItem extends IG_Pb_Child {

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
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-select2/select2.min.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-select2/select2.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-linktype.js', 'js' );
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
		$this->config['shortcode'] = 'pb_buttonbar_item';
		$this->config['exception'] = array(
			'data-modal-title' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_BUTTONBAR_ITEM' )
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
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_TEXT' ),
					'id'      => 'button_text',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_BUTTONBAR_ITEM_STD' ),
					'role'    => 'title',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_BUTTONBAR_ITEM_DES' )
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_LINK_TYPE' ),
					'id'         => 'link_type',
					'type'       => 'select',
					'std'        => 'url',
					'options'    => JSNPagebuilderHelpersType::getLinkTypes(),
					'tooltip'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_LINK_TYPE_DES' ),
					'has_depend' => '1',
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_URL' ),
					'id'         => 'button_type_url',
					'type'       => 'text_field',
					'class'      => 'jsn-input-xxlarge-fluid',
					'std'        => 'http://',
					'tooltip'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_URL_DES' ),
					'dependency' => array( 'link_type', '=', 'url' )
				),
				//array(
				//	'name'  => JText::_( 'Single Item' ),
				//	'id'    => 'single_item',
				//	'type'  => 'array_',
				//	'std'   => '',
				//	'items' => IG_Pb_Helper_Type::get_single_item_button_bar(
				//		'link_type',
				//		array(
				//			'type'         => 'items_list',
				//			'options_type' => 'select',
				//			'class'        => 'select2-select',
				//			'ul_wrap'      => false,
				//		 )
				//	),
				//),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_OPEN_IN' ),
					'id'         => 'open_in',
					'type'       => 'select',
					'std'        => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getOpenInOptions() ),
					'options'    => JSNPagebuilderHelpersType::getOpenInOptions(),
					'tooltip'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_OPEN_IN_DES' ),
					'dependency' => array( 'link_type', '!=', 'no_link' )
				),
				array(
					'name'      => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ICON' ),
					'id'        => 'icon',
					'type'      => 'icons',
					'std'       => '',
					'role'      => 'title_prepend',
					'role_type' => 'icon',
					'tooltip'   => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ICON_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SIZE' ),
					'id'      => 'button_size',
					'type'    => 'select',
					'std'     => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getButtonSize() ),
					'options' => JSNPagebuilderHelpersType::getButtonSize(),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SIZE_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_COLOR' ),
					'id'      => 'button_color',
					'type'    => 'select',
					'std'     => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getButtonColor() ),
					'options' => JSNPagebuilderHelpersType::getButtonColor(),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_COLOR_DES' ),
					'container_class'   => 'color_select2',
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
	public function element_shortcode( $atts = null, $content = null ) {
		$arr_params   = JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts );
		extract( $arr_params );
		$button_text  = ( ! $button_text ) ? '' : $button_text;
		$button_size  = ( ! $button_size || strtolower( $button_size ) == 'default' ) ? '' : $button_size;
		$button_color = ( ! $button_color || strtolower( $button_color ) == 'default' ) ? '' : $button_color;
		$button_icon  = ( ! $icon ) ? '' : "<i class='{$icon}'></i>";
		$tag          = 'a';
		$href         = '';
		$script       = '';
		if ( ! empty( $link_type ) ) {
			switch ( $link_type ) {
				case 'no_link':
					$tag = 'button';
					break;
				case 'url':
					$href = ( ! $button_type_url ) ? ' href="#"' : " href='{$button_type_url}'";
					break;
			}
		}
		$target = '';
		if ( $open_in ) {
			switch ( $open_in ) {
				case 'current_browser':
					$target = '';
					break;
				case 'new_browser':
					$target = ' target="_blank"';
					break;
				case 'lightbox':
					$cls_button_fancy = ' pb-button-fancy';
					break;
			}
		}
		$button_type      = ( $tag == 'button' ) ? " type='button'" : '';
		$cls_button_fancy = ( ! isset( $cls_button_fancy ) ) ? '' : $cls_button_fancy;

		$html_result      = "<{$tag} class='btn {$button_size} {$button_color}{$cls_button_fancy}'{$href}{$target}{$button_type}>[icon]{$button_icon}[/icon][title]{$button_text}[/title]</{$tag}>";

		return $html_result . '<!--seperate-->';
	}

}

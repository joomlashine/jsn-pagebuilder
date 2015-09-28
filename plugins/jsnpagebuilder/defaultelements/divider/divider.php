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
 * Divider shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeDivider extends IG_Pb_Element {

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
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/js/colorpicker.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/css/colorpicker.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-colorpicker.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ELEMENT_URL.'/divider/assets/js/divider-setting.js', 'js' );
	}
	
	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config() {
		$this->config['shortcode'] = 'pb_divider';
		$this->config['name']      = JText::_( 'JSN_PAGEBUILDER_ELEMENT_DIVIDER' );
		$this->config['cat']       = JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_EXTRA' );
		$this->config['icon']      = 'icon-divider';
		$this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_DIVIDER_DES");
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
			'content' => array(
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE' ),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_DIVIDER_ELEMENT_TITLE_STD' ),
					'role'    => 'title',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES' )
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_BORDER' ),
					'type' => array(
						array(
							'id'           => 'div_border_width',
							'type'         => 'text_append',
							'type_input'   => 'number',
							'class'        => 'input-mini',
							'std'          => '2',
							'append'       => 'px',
							'validate'     => 'number',
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'div_border_style',
							'type'         => 'select',
							'class'        => 'input-medium',
							'std'          => 'solid',
							'options'      => JSNPagebuilderHelpersType::getBorderStyles(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'div_border_color',
							'type'         => 'color_picker',
							'std'          => '#E0DEDE',
							'parent_class' => 'combo-item',
						),
					),
					'tooltip'         => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_BORDER_DES' ),
					'container_class' => 'combo-group',
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
		$arr_params = JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts );
		extract( $arr_params );
		$styles = array();
		if ( $div_border_width ) {
			$styles[] = 'border-bottom-width:' . intval( $div_border_width ) . 'px';
		}
		if ( $div_border_style ) {
			$styles[] = 'border-bottom-style:' . $div_border_style;
		}
		if ( $div_border_color ) {
			$styles[] = 'border-bottom-color:' . urldecode( $div_border_color );
		}
		//if ( $div_margin_top ) {
		//	$styles[] = 'margin-top:' . intval( $div_margin_top ) . 'px';
		//}
		//if ( $div_margin_bottom ) {
		//	$styles[] = 'margin-bottom:' . intval( $div_margin_bottom ) . 'px';
		//}
		if ( count( $styles ) > 0 ) {
			$html_element = '<div style="' . implode( ';', $styles ) . '"></div><div style="clear: both"></div>';
		} else {
			$html_element = '';
		}
		return $this->element_wrapper( $html_element, $arr_params );
	}

}

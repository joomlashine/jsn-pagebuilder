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
 * QRCode shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeQRCode extends IG_Pb_Element {

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
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ELEMENT_URL.'/qrcode/assets/js/qrcode-setting.js', 'js' );
	}
	
	/**
	 * DEFINE configuration information of shortcode
	 * 
	 * @return type
	 */
	public function element_config() {
		$this->config['shortcode'] = 'pb_qrcode';
		$this->config['name']      = JText::_( 'JSN_PAGEBUILDER_ELEMENT_QRCODE' );
		$this->config['cat']       = JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_EXTRA' );
		$this->config['icon']      = 'icon-qr-code';
		$this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_QRCODE_DES");
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
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_QRCODE_ELEMENT_TITLE_STD' ),
					'role'    => 'title',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES' )
				),
				array(
					'name'          => JText::_( 'JSN_PAGEBUILDER_ELEMENT_QRCODE_DATA' ),
					'id'            => 'qr_content',
					'type'          => 'text_area',
					'class'         => 'jsn-input-xxlarge-fluid',
					'std'           => 'http://www.joomlashine.com',
					'tooltip'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_QRCODE_DATA_DES' ),
					'exclude_quote' => '1',
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_QRCODE_IMAGE_ALT_TEXT' ),
					'id'      => 'qr_alt',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_QRCODE_IMAGE_ALT_TEXT_STD' ),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_QRCODE_IMAGE_ALT_TEXT_DES' ),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_CONTAINER_STYLE' ),
					'id'      => 'qr_container_style',
					'type'    => 'select',
					'std'     => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getQRContainerStyle() ),
					'options' => JSNPagebuilderHelpersType::getQRContainerStyle(),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_CONTAINER_STYLE_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT' ),
					'id'      => 'qr_alignment',
					'type'    => 'select',
					'std'     => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getTextAlign() ),
					'options' => JSNPagebuilderHelpersType::getTextAlign(),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_DES' )
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_QRCODE_SIZE' ),
					'id'         => 'qrcode_sizes',
					'type'       => 'text_append',
					'type_input' => 'number',
					'class'      => 'input-mini',
					'std'        => '150',
					'append'     => 'px',
					'validate'   => 'number',
					'tooltip'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_QRCODE_SIZE_DES' )
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
		$html_element  = '';
		$arr_params    = ( JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts ) );
		extract( $arr_params );
		$qrcode_sizes  = ( $qrcode_sizes ) ? ( int ) $qrcode_sizes : 0;
		$cls_alignment = '';
		if ( strtolower( $arr_params['qr_alignment'] ) != 'inherit' ) {
			if ( strtolower( $arr_params['qr_alignment'] ) == 'left' )
				$cls_alignment = "class='pull-left'";
			if ( strtolower( $arr_params['qr_alignment'] ) == 'right' )
				$cls_alignment = "class='pull-right'";
			if ( strtolower( $arr_params['qr_alignment'] ) == 'center' )
				$cls_alignment = "class='text-center'";
		}
		$class_img    = ( $qr_container_style != 'no-styling' ) ? "class='{$qr_container_style}'" : '';
		$qr_content   = str_replace( '<pb_quote>', '"', $qr_content );
		$image        = 'https://chart.googleapis.com/chart?chs=' . $qrcode_sizes . 'x' . $qrcode_sizes . '&cht=qr&chld=H|1&chl=' . $qr_content;
		$qr_alt       = ( ! empty( $qr_alt ) ) ? "alt='{$qr_alt}'" : '';
		$html_element = "<img src='{$image}' {$qr_alt} width='{$qrcode_sizes}' height='{$qrcode_sizes}' $class_img />";
		if ($cls_alignment != '')
			$html_element = "<div {$cls_alignment}>{$html_element}</div>";
            $html_element .= '<div style="clear: both"></div>';

		return $this->element_wrapper( $html_element, $arr_params );
	}

}

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
 * Alert shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeAlert extends IG_Pb_Element {

	/**
	 * Constructor
	 *
	 * @return type
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config() {
		$this->config['shortcode'] = 'pb_alert';
		$this->config['name']      = JText::_( 'JSN_PAGEBUILDER_ELEMENT_ALERT' );
		$this->config['cat']       = JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_TYPOGRAPHY' );
		$this->config['icon']      = 'icon-alert';
		$this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_ALERT_DESCRIPTION");
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
			'content' => array(
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE' ),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_ALERT_ELEMENT_TITLE_STD' ),
					'role'    => 'title',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES' )
				),
				array(
					'name'  => JText::_( 'JSN_PAGEBUILDER_ELEMENT_ALERT_CONTENT' ),
					'id'    => 'alert_content',
					'type'  => 'tiny_mce',
					'role'  => 'content',
					'rows'  => '12',
					'std'   => JSNPagebuilderHelpersType::loremText()
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_STYLE' ),
					'id'      => 'alert_style',
					'type'    => 'select',
					'std'     => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getAlertType() ),
					'options' => JSNPagebuilderHelpersType::getAlertType(),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_ALERT_STYLE_TOOLTIP' )
				),
				array(
					'name'		=> JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALLOW_TO_CLOSE' ),
					'id'		=> 'alert_close',
					'type'		=> 'radio',
					'std'		=> 'no',
					'options'	=> array( 'yes' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ), 'no' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' ) ),
					'tooltip'	=> JText::_('JSN_PAGEBUILDER_ELEMENT_ALERT_ALLOW_TO_CLOSE_TOOLTIP'),
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
		$arr_params	   = ( JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts ) );
		extract( $arr_params );
		$alert_style   = ( ! $arr_params['alert_style'] ) ? '' : $arr_params['alert_style'];
		$alert_close   = ( ! $arr_params['alert_close'] || $arr_params['alert_close'] == 'no' ) ? '' : '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		$alert_dismis  = ( ! $arr_params['alert_close'] || $arr_params['alert_close'] == 'no' ) ? '' : ' alert-dismissable';
		$content      = ( ! $content ) ? $alert_content : $content;
		$content = JSNPagebuilderHelpersFunctions::add_absolute_path_to_image_url($content);
		$html_element .= "<div class='alert {$alert_style}{$alert_dismis}'>";
		$html_element .= $alert_close;
		$html_element .= $content;
		$html_element .= '</div>';
        $html_element .= '<div style="clear: both"></div>';
		return $this->element_wrapper( $html_element, $arr_params );
	}

}

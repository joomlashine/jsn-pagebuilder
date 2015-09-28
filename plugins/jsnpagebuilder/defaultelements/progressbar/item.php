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
 * Progress Bar Item shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeProgressbarItem extends IG_Pb_Child {

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
		$this->config['shortcode'] = 'pb_progressbar_item';
		$this->config['exception'] = array(
			'item_text'        => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR' ),
			'data-modal-title' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR_ITEM' )
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
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_TITLE' ),
					'id'      => 'pbar_text',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR_ITEM_TITLE_STD' ),
					'role'    => 'title',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR_ITEM_TITLE_DES' )
				),
				array(
					'name'         => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_PERCENTAGE' ),
					'id'           => 'pbar_percentage',
					'type'         => 'text_append',
					'type_input'   => 'number',
					'class'        => 'input-mini',
					'std'          => '25',
					'append'       => '%',
					'validate'     => 'number',
					'parent_class' => 'combo-item',
					'tooltip'      => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_PERCENTAGE_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_COLOR' ),
					'id'      => 'pbar_color',
					'type'    => 'select',
					'std'     => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getProgressBarColor() ),
					'options' => JSNPagebuilderHelpersType::getProgressBarColor(),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_COLOR_DES' ),
					'container_class'   => 'color_select2',
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_STYLE' ),
					'id'      => 'pbar_item_style',
					'type'    => 'select',
					'std'     => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getProgressBarItemStyle() ),
					'options' => JSNPagebuilderHelpersType::getProgressBarItemStyle(),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_STYLE_DES' )
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
					'id'    => 'pbar_group',
					'class' => 'pbar_group_type',
					'type'  => 'hidden',
					'std'   => 'multiple-bars',
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
		extract( JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts ) );
		$pbar_percentage       = floatval( $pbar_percentage );
		$pbar_color            = ( strtolower( $pbar_color ) == 'default' || empty( $pbar_color ) ) ? $pbar_color = '' : ' ' . $pbar_color;
		$pbar_percentage_width = ( ! $pbar_percentage ) ? '' : ' style="width: ' . $pbar_percentage . '%"';
		$pbar_value			   = ( ! $pbar_percentage ) ? '' : ' aria-valuenow="' . $pbar_percentage . '"';
		$pbar_item_style       = ( ! $pbar_item_style || strtolower( $pbar_item_style ) == 'solid' ) ? '' : $pbar_item_style;
		if ( $pbar_item_style == 'striped' ) {
			$pbar_item_style = ' progress-striped';
		}

		$icon    = ( ! $icon ) ? '' : "<i class='{$icon}'></i>";
		$html_content = "[icon]{$icon}[/icon][text]{$pbar_text}[/text]";

		// Add title progressbar
		$html_content = "<div class='progress-info'[width]><span class='progress-title'>{$html_content}</span>[percentage]<span class='progress-percentage'>{$pbar_percentage}%</span>[/percentage]</div>";

		if ( $pbar_group == 'stacked' ) {
			$html_sub_elm = '[sub_content]' . $html_content . '[/sub_content]';
			$html_sub_elm .= "<div class='progress-bar{$pbar_color}{$pbar_item_style}'{$pbar_percentage_width}></div>";
		} else {
			$html_sub_elm = '[sub_content]' . $html_content . '[/sub_content]';
			$html_sub_elm .= "<div class='progress{$pbar_item_style}{active}'>";
			$html_sub_elm .= "<div class='progress-bar {$pbar_color}' role='progressbar'{$pbar_value}aria-valuemin='0' aria-valuemax='100'{$pbar_percentage_width}></div>";
			$html_sub_elm .= '</div>';
		}

		return $html_sub_elm . '<!--seperate-->';
	}

}

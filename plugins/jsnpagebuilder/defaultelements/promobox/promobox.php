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
 * Promobox shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodePromobox extends IG_Pb_Element {

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
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/js/colorpicker.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/css/colorpicker.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-fontselector.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-popover.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-colorpicker.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-linktype.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ELEMENT_URL.'/promobox/assets/js/promobox-setting.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ELEMENT_URL.'/promobox/assets/css/promobox-setting.css', 'css' );
	}
	
	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config() {
		$this->config['shortcode'] = 'pb_promobox';
		$this->config['name']      = JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX' );
		$this->config['cat']       = JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_TYPOGRAPHY' );
		$this->config['icon']      = 'icon-promotion-box';
		$this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_PROMOBOX_DES");
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
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_ELEMENT_TITLE_STD' ),
					'role'    => 'title',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES' )
				),
				array(
					'id'      => 'pb_title',
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_PROMOTION_TITLE' ),
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_PROMOTION_TITLE_STD' ),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_PROMOTION_TITLE_DES' )
				),
				array(
					'id'      => 'pb_content',
					'role'    => 'content',
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_PROMOTION_CONTENT' ),
					'type'    => 'tiny_mce',
					'rows'    => '12',
					'std'     => JSNPagebuilderHelpersType::loremText(),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_PROMOTION_CONTENT_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BUTTON_TITLE' ),
					'id'      => 'pb_button_title',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => 'Button Title',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BUTTON_TITLE_DES' )
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BUTTON_LINK' ),
					'id'         => 'link_type',
					'type'       => 'select',
					'std'        => 'url',
					'options'    => JSNPagebuilderHelpersType::getLinkTypes(),
					'tooltip'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BUTTON_LINK_DES' ),
					'has_depend' => '1',
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_URL' ),
					'id'         => 'pb_button_url',
					'type'       => 'text_field',
					'class'      => 'jsn-input-xxlarge-fluid',
					'std'        => 'http://',
					'tooltip'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_URL_DES' ),
					'dependency' => array( 'link_type', '=', 'url' )
				),
				//array(
				//	'name'  => JText::_( 'Single Item' ),
				//	'id'    => 'single_item',
				//	'type'  => 'type_group',
				//	'std'   => '',
				//	'items' => JSNPagebuilderHelpersType::get_single_item_button_bar(
				//		'link_type', array(
				//			'type'         => 'items_list',
				//			'options_type' => 'select',
				//			'class'        => 'select2-select',
				//			'ul_wrap'      => false,
				//		)
				//	),
				//),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_OPEN_IN' ),
					'id'         => 'pb_button_open_in',
					'type'       => 'select',
					'std'        => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getOpenInOptions() ),
					'options'    => JSNPagebuilderHelpersType::getOpenInOptions(),
					'tooltip'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_OPEN_IN_DES' ),
					'dependency' => array( 'link_type', '!=', 'no_link' )
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BACKGROUND_COLOR' ),
					'type' => array(
						array(
							'id'           => 'pb_bg_value',
							'type'         => 'text_field',
							'class'        => 'input-small',
							'std'          => '#F6F6F6',
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'pb_bg_color',
							'type'         => 'color_picker',
							'std'          => '#F6F6F6',
							'parent_class' => 'combo-item',
						),
					),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BACKGROUND_COLOR_DES' ),
					'container_class' => 'combo-group',
				),
				array(
					'name'             => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_BORDER' ),
					'container_class'  => 'combo-group',
					'id'               => 'pb_border',
					'type'             => 'margin',
					'extended_ids'     => array( 'pb_border_top', 'pb_border_right', 'pb_border_bottom', 'pb_border_left' ),
					'pb_border_top'    => array( 'std' => '5' ),
					'pb_border_right'  => array( 'std' => '5' ),
					'pb_border_bottom' => array( 'std' => '5' ),
					'pb_border_left'   => array( 'std' => '5' ),
					'tooltip'          => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_BORDER_DES' )
				),
				array(
					'name' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BORDER_COLOR' ),
					'type' => array(
						array(
							'id'           => 'pb_border_value',
							'type'         => 'text_field',
							'class'        => 'input-small',
							'std'          => '#A0CE4E',
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'pb_border_color',
							'type'         => 'color_picker',
							'std'          => '#A0CE4E',
							'parent_class' => 'combo-item',
						),
					),
					'tooltip'         => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BORDER_COLOR_DES' ),
					'container_class' => 'combo-group',
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_SHOW_SHADOW' ),
					'id'      => 'pb_show_drop',
					'type'    => 'radio',
					'std'     => 'yes',
					'options' => array( 'yes' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ), 'no' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' ) ),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_SHOW_SHADOW_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENTS' ),
					'id'      => 'elements',
					'type'    => 'items_list',
					'std'     => 'title__#__content__#__button',
					'options' => array(
						'title'   => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_TITLE' ),
						'content' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_CONTENT' ),
						'button'  => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BUTTON' )
					),
					'options_type'    => 'checkbox',
					'popover_items'   => array( 'title', 'button' ),
					'tooltip'         => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_ELEMENTS_DES' ),
					'style'           => array( 'height' => '200px' ),
					'container_class' => 'unsortable',
				),
				// Popup settings for Title
				array(
					'name'              => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT' ),
					'id'                => 'title_font',
					'type'              => 'select',
					'std'               => 'inherit',
					'options'           => array( 'inherit' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_INHERIT' ), 'custom' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_CUSTOM' ) ),
					'has_depend'        => '1',
					'class'             => 'input-medium',
					'tooltip'           => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_DES' ),
					'container_class'   => 'hidden',
					'data_wrap_related' => 'title',
				),
				array(
					'name' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_FACE' ),
					'id'   => 'title_font_family',
					'type' => array(
						array(
							'id'           => 'title_font_face_type',
							'type'         => 'jsn_select_font_type',
							'class'        => 'input-medium',
							'std'          => 'standard fonts',
							'options'      => JSNPagebuilderHelpersType::getFonts(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'title_font_face_value',
							'type'         => 'jsn_select_font_value',
							'class'        => 'input-medium',
							'std'          => 'Verdana',
							'options'      => '',
							'parent_class' => 'combo-item',
						),
					),
					'dependency'        => array( 'title_font', '=', 'custom' ),
					'tooltip'           => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_FACE_DES' ),
					'container_class'   => 'combo-group hidden',
					'data_wrap_related' => 'title',
				),
				array(
					'name' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_ATTRIBUTES' ),
					'type' => array(
						array(
							'id'           => 'title_font_size',
							'type'         => 'text_append',
							'type_input'   => 'number',
							'class'        => 'input-mini',
							'std'          => '',
							'append'       => 'px',
							'validate'     => 'number',
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'title_font_style',
							'type'         => 'select',
							'class'        => 'input-medium',
							'std'          => 'bold',
							'options'      => JSNPagebuilderHelpersType::getFontStyles(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'title_font_color',
							'type'         => 'color_picker',
							'std'          => '#000000',
							'parent_class' => 'combo-item',
						),
					),
					'dependency'        => array( 'title_font', '=', 'custom' ),
					'tooltip'           => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_ATTRIBUTES_DES' ),
					'container_class'   => 'combo-group hidden',
					'data_wrap_related' => 'title',
				),
				array(
					'name' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BOTTOM_PADDING' ),
					'type' => array(
						array(
							'id'         => 'title_padding_bottom',
							'type'       => 'text_append',
							'type_input' => 'number',
							'class'      => 'input-mini',
							'std'        => '',
							'append'     => 'px',
							'validate'   => 'number',
						),
					),
					'tooltip'           => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BOTTOM_PADDING_DES' ),
					'container_class'   => 'hidden',
					'data_wrap_related' => 'title',
				),
				array(
					'name' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BOTTOM_MARGIN' ),
					'type' => array(
						array(
							'id'         => 'title_margin_bottom',
							'type'       => 'text_append',
							'type_input' => 'number',
							'class'      => 'input-mini',
							'std'        => '',
							'append'     => 'px',
							'validate'   => 'number',
						),
					),
					'tooltip'           => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BOTTOM_MARGIN_DES' ),
					'container_class'   => 'hidden',
					'data_wrap_related' => 'title',
				),
				array(
					'name'              => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SIZE' ),
					'id'                => 'pb_button_size',
					'type'              => 'select',
					'std'               => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getButtonSize() ),
					'options'           => JSNPagebuilderHelpersType::getButtonSize(),
					'tooltip'           => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SIZE_DES' ),
					'container_class'   => 'hidden',
					'data_wrap_related' => 'button',
				),
				array(
					'name'              => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BUTTON_COLOR' ),
					'id'                => 'pb_button_color',
					'type'              => 'select',
					'std'               => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getButtonColor() ),
					'options'           => JSNPagebuilderHelpersType::getButtonColor(),
					'tooltip'           => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROMOBOX_BUTTON_COLOR_DES' ),
					'container_class'   => 'hidden color_select2',
					'data_wrap_related' => 'button',
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
		JSNPagebuilderHelpersFunctions::loadFancyboxJS();
		$document = JFactory::getDocument();
		$document->addScript( JSNPB_ELEMENT_URL.'/promobox/assets/js/promobox.js', 'text/javascript' );
		$document->addStyleSheet( JSNPB_ELEMENT_URL.'/promobox/assets/css/promobox.css', 'text/css' );
		
		$html_element = '';
		$arr_params   = JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts );
		extract( $arr_params );
		$styles       = array();
		if ( $pb_bg_color ) {
			$styles[] = 'background-color:' . $pb_bg_color;
		}
		if ( intval( $pb_border_top ) > 0 ) {
			$styles[] = 'border-top-width:' . ( int ) $pb_border_top . 'px';
			$styles[] = 'border-top-style: solid';
		}
		if ( intval( $pb_border_left ) > 0 ) {
			$styles[] = 'border-left-width:' . ( int ) $pb_border_left . 'px';
			$styles[] = 'border-left-style: solid';
		}
		if ( intval( $pb_border_bottom ) > 0 ) {
			$styles[] = 'border-bottom-width:' . ( int ) $pb_border_bottom . 'px';
			$styles[] = 'border-bottom-style: solid';
		}
		if ( intval( $pb_border_right ) > 0 ) {
			$styles[] = 'border-right-width:' . ( int ) $pb_border_right . 'px';
			$styles[] = 'border-right-style: solid';
		}
		if ( $pb_border_color ) {
			$styles[] = 'border-color:' . $pb_border_color;
		}

		$elements = explode( '__#__', $elements );
		$class    = '';
		if ( $pb_show_drop == 'yes' ) {
			$class .= 'promo-box-shadow';
		}
		
		$cls_button_fancy = $target = $button = '';
		if ( in_array( 'button', $elements ) ) {
			switch ( $link_type ) {
				case 'no_link':
					$button_href = '';
					break;
				case 'url':
					$button_href = ( ! $pb_button_url ) ? ' href="#"' : " href='{$pb_button_url}'";
					break;
			}

			if ( $pb_button_open_in AND $link_type != 'no_link' ) {
				switch ( $pb_button_open_in ) {
					case 'current_browser':
						$target = '';
						break;
					case 'new_browser':
						$target = ' target="_blank"';
						break;
					case 'lightbox':
						$cls_button_fancy = ' pb-pb-button-fancy';
						break;
				}
			}

			$pb_button_size = ( isset( $pb_button_size ) && $pb_button_size != 'default' ) ? $pb_button_size : '';
			$pb_button_color = ( isset( $pb_button_color ) && $pb_button_color != 'default' ) ? $pb_button_color : '';
			$button = "<a class='pull-right btn {$pb_button_size} {$pb_button_color} {$cls_button_fancy}' {$target} {$button_href}>{$pb_button_title}</a>";
		}
		$styles = implode( ';', $styles );
		$styles = ( $styles ) ? "style='{$styles}'" : '';
		$html_element .= "<div class='pb-promobox'>";
		$html_element .= "<section class='{$class}' {$styles}>";
		$html_element .= $button;
		if ( in_array( 'title', $elements ) ) {
			$style_title = array();
			if ( $title_font == 'custom' ) {
				if ( $title_font_face_type == 'google fonts' ) {
					$document = JFactory::getDocument();
					$document->addStyleSheet( "http://fonts.googleapis.com/css?family={$title_font_face_value}", 'text/css' );
					
					$style_title[] = 'font-family:' . $title_font_face_value;
				} elseif ( $title_font_face_value ) {
					$style_title[] = 'font-family:' . $title_font_face_value;
				}
				if ( intval( $title_font_size ) > 0 ) {
					$style_title[] = 'font-size:' . intval( $title_font_size ) . 'px';
				}
				switch ( $title_font_style ) {
					case 'bold':
						$style_title[] = 'font-weight:700';
						break;
					case 'italic':
						$style_title[] = 'font-style:italic';
						break;
					case 'normal':
						$style_title[] = 'font-weight:normal';
						break;
				}
				if ( strpos( $title_font_color, '#' ) !== false ) {
					$style_title[] = 'color:' . $title_font_color;
				}
			}
			if ( $title_padding_bottom ) {
				$style_title[] = 'padding-bottom:' . $title_padding_bottom . 'px';
			}
			if ( $title_margin_bottom ) {
				$style_title[] = 'margin-bottom:' . $title_margin_bottom . 'px';
			}
			if ( count( $style_title ) ) {
				$style_title = 'style="' . implode( ';', $style_title ) . '"';
			} else
				$style_title = '';
			$html_element .= "<h2 {$style_title}>{$pb_title}</h2>";
		}
		$content = ( ! $content ) ? $pb_content : $content;
		$content = JSNPagebuilderHelpersFunctions::add_absolute_path_to_image_url($content);
		if ( in_array( 'content', $elements ) )
			$html_element .= "<p>{$content}</p>";
		$html_element .= '</section>';
		$html_element .= '</div>';
        $html_element .= "<div style='clear: both'></div>";

		return $this->element_wrapper( $html_element, $arr_params );
	}

}

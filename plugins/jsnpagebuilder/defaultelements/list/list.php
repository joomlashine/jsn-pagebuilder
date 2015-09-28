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
 * List shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeList extends IG_Pb_Element {

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
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-select2/select2.min.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-select2/select2.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-fontselector.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/js/colorpicker.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/css/colorpicker.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-colorpicker.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-fonticomoon.css', 'css' );
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/font-awesome/css/font-awesome.min.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ELEMENT_URL.'/list/assets/js/list-setting.js', 'js' );
	}
	
	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config() {
		$this->config['shortcode']        = 'pb_list';
		$this->config['name']             = JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST' );
		$this->config['cat']              = JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_TYPOGRAPHY' );
		$this->config['icon']             = 'icon-list';
		$this->config['description']      = JText::_("JSN_PAGEBUILDER_ELEMENT_LIST_DES");
		$this->config['has_subshortcode'] = __CLASS__ . 'Item';
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
			'action' => array(
				array(
					'id'      => 'btn_convert',
					'type'    => 'button_group',
					'bound'   => 0,
					'actions' => array(
						array(
							'std'         => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ACTION_TAB' ),
							'action_type' => 'convert',
							'action'      => 'list_to_tab',
						),
						array(
							'std'         => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ACTION_ACCORDION' ),
							'action_type' => 'convert',
							'action'      => 'list_to_accordion',
						),
						array(
							'std'         => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ACTION_CAROUSEL' ),
							'action_type' => 'convert',
							'action'      => 'list_to_carousel',
						),
					)
				),
			),
			'content' => array(
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE' ),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST_ELEMENT_TITLE_STD' ),
					'role'    => 'title',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES' )
				),
				array(
					'id'            => 'list_items',
					'name'          => JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST_ITEMS' ),
					'type'          => 'group',
					'shortcode'     => $this->config['shortcode'],
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array( 'std' => '' ),
						array( 'std' => '' ),
					),
					'label_item'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST_ITEMS_LABEL' ),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SHOW_ICON' ),
					'id'         => 'show_icon',
					'type'       => 'radio',
					'std'        => 'yes',
					'options'    => array( 'yes' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ), 'no' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' ) ),
					'tooltip'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_SHOW_ICON_DES' ),
					'has_depend' => '1',
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST_ICON_POSITION' ),
					'id'         => 'icon_position',
					'type'       => 'select',
					'class'      => 'input-small',
					'std'        => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getIconPosition() ),
					'options'    => JSNPagebuilderHelpersType::getIconPosition(),
					'tooltip'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST_ICON_POSITION_DES' ),
					'dependency' => array( 'show_icon', '=', 'yes' )
				),
				array(
					'name' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST_ICON_BACKGROUND' ),
					'type' => array(
						array(
							'id'           => 'icon_size_value',
							'type'         => 'select',
							'class'        => 'input-mini',
							'std'          => '32',
							'options'      => JSNPagebuilderHelpersType::getIconSizes(),
							'parent_class' => 'combo-item input-append',
							'append_text'  => 'px',
						),
						array(
							'id'           => 'icon_background_type',
							'type'         => 'select',
							'class'        => 'input-small',
							'std'          => 'circle',
							'options'      => JSNPagebuilderHelpersType::getIconBackground(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'icon_background_color',
							'type'         => 'color_picker',
							'std'          => '#0088CC',
							'parent_class' => 'combo-item',
						),
					),
					'tooltip'         => JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST_ICON_BACKGROUND_DES' ),
					'container_class' => 'combo-group',
					'dependency'      => array( 'show_icon', '=', 'yes' )
				),
				array(
					'name' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST_ICON_COLOR' ),
					'type' => array(
						array(
							'id'           => 'icon_c_value',
							'type'         => 'text_field',
							'class'        => 'input-small',
							'std'          => '#FFFFFF',
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'icon_c_color',
							'type'         => 'color_picker',
							'std'          => '#ffffff',
							'parent_class' => 'combo-item',
						),
					),
					'tooltip'         => JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST_ICON_COLOR_DES' ),
					'container_class' => 'combo-group',
					'dependency'      => array( 'show_icon', '=', 'yes' )
				),
				array(
					'type' => 'hr',
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST_SHOW_HEADING' ),
					'id'         => 'show_heading',
					'type'       => 'radio',
					'std'        => 'yes',
					'options'    => array( 'yes' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ), 'no' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' ) ),
					'tooltip'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST_SHOW_HEADING_DES' ),
					'has_depend' => '1',
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST_HEADING_FONT' ),
					'id'         => 'font',
					'type'       => 'select',
					'std'        => 'inherit',
					'options'    => array( 'inherit' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_INHERIT' ), 'custom' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_CUSTOM' ) ),
					'has_depend' => '1',
					'tooltip'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_LIST_HEADING_FONT_DES' ),
					'class'      => 'input-medium',
					'dependency' => array( 'show_heading', '=', 'yes' )
				),
				array(
					'name' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_FACE' ),
					'id'   => 'font-family',
					'type' => array(
						array(
							'id'           => 'font_face_type',
							'type'         => 'jsn_select_font_type',
							'class'        => 'input-medium',
							'std'          => 'standard fonts',
							'options'      => JSNPagebuilderHelpersType::getFonts(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'font_face_value',
							'type'         => 'jsn_select_font_value',
							'class'        => 'input-medium',
							'std'          => 'Verdana',
							'options'      => '',
							'parent_class' => 'combo-item',
						),
					),
					'dependency'      => array( 'font', '=', 'custom' ),
					'tooltip'         => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_FACE_DES' ),
					'container_class' => 'combo-group',
				),
				array(
					'name' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_ATTRIBUTES' ),
					'id'   => 'font-attributes',
					'type' => array(
						array(
							'id'           => 'font_size_value',
							'type'         => 'text_append',
							'type_input'   => 'number',
							'class'        => 'input-mini',
							'std'          => '',
							'append'       => 'px',
							'validate'     => 'number',
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'font_style',
							'type'         => 'select',
							'class'        => 'input-medium',
							'std'          => 'bold',
							'options'      => JSNPagebuilderHelpersType::getFontStyles(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'color',
							'type'         => 'color_picker',
							'std'          => '#000000',
							'parent_class' => 'combo-item',
						),
					),
					'dependency'      => array( 'font', '=', 'custom' ),
					'tooltip'         => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_ATTRIBUTES_DES' ),
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
		$document = JFactory::getDocument();
		$document->addStyleSheet( JSNPB_ELEMENT_URL."/list/assets/css/list.css", 'text/css' );
		
		$arr_params = ( JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts ) );
		$link = '';
		$exclude_params = array( 'tag', 'text', 'preview' );

		$arr_styles = array();
		if ( strtolower( $arr_params['font_face_type'] ) == 'google fonts' AND $arr_params['font'] != 'inherit' ) {
			$document->addStyleSheet( "http://fonts.googleapis.com/css?family={$arr_params['font_face_value']}", 'text/css' );
		}

		if ( $arr_params['font'] != 'inherit' ) {
			if ($arr_params['font_face_value'])
				$arr_styles[] = 'font-family: ' . $arr_params['font_face_value'];
			if ($arr_params['font_size_value'])
				$arr_styles[] = 'font-size: ' . $arr_params['font_size_value'] . 'px';
			if ($arr_params['color'])
				$arr_styles[] = 'color: ' . $arr_params['color'];
			if ($arr_params['font_style'] == 'bold')
				$arr_styles[] = 'font-weight: 700 !important';
			else if ($arr_params['font_style'] == 'normal')
				$arr_styles[] = 'font-weight: normal !important';
			else
				$arr_styles[] = 'font-style: ' . $arr_params['font_style'];
		}

		$arr_icon_styles = array();
		$arr_icon_class = array();
		$arr_icon_class[] = '';
		if ( $arr_params['icon_position'] ) {
			$icon_position    = strtolower( $arr_params['icon_position'] );
			$arr_icon_class[] = ( $icon_position != 'inherit' ) ? "pb-position-{$icon_position}" : '';
		}
		if (strtolower( $arr_params['icon_background_type'] ) != '' )
			$arr_icon_class[] = "pb-shape-{$arr_params['icon_background_type']}";
		if ( $arr_params['icon_size_value'] ) {
			$arr_icon_class[] = "pb-icon-{$arr_params['icon_size_value']}";
		}

		if ($arr_params['icon_background_color'])
			$arr_icon_styles[] = 'background-color: ' . $arr_params['icon_background_color'];
		if ( $arr_params['icon_c_color'] ) {
			$arr_icon_styles[] = 'color: ' . $arr_params['icon_c_color'];
		}

		$html_elements = '';
		$sub_shortcode = empty($content) ? JSNPagebuilderHelpersShortcode::removeAutop($content) : JSNPagebuilderHelpersBuilder::generateShortCode($content, false, 'frontend', true);
		$items         = explode( '<!--seperate-->', $sub_shortcode );
		// remove empty element
		$items         = array_filter( $items );
		$initial_open  = ( ! isset( $initial_open ) || $initial_open > count( $items ) ) ? 1 : $initial_open;
		foreach ( $items as $idx => $item ) {
			$open        = ( $idx + 1 == $initial_open ) ? 'in' : '';
			$items[$idx] = $item;
		}
		if ( ! empty( $sub_shortcode ) ) {
			$parent_class  = implode( ' ', $arr_icon_class );
			$html_elements = "<ul class='pb-list-icons {$parent_class}'>";
			$sub_htmls     = $sub_shortcode;
			$sub_htmls     = str_replace( 'pb-sub-icons', 'pb-icon-base', $sub_htmls );
			$sub_htmls     = str_replace( 'pb-styles', implode( ';', $arr_icon_styles ), $sub_htmls );
			$sub_htmls     = str_replace( 'pb-list-title', implode( ';', $arr_styles ), $sub_htmls );

			if ( $arr_params['show_icon'] == 'no' ) {
				$pattern   = '\\[(\\[?)(icon)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
				$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
			} else {
				$sub_htmls = str_replace( '[icon]', '', $sub_htmls );
				$sub_htmls = str_replace( '[/icon]', '', $sub_htmls );
			}

			if ( $arr_params['show_heading'] == 'no' ) {
				$pattern   = '\\[(\\[?)(heading)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
				$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
			} else {
				$sub_htmls = str_replace( '[heading]', '', $sub_htmls );
				$sub_htmls = str_replace( '[/heading]', '', $sub_htmls );
			}

			$html_elements .= $sub_htmls;
			$html_elements .= '</ul>';
            $html_elements .= '<div style="clear: both"></div>';
		}

		return $this->element_wrapper( $link . $html_elements, $arr_params );
	}

}

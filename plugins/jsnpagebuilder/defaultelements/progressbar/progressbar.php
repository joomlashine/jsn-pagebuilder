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
 * Progress Bar shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeProgressbar extends IG_Pb_Element {

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
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ELEMENT_URL.'/progressbar/assets/js/progressbar-setting.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-fonticomoon.css', 'css' );
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/font-awesome/css/font-awesome.min.css', 'css');
	}
	
	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config() {
		$this->config['shortcode']        = 'pb_progressbar';
		$this->config['name']             = JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR' );
		$this->config['cat']              = JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_TYPOGRAPHY' );
		$this->config['icon']             = 'icon-progress-bar';
		$this->config['description'] 	  = JText::_("JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR_DES");
		$this->config['has_subshortcode'] = __CLASS__ . 'Item';
		$this->config['exception']        = array(
			'default_content'  => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR' ),
			'data-modal-title' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR' )
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
			'content' => array(
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE' ),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR_ELEMENT_TITLE_STD' ),
					'role'    => 'title',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES' )
				),
				array(
					'id'            => 'progress_bar_items',
					'name'          => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR_ITEMS' ),
					'type'          => 'group',
					'shortcode'     => $this->config['shortcode'],
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array( 'std' => '' ),
						array( 'std' => '' ),
						array( 'std' => '' ),
					),
					'label_item'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR_ITEMS_LABEL' ),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_PRESENTATION' ),
					'id'      => 'progress_bar_style',
					'type'    => 'select',
					'std'     => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getProgressBarStyle() ),
					'options' => JSNPagebuilderHelpersType::getProgressBarStyle(),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_PRESENTATION_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SHOW_ICON' ),
					'id'      => 'progress_bar_show_icon',
					'type'    => 'radio',
					'std'     => 'yes',
					'options' => array( 'yes' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ), 'no' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' ) ),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SHOW_ICON_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SHOW_TITLE' ),
					'id'      => 'progress_bar_show_title',
					'type'    => 'radio',
					'std'     => 'yes',
					'options' => array( 'yes' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ), 'no' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' ) ),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SHOW_TITLE_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR_SHOW_PERCENTAGE' ),
					'id'      => 'progress_bar_show_percent',
					'type'    => 'radio',
					'std'     => 'yes',
					'options' => array( 'yes' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ), 'no' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' ) ),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR_SHOW_PERCENTAGE_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR_MAKE_ACTIVE' ),
					'id'      => 'progress_bar_stack_active',
					'type'    => 'radio',
					'std'     => 'no',
					'options' => array( 'yes' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ), 'no' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' ) ),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PROGRESSBAR_MAKE_ACTIVE_DES' )
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
		$document->addStyleSheet( JSNPB_ELEMENT_URL.'/progressbar/assets/css/progressbar.css', 'text/css' );
		
		$arr_params   = JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts );
		$html_element = '';
		if ( $arr_params['progress_bar_stack_active'] == 'yes' ) {
			$content = str_replace( 'pbar_item_style="solid"', 'pbar_item_style="striped"', $content );
		}

		$sub_shortcode = empty($content) ? JSNPagebuilderHelpersShortcode::removeAutop($content) : JSNPagebuilderHelpersBuilder::generateShortCode($content, false, 'frontend', true);
		$items         = explode( '<!--seperate-->', $sub_shortcode );
		// remove empty element
		$items         = array_filter( $items );
		$initial_open  = ( ! isset( $initial_open ) || $initial_open > count( $items ) ) ? 1 : $initial_open;
		foreach ( $items as $idx => $item ) {
			$open        = ( $idx + 1 == $initial_open ) ? 'in' : '';
			$items[$idx] = $item;
		}
		$sub_htmls = implode( '', $items );
		
		if ( $arr_params['progress_bar_show_icon'] == 'no' ) {
			$pattern   = '\\[(\\[?)(icon)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
		} else {
			$sub_htmls = str_replace( '[icon]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/icon]', '', $sub_htmls );
		}
		if ( $arr_params['progress_bar_show_title'] == 'no' ) {
			$pattern   = '\\[(\\[?)(text)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
		} else {
			$sub_htmls = str_replace( '[text]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/text]', '', $sub_htmls );
		}
		if ( $arr_params['progress_bar_show_percent'] == 'no' ) {
			$pattern   = '\\[(\\[?)(percentage)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
		} else {
			$sub_htmls = str_replace( '[percentage]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/percentage]', '', $sub_htmls );
		}
		if ( $arr_params['progress_bar_show_percent'] == 'no' AND $arr_params['progress_bar_show_title'] == 'no' AND $arr_params['progress_bar_show_icon'] == 'no' ) {
			$pattern   = '\\[(\\[?)(sub_content)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
		}
		if ( $arr_params['progress_bar_style'] == 'stacked' ) {
			$sub_htmls   = str_replace( '{active}', '', $sub_htmls );
			$active      = ( $arr_params['progress_bar_stack_active'] == 'yes' ) ? ' progress-striped active' : '';
			$stacked 	 = ' pb_stacked';
			$html_titles = '';
			$pattern     = '\\[(\\[?)(sub_content)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			preg_match_all( "/$pattern/s", $sub_htmls, $matches );
			$sub_htmls   = preg_replace( "/$pattern/s", '', $sub_htmls );
			foreach ( $matches as $i => $items ) {
				if ( is_array( $items ) ) {
					foreach ( $items as $j => $item ) {
						if ( $item != '' AND strpos( $item, '[sub_content]' ) !== false ) {
							$item        = str_replace( '[sub_content]', '', $item );
							$item        = str_replace( '[/sub_content]', '', $item );
							$html_titles .= $item;
						}
					}
				}
			}
			$html_element = $html_titles;
			$html_element .= "<div class='progress{$active}{$stacked}'>";
			$html_element .= $sub_htmls;
			$html_element .= '</div>';
            $html_element .= "<div style='clear: both'></div>";
		} else {
			$sub_htmls = str_replace( '[sub_content]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/sub_content]', '', $sub_htmls );
			if ( $arr_params['progress_bar_stack_active'] == 'yes' ) {
				$sub_htmls = str_replace( '{active}', ' active', $sub_htmls );
			} else {
				$sub_htmls = str_replace( '{active}', '', $sub_htmls );
			}
			$html_element = $sub_htmls;
		}

		return $this->element_wrapper( $html_element, $arr_params );
	}

}

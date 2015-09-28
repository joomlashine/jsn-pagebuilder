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
 * Accordion shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeAccordion extends IG_Pb_Element {

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
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/font-awesome/css/font-awesome.min.css', 'css');
	}
	
	/**
	 * DEFINE configuration information of shortcode
	 * 
	 * @return type
	 */
	public function element_config() {
		$this->config['shortcode'] = 'pb_accordion';
		$this->config['name'] = JText::_('JSN_PAGEBUILDER_ELEMENT_ACCORDION');
		$this->config['cat'] = JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_TYPOGRAPHY');
		$this->config['icon'] = "icon-accordion";
		$this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_ACCORDION_DESCRIPTION");

		$this->config['has_subshortcode'] = __CLASS__ . 'Item';
		$this->config['shortcode_structure']	= '';
	}

	/**
	 * DEFINE setting options of shortcode
	 * 
	 * @return type
	 */
	public function frontend_element_items() {
		$this->items = array(
			"action" => array(
				array(
					"id" => "btn_convert",
					"type" => "button_group",
					"bound" => 0,
					"actions" => array(
						array(
							"std" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ACTION_TAB"),
							"action_type" => "convert",
							"action" => "accordion_to_tab",
						),
						array(
							"std" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ACTION_CAROUSEL"),
							"action_type" => "convert",
							"action" => "accordion_to_carousel",
						),
						array(
							"std" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ACTION_LIST"),
							"action_type" => "convert",
							"action" => "accordion_to_list",
						),
					)
				)
			),
			"content" => array(
				array(
					"name" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE"),
					"id" => "el_title",
					"type" => "text_field",
					"class" => "jsn-input-xxlarge-fluid",
					"std" => JText::_( "JSN_PAGEBUILDER_ELEMENT_ACCORDION_ELEMENT_TITLE_STD" ),
					"role" => "title",
					"tooltip" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES")
				),
				array(
					"id" => "accordion_items",
					"name" => JText::_("JSN_PAGEBUILDER_ELEMENT_ACCORDION_ITEMS"),
					"type" => "group",
					"shortcode" => 'pb_accordion',
					"sub_item_type" => $this->config['has_subshortcode'],
					"sub_items" => array(
						array('std' => ''),
						array('std' => ''),
					),
					'label_item'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_ACCORDION_ITEM_LABEL' ),
				)
			),
			"styling" => array(
				array(
					"type" => "preview"
				),
				array(
					"name" => JText::_("JSN_PAGEBUILDER_ELEMENT_ACCORDION_SET_ACTIVE_ACCORDION"),
					"id" => "initial_open",
					"type" => "text_number",
					"std" => "1",
					"class" => "input-mini",
					"validate" => "number"
				),
				array(
					"name" => JText::_("JSN_PAGEBUILDER_ELEMENT_ACCORDION_ALLOW_MULTIPLE_OPENING"),
					"id" => "multi_open",
					"type" => "radio",
					"std" => "no",
					"options" => array("yes" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES"), "no" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO")),
				),
				array(
					"name" => JText::_("JSN_PAGEBUILDER_ELEMENT_ACCORDION_ENABLE_FILTER"),
					"id" => "filter",
					"type" => "radio",
					"std" => "no",
					"options" => array("yes" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES"), "no" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO")),
				),
			)
		);
	}

    /**
     * DEFINE setting options of shortcode
     *
     * @return type
     */
    public function backend_element_items() {
        $this->frontend_element_items();
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
		$document = JFactory::getDocument();
		$document->addStyleSheet(JSNPB_ELEMENT_URL . '/accordion/assets/css/accordion.css', 'text/css');
		
		$arr_params = (JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts));
		$initial_open = intval($arr_params['initial_open']);
		$multi_open = ($arr_params['multi_open']);
		$filter = ($arr_params['filter']);
		$random_id = JSNPagebuilderHelpersShortcode::generateRandomString();
		$script = "";
		$scriptFilter ="";
		if ($multi_open == "yes") {
			$script .= "<script type='text/javascript'>( function ($) {
					$( document ).ready( function ()
					{
						$( '#accordion_$random_id .panel-title a' ).click( function(e ){
							var collapse_item = $( '#accordion_$random_id '+this.hash )
							collapse_item.collapse( 'toggle' )
						});
					});
				} )( JoomlaShine.jQuery );</script>";
		} else {
			// some case the collapse doesn't work, need this code
			$script .= "<script type='text/javascript'>( function ($) {
					$( document ).ready( function ()
					{
						$( '#accordion_$random_id .panel-collapse' ).click( function(e ){
							var collapse_item = $( '#accordion_$random_id '+this.hash )
							$( '#accordion_$random_id .panel-body' ).each(function(){
								$( this ).addClass( 'panel-collapse' );
							});
							collapse_item.removeClass( 'panel-collapse' );
							collapse_item.attr( 'style', '' );
						});
					});
				} )( JoomlaShine.jQuery );</script>";
		}

		$sub_shortcode = empty($content) ? JSNPagebuilderHelpersShortcode::removeAutop($content) : JSNPagebuilderHelpersBuilder::generateShortCode($content, false, 'frontend', true);
		$items = explode("<!--seperate-->", $sub_shortcode);
		// remove empty element
		$items = array_filter($items);
		$initial_open = ($initial_open > count($items)) ? 1 : $initial_open;
		foreach ($items as $idx => $item) {
			$open = ($idx + 1 == $initial_open) ? "in" : "";
			$item = str_replace("{index}", $random_id . $idx, $item);
			$item = str_replace("{show_hide}", $open, $item);
			$items[$idx] = $item;
		}
		$sub_shortcode = implode("", $items);

		$filter_html = "";
		if ($filter == "yes") {
			$sub_sc_data = JSNPagebuilderHelpersShortcode::extractSubShortcode($content);
			$sub_sc_data = $sub_sc_data[$this->config['has_subshortcode']];
			
			$tags = array();
			$tags[] = 'all';
			foreach ($sub_sc_data as $shortcode) {
				$extract_params = JSNPagebuilderHelpersShortcode::shortcodeParseAtts($shortcode);
				$tags[] = isset( $extract_params["tag"] ) ? $extract_params["tag"] : '';
			}
			$tags = array_filter($tags);
			if (count($tags) > 1) {
				$tags = implode(",", $tags);
				$tags = explode(",", $tags);
				$tags = array_unique($tags);
				$filter_html = "<ul id='filter_$random_id' class='nav nav-pills elementFilter' style='margin-bottom:2px;'>";
				foreach ($tags as $idx => $value) {
	                $active = ($idx == 0) ? "active" : "";
	                $filter_html .= "<li class='$active'><a href='#' class='" . str_replace(" ", "_", $value) . "'>" . ucfirst($value) . "</a></li>";
                }
                $filter_html .= "</ul>";
				// remove "All" tag
				array_shift($tags);
				$inner_tags = implode( ',', $tags );
				$scriptFilter .= "<script type='text/javascript'>( function ($) {
				$( document ).ready( function ()
					{
						window.parent.jQuery.noConflict()( '#jsn_view_modal').contents().find( 'data-tag' ).text( '{$inner_tags}')
						var parent_criteria = '#filter_$random_id'
						var clientsClone = $( '#accordion_$random_id' );
						var tag_to_filter = 'div';
						var class_to_filter = '.panel-default';
	
						$( parent_criteria + ' a' ).click( function(e ) {
							// stop running filter
							$( class_to_filter ).each(function(){
								$( this ).stop( true )
							})
							e.preventDefault();
	
							//active clicked criteria
							$( parent_criteria + ' li' ).removeClass( 'active' );
							$( this ).parent().addClass( 'active' );
	
							var filterData = $( this ).attr( 'class' );
							var filters;
							if( filterData == 'all' ){
								filters = clientsClone.find( tag_to_filter );
							} else {
								filters = clientsClone.find( tag_to_filter + '[data-tag~='+ filterData +']' );
							}
							clientsClone.find( class_to_filter ).each(function(){
								$( this ).fadeOut();
							});
							filters.each(function(){
								$( this ).fadeIn();
							});
						});
					});
				} )( jQuery )</script>";
				}
		}

		$html = '<div class="panel-group" id="accordion_{ID}">' . $sub_shortcode . '</div>';
		$html = str_replace("{ID}", "$random_id", $html);
		$html .= $script . $scriptFilter;
		return $this->element_wrapper( $filter_html . $html, $arr_params );
	}

}

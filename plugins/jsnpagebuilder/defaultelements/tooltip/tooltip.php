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
 * Tooltip shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeTooltip extends IG_Pb_Element {

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
	}
	
	/**
	 * DEFINE configuration information of shortcode
	 * 
	 * @return type
	 */
	public function element_config() {
		$this->config['shortcode'] = 'pb_tooltip';
		$this->config['name']      = JText::_( 'JSN_PAGEBUILDER_ELEMENT_TOOLTIP' );
		$this->config['cat']       = JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_TYPOGRAPHY' );
		$this->config['icon']      = 'icon-tooltip';
		$this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_TOOLTIP_DES");
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
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TOOLTIP_ELEMENT_TITLE_STD' ),
					'role'    => 'title',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_TEXT' ),
					'id'      => 'text',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TOOLTIP_TEXT_STD' ),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_TEXT_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TOOLTIP_CONTENT' ),
					'id'      => 'tooltip_content',
					'role'    => 'content',
					'type'    => 'tiny_mce',
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TOOLTIP_CONTENT_STD' ),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TOOLTIP_CONTENT_DES' ),
					'exclude_quote' => '1'
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TOOLTIP_POSITION' ),
					'id'   	  => 'position',
					'type'    => 'select',
					'std' 	  => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getFullPositions() ),
					'options' => JSNPagebuilderHelpersType::getFullPositions(),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TOOLTIP_POSITION_DES' )
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TOOLTIP_IN_BUTTON' ),
					'id'         => 'tooltips_button',
					'type'       => 'radio',
					'std'        => 'no',
					'options'    => array( 'yes' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ), 'no' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' ) ),
					'has_depend' => '1',
				),
				array(
					'name'              => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TOOLTIP_BUTTON_COLOR' ),
					'id'                => 'button_color',
					'type'              => 'select',
					'std'               => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getButtonColor() ),
					'options'           => JSNPagebuilderHelpersType::getButtonColor(),
					'container_class'   => 'color_select2',
					'dependency' => array( 'tooltips_button', '=', 'yes' ),
				),
				array(
					'name'            => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TOOLTIP_DELAY' ),
					'container_class' => 'combo-group',
					'type'            => array(
						array(
							'id'            => 'show',
							'type'          => 'text_append',
							'type_input'    => 'number',
							'class'         => 'input-mini',
							'std'           => '500',
							'append_before' => 'Show',
							'append'        => 'ms',
							'parent_class'  => 'combo-item',
							'validate'      => 'number',
						),
						array(
							'id'            => 'hide',
							'type'          => 'text_append',
							'type_input'    => 'number',
							'class'         => 'input-mini',
							'std'           => '100',
							'append_before' => 'Hide',
							'append'        => 'ms',
							'parent_class'  => 'combo-item',
							'validate'      => 'number',
						),
					),
				),
			)
		);
	}

	/**
	 * DEFINE setting options of shortcode
	 * 
	 * @return type
	 */
	public function element_shortcode( $atts = null, $content = null ) {		
		$document = JFactory::getDocument();
		$document->addStyleSheet( JURI::root(true) . "/plugins/system/jsnframework/assets/3rd-party/jquery-tipsy/tipsy.css", 'text/css' );
		$document->addScript( JURI::root(true) . "/plugins/system/jsnframework/assets/3rd-party/jquery-tipsy/jquery.tipsy.js", 'text/javascript' );
		
		$arr_params = JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts );
		extract( $arr_params );
		$random_id  = JSNPagebuilderHelpersShortcode::generateRandomString();
		$tooltip_id = "tooltip_$random_id";

		$button_color = ( ! $button_color || strtolower( $button_color ) == 'default' ) ? '' : $button_color;
		$position     = strtolower( $position );
		$delay_show   = ! empty( $show ) ? $show : 500;
		$delay_hide   = ! empty( $hide ) ? $hide : 100;
		$direction    = array( 'top' => 's', 'bottom' => 'n', 'left' => 'e', 'right' => 'w' );
		$content      = str_replace(array("\n", "\r"), '', $content);
		$script       = "( function ($) {
				$( document ).ready( function ()
				{
					$('#$tooltip_id').click(function(e){
						e.preventDefault();
					});
					$('#$tooltip_id').tipsy({
						fallback: '$content',
						html: true,
						live: true,
						delayIn: $delay_show,
						delayOut: $delay_hide,
						gravity: '{$direction[$position]}'
					});
				});
			} )( JoomlaShine.jQuery );";
		if ( $tooltips_button == 'no' ) {
			$html = "<a id='$tooltip_id' class='pb-label-des-tipsy' original-title='' href='#'>$text</a>";
		} else {
			$html = "<a id='$tooltip_id' class='pb-label-des-tipsy btn {$button_color}' original-title='' href='#'>$text</a>";
		}
		$document->addScriptDeclaration( $script, 'text/javascript' );
		//$html = $html;
		//if ( is_admin() ) {
		//	$custom_style = "style='margin-top: 50px;'";
		//	$html_element = "<center $custom_style>$html</center>";
		//} else
		//	$html_element = $html;

		return $this->element_wrapper( $html, $arr_params );
	}

}

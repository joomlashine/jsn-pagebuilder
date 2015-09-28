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

defined('_JEXEC') or die('Restricted access');

/**
 * SocialIcon shortcode element
 *
 * @package JSN_Pagebuilder
 * @since   1.0.7
 */
class JSNPBShortcodeSocialicon extends IG_Pb_Element
{
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

		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-iconsocialselector.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-general.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/3rd-party/font-awesome/css/font-awesome.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/css/jsn-fontawesome.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/js/colorpicker.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/css/colorpicker.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-colorpicker.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/socialicon/assets/js/socialicon-settings.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/socialicon/assets/css/socialicon-settings.css', 'css');
	}

	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config() {
		$this->config['shortcode'] = 'pb_socialicon';
		$this->config['name']      = JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON' );
		$this->config['cat']       = JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_TYPOGRAPHY' );
		$this->config['icon']      = 'icon-socials';
		$this->config['description'] = JText::_('JSN_PAGEBUILDER_ELEMENT_SOCIALICON_DES');
		$this->config['has_subshortcode'] = __CLASS__ . 'Item';
		$this->config['exception']        = array(
			'default_content'             => JText::_('JSN_PAGEBUILDER_ELEMENT_SOCIALICON'),
			'data-modal-title'            => JText::_('JSN_PAGEBUILDER_ELEMENT_SOCIALICON'),
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
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_ELEMENT_TITLE_STD' ),
					'role'    => 'title',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES' )
				),
				array(
					'id' => 'socialicon_items',
					'name' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_ITEMS' ),
					'type' => 'group',
					'shortcode' => $this->config['shortcode'],
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items' => array(
						array( 'std' => '[pb_socialicon_item heading="Facebook" link_url="https://www.facebook.com" icon="fa fa-facebook" ][/pb_socialicon_item]' ),
						array( 'std' => '[pb_socialicon_item heading="Twitter" link_url="https://twitter.com" icon="fa fa-twitter" ][/pb_socialicon_item]' ),
						array( 'std' => '[pb_socialicon_item heading="Google +" link_url="https://plus.google.com" icon="fa fa-google-plus" ][/pb_socialicon_item]' ),
					),

					'label_item'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_ITEMS_LABEL' )
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_SHAPE' ),
					'id'      => 'shape',
					'type'    => 'radio',
					'std'     => 'square',
					'has_depend' => '1',
					'options' => array( 'square' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_SQUARE'), 'circle' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_CIRCLE')),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_SHAPE_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_ROUNDED_CORNER_VALUE' ),
					'id'      => 'rounded_corner',
					'type'    => 'text_field',
					'std'     => '5',
					'dependency' => array('shape', '=', 'square'),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_ROUNDED_CORNER_VALUE_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SIZE' ),
					'id'      => 'icon_size',
					'type'    => 'select',
					'std'     => 'small',
					'options' => array('small' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SMALL' ), 'medium' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_MEDIUM' ), 'large' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_LARGE' )),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SIZE_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_BACKGROUND_COLOR' ),
					'id'      => 'icon_bg_color',
					'type'    => 'radio',
					'std'     => 'brand',
					'options' => array( 'brand' => JText::_('JSN_PAGEBUILDER_ELEMENT_SOCIALICON_BRAND_COLOR'), 'custom' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_CUSTOM')),
					'has_depend' => '1',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_BACKGROUND_COLOR_DES' )
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_CHOOSE_COLOR' ),
					'id'      => 'custom_bg_color',
					'type'    => 'color_picker',
					'std'     => '#333333',
					'dependency' => array('icon_bg_color', '=', 'custom'),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_CHOOSE_COLOR_DES' )
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
		$document->addScript( JSNPB_ELEMENT_URL.'/socialicon/assets/js/socialicon.js', 'text/javascript' );
		$document->addStyleSheet(JSNPB_ADMIN_URL . '/assets/3rd-party/font-awesome/css/font-awesome.css', 'text/css');
		$document->addStyleSheet(JSNPB_ELEMENT_URL . '/socialicon/assets/css/socialicon.css', 'text/css');

		$arr_params    = JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts );
		extract( $arr_params );
		$style = '';
		if ($icon_bg_color == 'custom'){
			$style .= 'background:'.$custom_bg_color .';';
		}
		if ($shape == 'square')
		{
			if (!empty($rounded_corner))
			{
				$style .= 'border-radius:'. $rounded_corner . 'px;';
			}
		}
		elseif ($shape == 'circle')
		{
			$style .= 'border-radius:50px;';
		}

		$html_elements  = '';
		$sub_shortcode = empty($content) ? JSNPagebuilderHelpersShortcode::removeAutop($content) : JSNPagebuilderHelpersBuilder::generateShortCode($content, false, 'frontend', true);
		
		if ( ! empty( $sub_shortcode ) ) {

			$html_elements = "<ul class='pb-social-icons {$shape} {$icon_bg_color}'>";
			$sub_htmls     = $sub_shortcode;
			$sub_htmls      = str_replace('{icon_size}', $icon_size, $sub_htmls);
			$sub_htmls      = str_replace('STYLE', "style='{$style}'", $sub_htmls);

			$html_elements .= $sub_htmls;
			$html_elements .= '</ul>';
			$html_elements .= '<div style="clear: both"></div>';
		}
		return $this->element_wrapper( $html_elements, $arr_params );
	}
}
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
 * Google Map Item shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeGooglemapItem extends IG_Pb_Child
{

	/**
	 * Constructor
	 *
	 * @return type
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Include admin scripts
	 *
	 * @return type
	 */
	public function backend_element_assets()
	{
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/googlemap/assets/js/googlemap-settings.js', 'js');
	}

	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config()
	{
		$this->config['shortcode'] = 'pb_googlemap_item';
		$this->config['exception'] = array(
			'item-text'        => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_MARKER'),
			'data-modal-title' => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_ITEM')
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
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_TITLE'),
					'id'      => 'gmi_title',
					'type'    => 'text_field',
					'role'    => 'title',
					'std'     => JText::_(JSNPagebuilderHelpersPlaceholders::add_palceholder('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_TITLE_STD', 'index')),
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_TITLE_DES'),
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_DESCRIPTION'),
					'id'              => 'gmi_desc_content',
					'role'            => 'content',
					'type'            => 'tiny_mce',
					'std'             => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_DESCRIPTION_STD'),
					'tooltip'         => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_DESCRIPTION_DES'),
					'container_class' => 'pb_tinymce_replace',
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_LINK_URL'),
					'id'      => 'gmi_url',
					'type'    => 'text_field',
					'std'     => 'http://',
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_LINK_URL_DES'),
				),
				array(
					'name'        => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_IMAGE'),
					'id'          => 'gmi_image',
					'type'        => 'select_media',
					'std'         => '',
					'class'       => 'jsn-input-large-fluid',
					'tooltip'     => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_IMAGE_DES'),
					'filter_type' => 'image',
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_ITEM_LOCATION'),
					'id'              => 'gmi_location',
					'type'            => array(
						array(
							'id'            => 'gmi_lat',
							'type'          => 'text_append',
							'input_type'    => 'number',
							'class'         => 'jsn-input-number input-small input-sm',
							'std'           => rand(0, 10),
							'parent_class'  => 'input-group-inline',
							'append_before' => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_ITEM_LOCATION_LATITUDE')
						),
						array(
							'id'            => 'gmi_long',
							'type'          => 'text_append',
							'input_type'    => 'number',
							'class'         => 'jsn-input-number input-small input-sm',
							'std'           => rand(0, 10),
							'parent_class'  => 'input-group-inline',
							'append_before' => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_ITEM_LOCATION_LONGTITUDE'),
						),
					),
					'tooltip'         => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_ITEM_LOCATION_DES'),
					'container_class' => 'combo-group',
				),
			),
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
	public function element_shortcode($atts = null, $content = null)
	{
		$params = (JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts));
		extract($params);
		// reassign value for description from content of shortcode
		$content = JSNPagebuilderHelpersFunctions::add_absolute_path_to_image_url($content);
		$params['gmi_desc_content'] = $content;
		$html_element               = "<input type='hidden' value='" . json_encode($params) . "' class='pb-gmi-lat-long' />";
		$html_element .= '<!--seperate-->';

		return $html_element;
	}

}

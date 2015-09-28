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
 * Testimonial Item shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeTestimonialItem extends IG_Pb_Child
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
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-fonticomoon.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/js/colorpicker.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/css/colorpicker.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-colorpicker.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/testimonial/assets/js/testimonial-settings.js', 'js');
	}

	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config()
	{
		$this->config['shortcode'] = 'pb_testimonial_item';
		$this->config['exception'] = array(
			'item-text'        => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_ITEM'),
			'data-modal-title' => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_ITEM')
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
					'name' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_TITLE'),
					'id'   => 'elm_title',
					'type' => 'text_field',
					'role' => 'title',
					'std'  => JText::_(JSNPagebuilderHelpersPlaceholders::add_palceholder('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_ITEM_TITLE_STD', 'index')),
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_CLIENTS_NAME'),
					'type'            => array(
						array(
							'id'           => 'name',
							'type'         => 'text_field',
							'std'          => '',
							'placeholder'  => 'John Doe',
							'parent_class' => 'combo-item input-append-inline',
						),
						array(
							'id'           => 'name_height',
							'type'         => 'text_append',
							'type_input'   => 'number',
							'class'        => 'input-mini',
							'std'          => '12',
							'append'       => 'px',
							'validate'     => 'number',
							'parent_class' => 'combo-item input-append-inline',
						),
						array(
							'id'           => 'name_color',
							'type'         => 'color_picker',
							'std'          => '#000000',
							'parent_class' => 'combo-item',
						),
					),
					'tooltip'         => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_CLIENTS_NAME_DES'),
					'container_class' => 'combo-group',
				),
				array(
					'name'        => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_CLIENTS_POSITION'),
					'id'          => 'job_title',
					'type'        => 'text_field',
					'std'         => '',
					'tooltip'     => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_CLIENTS_POSITION_DES'),
					'placeholder' => 'CEO',
				),
				array(
					'name' => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_FEEDBACK_CONTENT'),
					'id'   => 'body',
					'role' => 'content',
					'type' => 'tiny_mce',
					'std'  => JSNPagebuilderHelpersType::loremText(),
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_COUNTRY'),
					'id'      => 'country',
					'type'    => 'text_field',
					'std'     => '',
					'tooltip' => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_COUNTRY_DES'),
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_COMPANY'),
					'id'      => 'company',
					'type'    => 'text_field',
					'std'     => '',
					'tooltip' => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_COMPANY_DES'),
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_ITEM_WEBSITE_URL'),
					'id'      => 'web_url',
					'type'    => 'text_field',
					'std'     => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_ITEM_WEBSITE_URL_STD'),
					'tooltip' => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_ITEM_WEBSITE_URL_DES'),
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_AVATAR'),
					'id'      => 'image_file',
					'type'    => 'select_media',
					'std'     => '',
					'class'   => 'jsn-input-large-fluid',
					'tooltip' => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_AVATAR_DES'),
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
		$content = JSNPagebuilderHelpersFunctions::add_absolute_path_to_image_url($content);
		$atts['testimonial_content'] = $content;
		return serialize($atts) . '<!--seperate-->';

		extract(JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts));

		$img = !empty($image_file) ? "<img class='pb-testimonial-image {style}' src='{$image_file}' />" : '';


		return "";

	}

}

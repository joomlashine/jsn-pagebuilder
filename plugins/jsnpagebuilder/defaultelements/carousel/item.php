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
 * Carousel Item shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeCarouselItem extends IG_Pb_Child
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
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-iconselector.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/carousel/assets/js/carousel-setting.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-general.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-fonticomoon.css', 'css');
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/font-awesome/css/font-awesome.min.css', 'css');
	}

	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config()
	{
		$this->config['shortcode'] = 'pb_carousel_item';
		$this->config['exception'] = array(
			'data-modal-title' => JText::_('JSN_PAGEBUILDER_ELEMENT_CAROUSEL_ITEM')
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
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_IMAGE_FILE'),
					'id'      => 'image_file',
					'type'    => 'select_media',
					'std'     => '',
					'class'   => 'jsn-input-large-fluid',
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_IMAGE_FILE_DES')
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_HEADING'),
					'id'      => 'heading',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'role'    => 'title',
					'std'     => JText::_('JSN_PAGEBUILDER_ELEMENT_CAROUSEL_ITEM_TITLE_STD'),
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_HEADING_DES'),
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_BODY'),
					'id'      => 'body',
					'role'    => 'content',
					'type'    => 'tiny_mce',
					'std'     => JSNPagebuilderHelpersType::loremText(),
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_BODY_DES'),
				),
				array(
					'name'      => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ICON'),
					'id'        => 'icon',
					'type'      => 'icons',
					'std'       => '',
					'role'      => 'title_prepend',
					'role_type' => 'icon',
					'tooltip'   => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ICON_DES'),
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
	public function element_shortcode($atts = null, $content = null)
	{
		$pathRoot 	= JURI::root();
		extract(JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts));
		$content_class = !empty($image_file) ? 'carousel-caption' : 'carousel-content';
		$pathRoot 	= JURI::root();
		$url_pattern = '/^(http|https)/';
		preg_match($url_pattern, $image_file, $m);
		if(count($m)){
			$pathRoot = '';
		}
		$hidden        = (empty($heading) && empty($content)) ? 'style="display:none"' : '';
		$img           = !empty($image_file) ? "<img  src='{$pathRoot}{$image_file}'>" : '';
		$icon          = !empty($icon) ? "<i class='$icon'></i>" : '';
		$inner_content = JSNPagebuilderHelpersShortcode::removeAutop($content);
		$inner_content = JSNPagebuilderHelpersFunctions::add_absolute_path_to_image_url($inner_content);
		if (empty($heading) && empty($inner_content))
		{
			$html_content = "";
		}
		else
		{
			$html_content = "<div class='$content_class' $hidden>";
			$html_content .= "<h4>{$icon}{$heading}</h4>";
			$html_content .= "<p>{$inner_content}</p></div>";
		}

		return "<div class='{active} item'>{$img}{$html_content}</div><!--seperate-->";
	}

}

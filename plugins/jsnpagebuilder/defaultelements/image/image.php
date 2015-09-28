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

include_once 'helpers/helper.php';

/**
 * Image shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeImage extends IG_Pb_Element
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
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-select2/select2.min.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-select2/select2.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-linktype.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/image/assets/js/image-setting.js', 'js');
	}

	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config()
	{
		$this->config['shortcode']   = 'pb_image';
		$this->config['name']        = JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGE');
		$this->config['cat']         = JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_MEDIA');
		$this->config['icon']        = 'icon-image';
		$this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_IMAGE_DES");
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
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE'),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGE_ELEMENT_TITLE_STD'),
					'role'    => 'title',
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES')
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_IMAGE_FILE'),
					'id'      => 'image_file',
					'type'    => 'select_media',
					'std'     => '',
					'class'   => 'jsn-input-large-fluid',
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_IMAGE_FILE_DES')
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGE_ALT_TEXT'),
					'id'      => 'image_alt',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => '',
					'tooltip' => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGE_ALT_TEXT_DES')
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGE_IMAGE_SIZE'),
					'id'      => 'image_size',
					'type'    => 'select',
					'std'     => 'fullsize',
					'options' => JSNPagebuilderHelpersType::getImageSize(),
					'tooltip' => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGE_IMAGE_SIZE_DES')
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGE_CLICK_ACTION'),
					'id'         => 'link_type',
					'type'       => 'select',
					'std'        => JSNPagebuilderHelpersType::getFirstOption(JSNPbImageHelper::getClickActionType()),
					'options'    => JSNPbImageHelper::getClickActionType(),
					'tooltip'    => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGE_CLICK_ACTION_DES'),
					'has_depend' => '1',
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_URL'),
					'id'         => 'image_type_url',
					'type'       => 'text_field',
					'class'      => 'jsn-input-xxlarge-fluid',
					'std'        => 'http://',
					'tooltip'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_LINK_URL_DES'),
					'dependency' => array('link_type', '=', 'url')
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_OPEN_IN'),
					'id'         => 'open_in',
					'type'       => 'select',
					'std'        => JSNPagebuilderHelpersType::getFirstOption(JSNPbImageHelper::getOpenInOptions()),
					'options'    => JSNPbImageHelper::getOpenInOptions(),
					'tooltip'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_OPEN_IN_DES'),
					'dependency' => array('link_type', '=', 'url')
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_CONTAINER_STYLE'),
					'id'      => 'image_container_style',
					'type'    => 'select',
					'std'     => JSNPagebuilderHelpersType::getFirstOption(JSNPagebuilderHelpersType::getContainerStyle()),
					'options' => JSNPagebuilderHelpersType::getContainerStyle(),
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_CONTAINER_STYLE_DES')
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT'),
					'id'      => 'image_alignment',
					'type'    => 'select',
					'std'     => JSNPagebuilderHelpersType::getFirstOption(JSNPagebuilderHelpersType::getTextAlign()),
					'options' => JSNPagebuilderHelpersType::getTextAlign(),
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_DES')
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_MARGIN'),
					'container_class' => 'combo-group',
					'id'              => 'image_margin',
					'type'            => 'margin',
					'extended_ids'    => array('image_margin_top', 'image_margin_right', 'image_margin_bottom', 'image_margin_left'),
					'tooltip'         => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_MARGIN_DES')
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
		// Load js and style sheet for frontend
		JSNPagebuilderHelpersFunctions::loadFancyboxJS();
		$document = JFactory::getDocument();
		$document->addScript(JSNPB_ELEMENT_URL . '/image/assets/jquery-lazyload/jquery.lazyload.js');
		$document->addScript(JSNPB_ELEMENT_URL . '/image/assets/js/image.js');

		$arr_params = JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts);
		extract($arr_params);
		$html_elemments = '';
		$alt_text       = ($image_alt) ? " alt='{$image_alt}'" : '';
		$image_styles   = array();
		if ($image_margin_top)
			$image_styles[] = "margin-top:{$image_margin_top}px";
		if ($image_margin_bottom)
			$image_styles[] = "margin-bottom:{$image_margin_bottom}px";
		if ($image_margin_right)
			$image_styles[] = "margin-right:{$image_margin_right}px";
		if ($image_margin_left)
			$image_styles[] = "margin-left:{$image_margin_left}px";
		$styles    = (count($image_styles)) ? ' style="' . implode(';', $image_styles) . '"' : '';
		$class_img = ($image_container_style != 'no-styling') ? $image_container_style : '';
		$class_img = (!empty($class_img)) ? ' class="' . $class_img . '"' : '';

		if (strtolower($image_size) != 'fullsize')
		{
			if (strtolower($image_size) == 'thumbnail')
				$img_width = 'width="150"';
			if (strtolower($image_size) == 'medium')
				$img_width = 'width="300"';
			if (strtolower($image_size) == 'large')
				$img_width = 'width="450"';
		}
		else
		{
			$img_width = '';
		}
		if ($image_file)
		{
			$pathRoot 	= JURI::root();
			$url_pattern = '/^(http|https)/';
			$image_file = $image_file;
			preg_match($url_pattern, $image_file, $m);
			if(count($m)){
				$pathRoot = '';
			}
			$html_elemments .= "<img src='{$pathRoot}{$image_file}'{$alt_text}{$styles}{$class_img}{$img_width}/>";
			$target = '';

			if ($open_in)
			{
				switch ($open_in)
				{
					case 'current_browser':
						$target = '';
						break;
					case 'new_browser':
					case 'lightbox':
						$target = ' target="_blank"';
						break;
				}
			}

			if ($link_type == 'url') {
				$html_elemments = "<a href='{$image_type_url}'{$target}>" . $html_elemments . '</a>';
			} else if ($link_type == 'image') {
				$html_elemments = "<a href='{$pathRoot}{$image_file}'{$target} class='pb-image-fancy'>" . $html_elemments . "</a>";
			}

			if (strtolower($image_alignment) != 'inherit')
			{
				if (strtolower($image_alignment) == 'left')
					$cls_alignment = 'pull-left';
				if (strtolower($image_alignment) == 'right')
					$cls_alignment = 'pull-right';
				if (strtolower($image_alignment) == 'center')
					$cls_alignment = 'text-center';
				$html_elemments = "<div class='{$cls_alignment}'>" . $html_elemments . '</div><div style="clear: both"></div>';

			}
		}

		return $this->element_wrapper($html_elemments, $arr_params);
	}

}

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
 * Progress Circle shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeProgresscircle extends IG_Pb_Element
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
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/font-awesome/css/font-awesome.min.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-iconselector.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/js/colorpicker.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/css/colorpicker.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-colorpicker.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/progresscircle/assets/js/progresscircle-settings.js', 'js');
	}

	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config()
	{
		$this->config['shortcode']        = 'pb_progresscircle';
		$this->config['name']             = JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE');
		$this->config['cat']              = JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_TYPOGRAPHY');
		$this->config['icon']             = 'icon-progress-circle';
		$this->config['description']      = JText::_("JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE_DES");
		$this->config['has_subshortcode'] = __CLASS__ . 'Item';
		$this->config['exception']        = array(
			'default_content'  => JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE'),
			'data-modal-title' => JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE')
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
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE'),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE_ELEMENT_TITLE_STD'),
					'role'    => 'title',
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES'),
				),
				array(
					'name' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_TEXT'),
					'id'   => 'text',
					'type' => 'text_field',
					'role' => 'content',
					'std'  => JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE_TEXT_STD'),
				),
				array(
					'name' => JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE_ITEM_DES'),
					'id'   => 'description',
					'type' => 'text_field',
					'std'  => JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE_ITEM_DES_STD'),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_PERCENTAGE'),
					'id'         => 'percent',
					'type'       => 'text_append',
					'type_input' => 'number',
					'class'      => 'input-mini',
					'std'        => '15',
					'append'     => '%',
					'validate'   => 'number',
				),
				array(
					'name' => JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE_FOREGROUND_COLOR'),
					'id'   => 'fg_color',
					'type' => 'color_picker',
					'std'  => '#556b2f',
				),
				array(
					'name' => JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE_BACKGROUND_COLOR'),
					'id'   => 'bg_color',
					'type' => 'color_picker',
					'std'  => '#eeeeee',
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE_USE_FILL_COLOR'),
					'id'         => 'use_fill',
					'type'       => 'radio',
					'std'        => 'no',
					'has_depend' => '1',
					'options'    => array('yes' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES'), 'no' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO')),
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE_FILL_COLOR'),
					'id'         => 'fill_color',
					'type'       => 'color_picker',
					'std'        => '#ffffff',
					'dependency' => array('use_fill', '=', 'yes'),
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE_CIRCLE_THICKNESS'),
					'id'         => 'width',
					'type'       => 'text_append',
					'type_input' => 'number',
					'class'      => 'input-mini',
					'std'        => '15',
					'append'     => 'px',
					'validate'   => 'number',
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_DIMENSION'),
					'id'         => 'dimension',
					'type'       => 'text_append',
					'type_input' => 'number',
					'class'      => 'input-mini',
					'std'        => '200',
					'append'     => 'px',
					'validate'   => 'number',
					'tooltip'    => JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE_DIMENSION_DES'),
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_SIZE'),
					'id'         => 'font_size',
					'type'       => 'text_append',
					'type_input' => 'number',
					'class'      => 'input-mini',
					'std'        => '15',
					'append'     => 'px',
					'validate'   => 'number',
				),
				array(
					'name'               => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ICON'),
					'id'                 => 'icon',
					'type'               => 'icons',
					'std'                => '',
					'title_prepend_type' => 'icon',
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_PROGRESSCIRCLE_SHOW_HALF'),
					'id'      => 'is_half',
					'type'    => 'radio',
					'std'     => 'no',
					'options' => array('yes' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES'), 'no' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO')),
				),
				array(
					'name'                 => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_MARGIN'),
					'id'                   => 'circle_margin',
					'container_class'      => 'combo-group',
					'type'                 => 'margin',
					'extended_ids'         => array('circle_margin_top', 'circle_margin_bottom', 'circle_margin_left', 'circle_margin_right'),
					'circle_margin_top'    => array('std' => '10'),
					'circle_margin_bottom' => array('std' => '10'),
					'tooltip'              => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_MARGIN_DES'),
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
		$document = JFactory::getDocument();
		$document->addStyleSheet(JSNPB_ELEMENT_URL . '/progresscircle/assets/3rd-party/jquery-circliful/css/jquery.circliful.css', 'text/css');
		$document->addStyleSheet(JSNPB_ELEMENT_URL . '/progresscircle/assets/css/progresscircle.css', 'text/css');
		$document->addScript(JSNPB_ELEMENT_URL . '/progresscircle/assets/js/progresscircle.js', 'text/javascript');
		$document->addScript(JSNPB_ELEMENT_URL . '/progresscircle/assets/3rd-party/jquery-circliful/js/jquery.circliful.min.js', 'text/javascript');
		$arr_params = JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts);
		extract($arr_params);
		if (isset($circle_margin_left))
		{
			$circle_styles[] = "margin-left:{$circle_margin_left}px";
		}
		if (isset($circle_margin_right))
		{
			$circle_styles[] = "margin-right:{$circle_margin_right}px";
		}
		if (isset($circle_margin_top))
		{
			$circle_styles[] = "margin-top:{$circle_margin_top}px";
		}
		if (isset($circle_margin_bottom))
		{
			$circle_styles[] = "margin-bottom:{$circle_margin_bottom}px";
		}
		$styles = (count($circle_styles)) ? ' style="' . implode(';', $circle_styles) . '"' : '';
		$html = '<div class="pb-progress-circle" ';
		$html .= (!empty($content)) ? 'data-text="' . $content . '"' : '';
		$html .= (!empty($description)) ? 'data-info="' . $description . '"' : '';
		$html .= (!empty($dimension)) ? 'data-dimension="' . $dimension . '"' : '';
		$html .= (!empty($width)) ? 'data-width="' . $width . '"' : '';
		$html .= (!empty($font_size)) ? 'data-fontsize="' . $font_size . '"' : '';
		$html .= (!empty($percent)) ? 'data-percent="' . $percent . '"' : '';
		$html .= (!empty($fg_color)) ? 'data-fgcolor="' . $fg_color . '"' : '';
		$html .= (!empty($bg_color)) ? 'data-bgcolor="' . $bg_color . '"' : '';

		if ($use_fill == 'yes')
		{
			$html .= (!empty($fill_color)) ? 'data-fill="' . $fill_color . '"' : '';
		}
		if ($is_half == 'yes')
		{
			$html .= 'data-type="half"';
		}
		$html .= (!empty($icon)) ? 'data-icon="' . $icon . '"' : '';
		$html .= $styles . '></div>';

		return $this->element_wrapper($html, $arr_params);
	}

}

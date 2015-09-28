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
 * Text shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeText extends IG_Pb_Element
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
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-fontselector.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/js/colorpicker.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/css/colorpicker.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-colorpicker.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/text/assets/js/text-setting.js', 'js');
	}

	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	function element_config()
	{
		$this->config['shortcode']   = 'pb_text';
		$this->config['name']        = JText::_("JSN_PAGEBUILDER_ELEMENT_TEXT");
		$this->config['cat']         = JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_TYPOGRAPHY');
		$this->config['icon']        = "icon-text";
		$this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_TEXT_DES");

		$this->config['exception'] = array(
			'default_content' => JText::_('JSN_PAGEBUILDER_ELEMENT_TEXT')
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
					"name"    => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE"),
					"id"      => "el_title",
					"type"    => "text_field",
					"class"   => "jsn-input-xxlarge-fluid",
					"std"     => JText::_('JSN_PAGEBUILDER_ELEMENT_TEXT_ELEMENT_TITLE_STD'),
					"role"    => "title",
					"tooltip" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES")
				),
				array(
					'name' => JText::_('JSN_PAGEBUILDER_ELEMENT_TEXT_CONTENT'),
					'desc' => JText::_('JSN_PAGEBUILDER_ELEMENT_TEXT_CONTENT_DES'),
					'id'   => 'text',
					'type' => 'tiny_mce',
					'role' => 'content',
					'std'  => JSNPagebuilderHelpersType::loremText(),
					'rows' => 15
				),
			),
			'styling' => array(
				array(
					'type' => 'preview'
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_ELEMENT_TEXT_WIDTH'),
					'type'            => array(
						array(
							'id'           => 'width_value',
							'type'         => 'text_number',
							'std'          => '',
							'class'        => 'input-mini',
							'validate'     => 'number',
							'parent_class' => 'combo-item merge-data',
						),
						array(
							'id'           => 'width_unit',
							'type'         => 'select',
							'options'      => array('%' => '%', 'px' => 'px'),
							'std'          => '%',
							'class'        => 'input-mini',
							'parent_class' => 'combo-item merge-data',
						),
					),
					'container_class' => 'combo-group',
					'tooltip'         => JText::_('JSN_PAGEBUILDER_ELEMENT_TEXT_WIDTH_DES')

				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_TEXT_ENABLE_DROPCAP'),
					'id'         => 'enable_dropcap',
					'type'       => 'radio',
					'std'        => 'no',
					'options'    => array('yes' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES'), 'no' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO')),
					'tooltip'    => JText::_('JSN_PAGEBUILDER_ELEMENT_TEXT_ENABLE_DROPCAP_DES'),
					'has_depend' => '1'
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_FACE'),
					'id'              => 'dropcap_font_family',
					'type'            => array(
						array(
							'id'           => 'dropcap_font_face_type',
							'type'         => 'jsn_select_font_type',
							'class'        => 'input-medium',
							'std'          => 'standard fonts',
							'options'      => JSNPagebuilderHelpersType::getFonts(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'dropcap_font_face_value',
							'type'         => 'jsn_select_font_value',
							'class'        => 'input-medium',
							'std'          => 'Verdana',
							'options'      => '',
							'parent_class' => 'combo-item',
						),
					),
					'dependency'      => array('enable_dropcap', '=', 'yes'),
					'tooltip'         => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_FACE_DES'),
					'container_class' => 'combo-group',
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_ATTRIBUTES'),
					'type'            => array(
						array(
							'id'           => 'dropcap_font_size',
							'type'         => 'text_append',
							'type_input'   => 'number',
							'class'        => 'input-mini',
							'std'          => '64',
							'append'       => 'px',
							'validate'     => 'number',
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'dropcap_font_style',
							'type'         => 'select',
							'class'        => 'input-medium',
							'std'          => 'bold',
							'options'      => JSNPagebuilderHelpersType::getFontStyles(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'dropcap_font_color',
							'type'         => 'color_picker',
							'std'          => '#000000',
							'parent_class' => 'combo-item',
						),
					),
					'dependency'      => array('enable_dropcap', '=', 'yes'),
					'tooltip'         => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_FONT_ATTRIBUTES_DES'),
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
	function element_shortcode($atts = null, $content = null)
	{
		$arr_params = JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts);
		if (empty($content) && isset($atts['text']))
		{
			$content = $atts['text'];
		}
        $content = preg_replace("/^<\/p>/", "", $content);
		extract($arr_params);

		$html_element = $html_style = $html_width = '';
		if (isset($enable_dropcap) && $enable_dropcap == 'yes')
		{
			if ($content)
			{
				$styles = array();
				if ($dropcap_font_face_type == 'google fonts' AND $dropcap_font_face_value != '')
				{
					$document = JFactory::getDocument();
					$document->addStyleSheet("http://fonts.googleapis.com/css?family={$dropcap_font_face_value}", 'text/css');
					$styles[] = 'font-family:' . $dropcap_font_face_value;
				}
				elseif ($dropcap_font_face_type == 'standard fonts' AND $dropcap_font_face_value)
				{
					$styles[] = 'font-family:' . $dropcap_font_face_value;
				}

				if (intval($dropcap_font_size) > 0)
				{
					$styles[] = 'font-size:' . intval($dropcap_font_size) . 'px';
					$styles[] = 'line-height:' . intval($dropcap_font_size / 2) . 'px';
				}
				switch ($dropcap_font_style)
				{
					case 'bold':
						$styles[] = 'font-weight:700';
						break;
					case 'italic':
						$styles[] = 'font-style:italic';
						break;
					case 'normal':
						$styles[] = 'font-weight:normal';
						break;
				}

				if (strpos($dropcap_font_color, '#') !== false)
				{
					$styles[] = 'color:' . $dropcap_font_color;
				}

                $random_id = JSNPagebuilderHelpersShortcode::generateRandomString(6, true);
                if (count($styles))
                {
                    $html_style .= 'div.pb-element-text div.dropcap.' . $random_id . ' :first-child::first-letter { float:left;margin-top:5px;margin-right:5px;';
                    $html_style .= implode(';', $styles);
                    $html_style .= '}';
                }

                $html_element .= "<div class='dropcap {$random_id}'>{$content}</div>";
			}
		}
		else
		{
			$html_element .= '<div>' . $content . '</div>';
		}
		if ($width_value)
		{
			$width_style = 'width:' . $width_value . $width_unit;
		}
		if (!empty($width_style))
		{
			$html_width .= 'div.pb-element-text{margin:0 auto;';
			$html_width .= $width_style;
			$html_width .= '}';
		}
		$document = JFactory::getDocument();
		if ($html_style)
		{
			$document->addStyleDeclaration($html_style, 'text/css');
		}
		if ($html_width)
		{
			$document->addStyleDeclaration($html_width, 'text/css');
		}
		$html_element = JSNPagebuilderHelpersFunctions::add_absolute_path_to_image_url($html_element);
		return $this->element_wrapper($html_element, $arr_params);
	}

}

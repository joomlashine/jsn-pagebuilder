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
defined('_JEXEC') or die('Restricted access');

/**
 * Testimonial shortcode element
 *
 * @package JSN_PageBuilder
 * @since   1.0.4
 **/
class JSNPBShortcodeTestimonial extends IG_Pb_Element
{

	/**
	 * Constructor
	 *
	 * @return  type
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
	}

	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config()
	{
		$this->config['shortcode']        = 'pb_testimonial';
		$this->config['name']             = JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL');
		$this->config['cat']              = JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_EXTRA');
		$this->config['icon']             = 'icon-testimonial';
		$this->config['description']      = JText::_("JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_DES");
		$this->config['has_subshortcode'] = __CLASS__ . 'Item';
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
					'std'     => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_ELEMENT_TITLE_STD'),
					'role'    => 'title',
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES'),
				),
				array(
					'name'          => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_ITEMS'),
					'id'            => 'testimonial_items',
					'type'          => 'group',
					'shortcode'     => $this->config['shortcode'],
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array('std' => ''),
						array('std' => ''),
						array('std' => ''),
					),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview'
				),
				array(
					'name'     => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_ITEMS_PER_SLIDE'),
					'id'       => 'items_per_slide',
					'type'     => 'text_number',
					'std'      => '2',
					'class'    => 'input-mini',
					'validate' => 'number',
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_SLIDER_ELEMENTS'),
					'id'              => 'slider_elements',
					'type'            => 'checkbox',
					'class'           => 'jsn-columns-item checkbox',
					'container_class' => 'jsn-columns-container jsn-columns-count-two',
					'std'             => 'arrows__#__indicator',
					'options'         => array(
						'arrows'    => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_SLIDER_ARROWS'),
						'indicator' => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_SLIDER_INDICATOR'),
					),
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_CONTENT_ELEMENTS'),
					'id'              => 'content_elements',
					'type'            => 'items_list',
					'std'             => 'content__#__image__#__name__#__job_title__#__country__#__company',
					'options'         => array(
						'content'   => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_FEEDBACK_CONTENT'),
						'image'     => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_AVATAR'),
						'name'      => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_CLIENTS_NAME'),
						'job_title' => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_CLIENTS_POSITION'),
						'country'   => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_COUNTRY'),
						'company'   => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_COMPANY'),
					),
					'options_type'    => 'checkbox',
					'popover_items'   => array('image', 'content'),
					'style'           => array('height' => '200px'),
					'container_class' => 'unsortable content-element',
				),
				// Popup settins for Elements = Image
				array(
					'name'              => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_CONTAINER_STYLE'),
					'id'                => 'author_image_style',
					'type'              => 'select',
					'std'               => JSNPagebuilderHelpersType::getFirstOption(JSNPagebuilderHelpersType::getContainerStyle()),
					'options'           => JSNPagebuilderHelpersType::getContainerStyle(),
					'container_class'   => 'hidden',
					'data_wrap_related' => 'image',
				),
				// Popup settins for Elements = Content
				array(
					'name'              => JText::_('JSN_PAGEBUILDER_ELEMENT_TESTIMONIAL_LENGTH_LIMITATION'),
					'id'                => 'content_length',
					'type'              => array(
						array(
							'id'           => 'content_count',
							'type'         => 'text_number',
							'std'          => '',
							'class'        => 'input-mini',
							'options'      => JSNPagebuilderHelpersType::getFonts(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'content_type',
							'type'         => 'select',
							'std'          => 'words',
							'class'        => 'input-medium',
							'options'      => array(
								'words'      => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_WORDS'),
								'characters' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_CHARACTERS'),
							),
							'parent_class' => 'combo-item',
						),
					),
					'container_class'   => 'combo-group hidden',
					'data_wrap_related' => 'content',
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
		$pathRoot 	= JURI::root();
		$document = JFactory::getDocument();
		$document->addScript(JSNPB_ELEMENT_URL . '/testimonial/assets/js/testimonial.js', 'text/javascript');
		$document->addStyleSheet(JSNPB_ELEMENT_URL . '/testimonial/assets/css/testimonial.css', 'text/css');
		$document->addStyleSheet(JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-fonticomoon.css', 'text/css');
		$document->addScriptDeclaration("if (typeof jQuery != 'undefined' && typeof MooTools != 'undefined' ) {
											    Element.implement({
											        slide: function(how, mode){
											            return this;
											        }
											    });

											}", 'text/javascript');
		$arr_params = JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts);
		extract($arr_params);
		$random_id                = JSNPagebuilderHelpersShortcode::generateRandomString();
		$testimonial_id           = "testimonial_$random_id";
		$styles                   = "style='width:100%'";
		$image_container_style    = ($author_image_style != 'no-styling') ? "{$author_image_style}" : '';
		$content_elements         = array_filter(explode('__#__', $content_elements));
		$testimonial_indicators   = array();
		$testimonial_indicators[] = '<ol class="carousel-indicators">';

		$sub_shortcode       = empty($content) ? JSNPagebuilderHelpersShortcode::removeAutop($content) : JSNPagebuilderHelpersBuilder::generateShortCode($content, false, 'frontend', true);
		$testimonial_content = array();
		$items               = explode('<!--seperate-->', $sub_shortcode);
		$items               = array_filter($items);
		$count_items         = count($items);

		foreach ($items as $idx => $item)
		{
			$pathRoot 	= JURI::root();
			$item = unserialize($item);
			$url_pattern = '/^(http|https)/';
			$image_file = @$item['image_file'];
			preg_match($url_pattern, $image_file, $m);
			if(count($m)){
				$pathRoot = '';
			}
			if ($idx % $items_per_slide == 0)
			{
				$active                   = ($idx == 0) ? 'active' : '';
				$testimonial_content[]    = "<div class='item row $active'>";
				$active_li                = ($idx == 0) ? "class='active'" : '';
				$testimonial_indicators[] = "<li $active_li></li>";
			}
			$divide    = ($count_items > $items_per_slide) ? $items_per_slide : $count_items;
			$colmd     = 'col-md-' . 12 / $divide;
			$colsm     = 'col-sm-' . 12 / $divide;
			$item_html = "<div class='pb-testimonial-item $colmd $colsm'>";

			$testimonial_info = array();
			if (in_array('content', $content_elements))
			{
				$item_content                = JSNPagebuilderHelpersShortcode::removeAutop($item['testimonial_content']);
				$item_content                = JSNPagebuilderHelpersShortcode::pbTrimContent($item_content, $content_count, $content_type);
				$testimonial_info['content'] = "<div class='pb-testimonial-box top'><div class='arrow'></div><div class='pb-testimonial-content'><p>" . $item_content . '</p></div></div>';
			}
			$img                       = !empty($item['image_file']) ? "<div class='pb-testimonial-avatar'><img class='$image_container_style' src='{$pathRoot}{$item['image_file']}' /></div>" : '';
			$testimonial_info['image'] = (in_array('image', $content_elements)) ? $img : '';

			// Process company field
			if (isset($item['company']) && $item['company'] != '')
			{
				$company_link = "<a href='{$item['web_url']}' target='_blank'>{$item['company']}</a>";
			}
			else
			{
				$company_link = "<a href='{$item['web_url']}' target='_blank'>{$item['web_url']}</a>";
			}
			if (!isset($item['web_url']) || empty($item['web_url']))
			{
				$company_link = $item['company'];
			}

			// Process testimonial metadata
			$arr_style = array();
			if (isset($item['name_height']))
			{
				$arr_style[] = 'font-size: ' . $item['name_height'] . 'px';
			}
			if (isset($item['name_color']))
			{
				$arr_style[] = 'color: ' . $item['name_color'];
			}
			$style     = ($arr_style) ? "style='" . implode(';', $arr_style) . "'" : '';
			$name      = (in_array('name', $content_elements)) ? "<strong {$style} class='pb-testimonial-name'>" . @$item['name'] . "</strong>" : '';
			$job_title = (in_array('job_title', $content_elements)) ? "<span class='pb-testimonial-jobtitle'>" . @$item['job_title']. "</span>" : '';
			$country   = (in_array('country', $content_elements)) ? "<span class='pb-testimonial-country'>" . @$item['country'] . "</span>" : '';
			if ($company_link)
			{
				$company = (in_array('company', $content_elements)) ? "<span class='pb-testimonial-company'>$company_link</span>" : '';
			}
			$html_metadata = '';
			if ($name != '' || $job_title != '' || $country != '' || $company != '')
			{
				$html_metadata .= '<div class="pb-testimonial-meta">';
				$html_metadata .= $name . $job_title . $country . $company;
				$html_metadata .= '</div>';
			}

			foreach ($content_elements as $element)
			{
				$item_html .= isset($testimonial_info[$element]) ? $testimonial_info[$element] : '';
			}
			$item_html .= $html_metadata;
			$item_html .= '</div>';
			$testimonial_content[] = $item_html;
			if (($idx + 1) % $items_per_slide == 0 || ($idx + 1) == count($items))
			{
				$testimonial_content[] = '</div>';
			}
		}
		$testimonial_content      = "<div class='carousel-inner'>" . implode('', $testimonial_content) . '</div>';
		$testimonial_indicators[] = "</ol>";
		$testimonial_indicators   = implode('', $testimonial_indicators);

		$script = "<script type='text/javascript'>
						(function($){
							$(document).ready(function(){
								if($('#$testimonial_id').length){
									$('#$testimonial_id .carousel-indicators li').each(function(i){
										$(this).on('click', function(){
											$('#$testimonial_id').carousel(i);
										});
									});
								}
							});
						})(jQuery)
				</script>";

		$slider_elements = explode('__#__', $slider_elements);
		if($count_items <= (int)$items_per_slide || !in_array('indicator', $slider_elements)){
			$testimonial_indicators = '';
		}
		$testimonial_navigator = ($count_items > $items_per_slide && in_array('arrows', $slider_elements)) ? "<a class='carousel-control left icon-arrow-left pb-arrow-left'></a><a class='carousel-control right icon-arrow-right pb-arrow-right'></a>" : '';
		$html                  = "<div class='carousel slide pb-testimonial' $styles id='$testimonial_id'> $testimonial_content $testimonial_indicators $testimonial_navigator</div>";

		return $this->element_wrapper($script . $html, $arr_params);
	}
}
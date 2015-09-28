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

require_once JSNPB_ADMIN_ROOT . '/libraries/innotheme/shortcode/layout.php';

/**
 * Row element layout
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeRow extends IG_Pb_Layout
{

	/**
	 * Default layouts for row
	 */

	static $layouts = array(
		array(6, 6),
		array(4, 4, 4),
		array(3, 3, 3, 3),
		array(4, 8),
		array(8, 4),
		array(3, 9),
		array(9, 3),
		array(3, 6, 3),
		array(3, 3, 6),
		array(6, 3, 3),
		array(2, 2, 2, 2, 2, 2),
	);

	/**
	 * Constructor of Column element layout
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Include admin scripts
	 *
	 * @return void
	 */
	public function backend_element_assets()
	{
		$document = JFactory::getDocument();

		JSNPagebuilderHelpersFunctions::print_asset_tag(JURI::root(true) . '/media/jui/js/bootstrap.min.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/classygradient/css/jquery.classygradient.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/classygradient/js/jquery.classygradient.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/js/colorpicker.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/css/colorpicker.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-colorpicker.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JUri::root(true) . '/administrator/components/com_pagebuilder/helpers/shortcode/row/assets/js/row.js', 'js');
	}

	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return void
	 */
	function element_config()
	{
		$this->config['shortcode'] = 'pb_row';
	}

    /**
     * contain setting items of this element (use for modal box)
     *
     * @return void
     */
    function element_items()
    {

    }

	/**
	 * contain setting items of this element (use for modal box)
	 *
	 * @return void
	 */
	function backend_element_items()
	{
		$this->items = array(
			"Notab" => array(
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_HELPER_ROW_BACKGROUND'),
					'id'         => 'background',
					'type'       => 'select',
					'std'        => 'none',
					'options'    => array('none' => JText::_('JSN_PAGEBUILDER_HELPER_ROW_NONE'), 'solid' => JText::_('JSN_PAGEBUILDER_HELPER_ROW_SOLID_COLOR'), 'gradient' => JText::_('JSN_PAGEBUILDER_HELPER_ROW_GRADIENT_COLOR'), 'pattern' => JText::_('JSN_PAGEBUILDER_HELPER_ROW_PATTERN'), 'image' => JText::_('JSN_PAGEBUILDER_HELPER_ROW_IMAGE'), 'video' => JText::_('JSN_PAGEBUILDER_HELPER_ROW_VIDEO')),
					"has_depend" => "1",
				),
				array(
					"name"            => JText::_("JSN_PAGEBUILDER_HELPER_ROW_SOLID_COLOR"),
					"type"            => array(
						array(
							"id"           => "solid_color_value",
							"type"         => "text_field",
							"class"        => "input-small",
							"std"          => '#FFFFFF',
							"parent_class" => "combo-item"
						),
						array(
							"id"           => "solid_color_color",
							"type"         => "color_picker",
							"std"          => "#ffffff",
							"parent_class" => "combo-item"
						)
					),
					"container_class" => "combo-group",
					"dependency"      => array('background', '=', 'solid'),
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_HELPER_ROW_GRADIENT_COLOR'),
					"id"         => "gradient_color",
					"type"       => "gradient_picker",
					"std"        => "0% #FFFFFF,100% #000000",
					"dependency" => array('background', '=', 'gradient'),
				),
				array(
					'id'              => 'gradient_color_css',
					'type'            => 'text_field',
					'std'             => '',
					'input-type'      => 'hidden',
					'container_class' => 'hidden',
					"dependency"      => array('background', '=', 'gradient'),
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_HELPER_ROW_GRADIENT_DIRECTION'),
					'id'         => 'gradient_direction',
					'type'       => 'select',
					'std'        => 'vertical',
					'options'    => array('vertical' => JText::_('JSN_PAGEBUILDER_HELPER_ROW_VERTICAL'), 'horizontal' => JText::_('JSN_PAGEBUILDER_HELPER_ROW_HORIZONTAL')),
					"dependency" => array('background', '=', 'gradient'),
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_HELPER_ROW_PATTERN'),
					'id'         => 'pattern',
					'type'       => 'select_media',
					'std'        => '',
					'class'      => 'jsn-input-large-fluid',
					"dependency" => array('background', '=', 'pattern'),
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_HELPER_ROW_REPEAT'),
					'id'         => 'repeat',
					'type'       => 'radio_button_group',
					'std'        => 'full',
					'options'    => array(
						'full'       => JText::_('JSN_PAGEBUILDER_HELPER_ROW_FULL'),
						'vertical'   => JText::_('JSN_PAGEBUILDER_HELPER_ROW_VERTICAL'),
						'horizontal' => JText::_('JSN_PAGEBUILDER_HELPER_ROW_HORIZONTAL'),
					),
					"dependency" => array('background', '=', 'pattern'),
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_HELPER_ROW_IMAGE'),
					'id'         => 'image',
					'type'       => 'select_media',
					'std'        => '',
					'class'      => 'jsn-input-large-fluid',
					"dependency" => array('background', '=', 'image'),
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_HELPER_ROW_STRETCH'),
					'id'         => 'stretch',
					'type'       => 'radio_button_group',
					'std'        => 'none',
					'options'    => array(
						'none'    => JText::_('JSN_PAGEBUILDER_HELPER_ROW_NONE'),
						'full'    => JText::_('JSN_PAGEBUILDER_HELPER_ROW_FULL'),
						'cover'   => JText::_('JSN_PAGEBUILDER_HELPER_ROW_COVER'),
						'contain' => JText::_('JSN_PAGEBUILDER_HELPER_ROW_CONTAIN'),
					),
					"dependency" => array('background', '=', 'image'),
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_HELPER_ROW_POSITION'),
					'id'         => 'position',
					'type'       => 'radio',
					'label_type' => 'image',
					'dimension'  => array(23, 23),
					'std'        => 'center center',
					'options'    => array(
						'left top'      => array('left top'),
						'center top'    => array('center top'),
						'right top'     => array('right top', 'linebreak' => true),
						'left center'   => array('left center'),
						'center center' => array('center center'),
						'right center'  => array('right center', 'linebreak' => true),
						'left bottom'   => array('left bottom'),
						'center bottom' => array('center bottom'),
						'right bottom'  => array('right bottom'),
					),
					"dependency" => array('background', '=', 'image'),
				),
				array(
					"name"       => JText::_("JSN_PAGEBUILDER_HELPER_ROW_ENABLE_PARALAX"),
					"id"         => "paralax",
					"type"       => "radio",
					"std"        => "no",
					"options"    => array("yes" => JText::_("JSN_PAGEBUILDER_HELPER_BUILDER_YES"), "no" => JText::_("JSN_PAGEBUILDER_HELPER_BUILDER_NO")),
					"dependency" => array('background', '=', 'pattern__#__image'),
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_HELPER_ROW_SCROLLING_EFFECT'),
					'id'         => 'parallax_scroll',
					'type'       => 'radio',
					'std'        => 'no',
					'options'    => array('yes' => JText::_('JSN_PAGEBUILDER_HELPER_BUILDER_YES'), 'no' => JText::_('JSN_PAGEBUILDER_HELPER_BUILDER_NO')),
					"dependency" => array('background', '=', 'pattern__#__image'),
					'tooltip'    => JText::_('JSN_PAGEBUILDER_HELPER_ROW_SCROLLING_EFFECT_DES'),
				),
				array(
					'name'        => JText::_('JSN_PAGEBUILDER_HELPER_ROW_VIDEO_URL'),
					'id'          => 'video_url',
					'type'        => 'select_media',
					'std'         => '',
					'placeholder' => 'Youtube video url',
					'dependency'  => array('background', '=', 'video'),
					'placeholder' => JText::_('JSN_PAGEBUILDER_HELPER_ROW_VIDEO_URL_PLACEHOLDER'),
				),
				array(
					"name"       => JText::_("JSN_PAGEBUILDER_HELPER_ROW_AUTOPLAY"),
					"id"         => "autoplay",
					"type"       => "radio",
					"std"        => "no",
					"options"    => array("1" => JText::_("JSN_PAGEBUILDER_HELPER_BUILDER_YES"), "0" => JText::_("JSN_PAGEBUILDER_HELPER_BUILDER_NO")),
					"dependency" => array('background', '=', 'video'),
				),
				array(
					"name"            => JText::_("JSN_PAGEBUILDER_HELPER_ROW_BORDER"),
					"type"            => array(
						array(
							"id"           => "border_width_value_",
							"type"         => "text_append",
							"type_input"   => "number",
							"class"        => "input-mini",
							"std"          => "0",
							"append"       => "px",
							"validate"     => "number",
							'parent_class' => 'combo-item'
						),
						array(
							"id"           => "border_style",
							"type"         => "select",
							"class"        => "input-medium",
							"std"          => 'solid',
							"options"      => JSNPagebuilderHelpersType::getBorderStyles(), // TODO add the options array for Row
							'parent_class' => 'combo-item'
						),
						array(
							"id"           => "border_color",
							"type"         => "color_picker",
							"std"          => "#000",
							'parent_class' => 'combo-item'
						),
					),
					"container_class" => "combo-group"
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_HELPER_ROW_WIDTH'),
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
					'tooltip'         => JText::_('JSN_PAGEBUILDER_HELPER_ROW_WIDTH_DES')
				),
				array(
					'name'               => JText::_('JSN_PAGEBUILDER_HELPER_ROW_PADDING'),
					'container_class'    => 'combo-group',
					'id'                 => 'div_padding',
					'type'               => 'margin',
					'extended_ids'       => array('div_padding_top', 'div_padding_bottom', 'div_padding_right', 'div_padding_left'),
					'div_padding_top'    => array('std' => '10'),
					'div_padding_bottom' => array('std' => '10'),
					'div_padding_right'  => array('std' => '10'),
					'div_padding_left'   => array('std' => '10'),
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_HELPER_ROW_CSS_CLASS_SUFFIX'),
					'id'      => 'css_suffix',
					'type'    => 'text_field',
					'std'     => '',
					'tooltip' => JText::_('JSN_PAGEBUILDER_HELPER_ROW_CSS_CLASS_SUFFIX_DES'),
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_HELPER_ROW_ID'),
					'id'      => 'id_wrapper',
					'type'    => 'text_field',
					'std'     => JText::_(''),
					'tooltip' => JText::_('JSN_PAGEBUILDER_HELPER_ROW_ID_DES'),
				),
			)
		);
	}

	/**
	 * html structure of element in Page Builder area
	 *
	 * @param type $content        : inner shortcode elements of this row
	 * @param type $shortcode_data : not used
	 *
	 * @return string
	 */
	public function element_in_pgbldr($content = '', $shortcode_data = '')
	{
		if (empty($content))
		{
			$column      = new JSNPBShortcodeColumn();
			$column_html = $column->element_in_pgbldr();
			$column_html = $column_html;
		}
		else
		{
			$column_html = JSNPagebuilderHelpersBuilder::generateShortCode($content);
		}

		if (empty($shortcode_data))
			$shortcode_data = $this->config['shortcode_structure'];
		// remove [/it_row][it_column...] from $shortcode_data
		$shortcode_data = explode("][", $shortcode_data);
		$shortcode_data = $shortcode_data[0] . "]";
		$row            = '<div class="jsn-row-container ui-sortable row-fluid shortcode-container" STYLE>
        				<div class="jsn-iconbar left">
        					<a href="javascript:void(0);" title="' . JText::_('JSN_PAGEBUILDER_HELPER_ROW_MOVE_UP') . '" class="jsn-move-up disabled"><i class="icon-chevron-up"></i></a>
                            <a href="javascript:void(0);" title="' . JText::_('JSN_PAGEBUILDER_HELPER_ROW_MOVE_DOWN') . '" class="jsn-move-down disabled"><i class=" icon-chevron-down"></i></a>
        				</div>
                        <textarea class="hidden" data-sc-info="shortcode_content" name="shortcode_content[]" >' . $shortcode_data . '</textarea>
                        <div class="jsn-pb-row-content">
                        ' . $column_html . '
                        </div>
                        <div class="jsn-iconbar jsn-vertical">
                            <a href="javascript:void(0);" class="add-container" title="' . JText::_('JSN_PAGEBUILDER_HELPER_ROW_ADD_COLUMN') . '"><i class="icon-plus"></i></a>
                            <a href="javascript:void(0);" title="Edit element" data-shortcode="' . $this->config['shortcode'] . '" class="element-edit row"><i class="icon-pencil"></i></a>
                            <a href="javascript:void(0);" class="item-delete row" title="' . JText::_('JSN_PAGEBUILDER_HELPER_ROW_DELETE_ROW') . '"><i class="icon-trash"></i></a>
                        </div>
                        <textarea class="hidden" name="shortcode_content[]" >[/' . $this->config['shortcode'] . ']</textarea>
                    </div>';

		return $row;
	}

	/**
	 * get params & structure of shortcode
	 *
	 * @return void
	 */
	public function shortcode_data()
	{
		$this->config['params']              = JSNPagebuilderHelpersShortcode::generateShortcodeParams($this->items, null, null, false, true);
		$this->config['shortcode_structure'] = JSNPagebuilderHelpersShortcode::generateShortcodeStructure($this->config['shortcode'], $this->config['params']);
	}

	/**
	 * define shortcode structure of element
	 *
	 * @return string
	 */
	function element_shortcode($atts = null, $content = null)
	{
		$extra_class = $style = $common_style = $data_attr = '';
		$extra_id    = !empty ($atts['id_wrapper']) ? $atts['id_wrapper'] : JSNPagebuilderHelpersShortcode::generateRandomString();
		if (isset($atts) && is_array($atts))
		{
			$arr_styles = array();

//            if(isset($atts['width'])){
//                if($atts['width'] == 'full'){
//                    $extra_class = 'pb_fullwidht';
//                    $script = "$(body).addClass('pb_fullwidht');";
//                    $custom_script = JSNPagebuilderHelpersShortcode::script_box($script);
//                    $arr_styles[] = '-webkit-box-sizing: content-box;-moz-box-sizing: content-box;box-sizing: content-box;width: 100%;padding-left: 1000px;padding-right: 1000px;margin:0 -1000px;';
//                }
//            }
			$background = "";
			switch ($atts['background'])
			{
				case 'solid':
					$solid_color = $atts['solid_color_value'];
					$background  = "background-color: $solid_color;";
					break;
				case 'gradient':
					if (!isset($atts['gradient_color_css']))
					{
						$background = "background:linear-gradient(center top , #ffffff 0%, #000000 100%);background:-moz-linear-gradient(center top , #ffffff 0%, #000000 100%);    background: -webkit-linear-gradient(#FFFFFF, #000000);background:-o-linear-gradient(center top , #ffffff 0%, #000000 100%);background:-ms-linear-gradient(center top , #ffffff 0%, #000000 100%);";
					}
					else
					{
						$background = $atts['gradient_color_css'];
					}
					break;
				case 'pattern':
					$pattern_img    = isset($atts['pattern']) ? $atts['pattern'] : '';
					$pattern_repeat = isset($atts['repeat']) ? $atts['repeat'] : '';

					$background = "background-image:url('$pattern_img');";
					switch ($pattern_repeat)
					{
						case 'full':
							$background_repeat = "repeat";
							break;
						case 'vertical':
							$background_repeat = "repeat-y";
							break;
						case 'horizontal':
							$background_repeat = "repeat-x";
							break;
						default :
							$background_repeat = "repeat";
					}
					$background .= "background-repeat:$background_repeat;";
					break;
				case 'image':
					if (isset($atts['image']))
					{
						$image = $atts['image'];
					}
					$image_position  = $atts['position'];
					$pattern_stretch = isset($atts['stretch']) ? $atts['stretch'] : '';

                    $url_pattern = '/^(http|https)/';
                    preg_match($url_pattern, $image, $_f);
                    if (!count($_f)) {
                        $image = JUri::root() . $image;
                    }
					$background = "background-image:url('$image');background-position:$image_position;";

					switch ($pattern_stretch)
					{
						case 'none':
							$background_size = "";
							break;
						case 'full':
							$background_size = "100% 100%";
							break;
						case 'cover':
							$background_size = "cover";
							break;
						case 'contain':
							$background_size = "contain";
							break;
					}
					$background .= !empty($background_size) ? "background-size:$background_size;" : "";
					break;

				case 'video':
					$url = $atts['video_url'];

					// Youtube video
					$pattern = '#^(?:https?://)?';    # Optional URL scheme. Either http or https.
					$pattern .= '(?:www\.)?';         #  Optional www subdomain.
					$pattern .= '(?:';                #  Group host alternatives:
					$pattern .=   'youtu\.be/';       #    Either youtu.be,
					$pattern .=   '|youtube\.com';    #    or youtube.com
					$pattern .=   '(?:';              #    Group path alternatives:
					$pattern .=     '/embed/';        #      Either /embed/,
					$pattern .=     '|/v/';           #      or /v/,
					$pattern .=     '|/watch\?v=';    #      or /watch?v=,
					$pattern .=     '|/watch\?.+&v='; #      or /watch?other_param&v=
					$pattern .=   ')';                #    End path alternatives.
					$pattern .= ')';                  #  End host alternatives.
					$pattern .= '([\w-]{11})';        # 11 characters (Length of Youtube video ids).
					$pattern .= '(?:.+)?$#x';         # Optional other ending URL parameters.

					if (preg_match( $pattern, $url, $matches ))
					{
						$extra_class .= 'pb_video_bg';

						$youtube_url = end($matches);

						$data_attr = sprintf(
							"data-property=\"{videoURL:'http://youtu.be/%s', containment:'%s', autoplay:%s, mute:true, startAt:0, opacity:1, showControls:false}\"",
							$youtube_url,
							"#$extra_id",
							$atts['autoplay']
						);
						$script = "
	                        (function($){
	                            $(document).ready(function(){
	                                $('.pb_video_bg').mb_YTPlayer();
	                                    $('.pb_video_bg').click(function(){ $(this).playYTP()})
	                            })
	                            })(jQuery);
	                            ";
						JFactory::getDocument()->addScriptDeclaration($script, 'text/javascript');

						self::enqueue_player_scripts();
					}
					else
					{
						JSNPagebuilderHelpersFunctions::print_asset_tag(JUri::root(true) . '/administrator/components/com_pagebuilder/helpers/shortcode/row/assets/css/row.css', 'css');
						$autoplay = $atts['autoplay'] == 1 ? 'autoplay="true"' : '';
						$script = "
							jQuery(document).ready(function($){
							var bgwidth = $('#$extra_id').width();
							var bgheight = $('#$extra_id').height();
							console.log(bgwidth)
							$('#$extra_id').css({'position':'relative','z-index':'1','overflow':'hidden'})
							$('video').attr({'width':bgheight, 'height': bgheight});
							$('object').attr({'width':bgheight, 'height': bgheight});
								$('video').mediaelementplayer({
								'loop':true,
								'clickToPlayPause': false,
								'controls': false,
								success: function(player, dom, mediaelement){
									mediaelement.container[0].style.position = 'absolute';
									mediaelement.container[0].style.zIndex = '1';
									$('.mejs-controls').css('display', 'none');
									player.play();
								}
								});

								var source = $('<object type=\"application/x-shockwave-flash\" width=\"'+bgwidth+'\" height=\"'+bgheight+'\" data=\"".JSNPB_PLG_SYSTEM_ASSETS_URL ."/3rd-party/mediaelement/flashmediaelement.swf\">'+
												'<param name=\"movie\" value=\"".JSNPB_PLG_SYSTEM_ASSETS_URL ."/3rd-party/mediaelement/flashmediaelement.swf\" />'+
                            					'<param name=\"flashVars\" value=\"controls=false&file=".$url."\" />'+
                            					'</object>'+
                                                '<img src=\"".  JSNPB_PLG_SYSTEM_ASSETS_URL ."/3rd-party/mediaelement/bigplay.png\" width=\"'+bgwidth+'\" height=\"'+bgheight+'\" title=\"No video playback capabilities\" />');
					            var video = $('<video controls=\"controls\" width=\"'+bgwidth+'\" height=\"\" $autoplay loop=\"true\" style=\"position: absolute; left: 0px; top: -20px; overflow: hidden; opacity: 1; transition-property: opacity; transition-duration: 2000ms; z-index: -1; min-width: 101%; min-height: 100%;\" ><source src=\"".$url."\" /></video>').append(source);

					            $('#$extra_id').append(video);
							});
						";
						JFactory::getDocument()->addScriptDeclaration($script,'text/javascript');
						$document = JFactory::getDocument();
						$document->addStyleSheet(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/mediaelement/mediaelementplayer.min.css', 'text/css');
						$document->addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/mediaelement/mediaelement-and-player.min.js', 'text/javascript');
					}
					break;
			}
			$arr_styles[] = $background;

			if (isset($atts['paralax']) && $atts['paralax'] == 'yes')
				$arr_styles[] = "background-attachment:fixed;";
			if (isset($atts['parallax_scroll']) && $atts['parallax_scroll'] == 'yes')
			{
				$extra_class .= 'parallax';
				$document = JFactory::getDocument();
				$document->addScript(JUri::root(true) . '/administrator/components/com_pagebuilder/helpers/shortcode/row/assets/js/parallax.js', 'text/javascript');
			}
			if (isset($atts['border_width_value_']) && intval($atts['border_width_value_']))
			{
				$border       = array();
				$border[]     = $atts['border_width_value_'] . "px";
				$border[]     = $atts['border_style'];
				$border[]     = $atts['border_color'];
				$border       = implode(" ", $border);
				$arr_styles[] = "border-top:$border; border-bottom:$border;";
			}

			$arr_styles[] = "padding-top:{$atts['div_padding_top']}px;";
			$arr_styles[] = "padding-bottom:{$atts['div_padding_bottom']}px;";
			$arr_styles[] = "padding-left:{$atts['div_padding_left']}px;";
			$arr_styles[] = "padding-right:{$atts['div_padding_right']}px;";
			if (@$atts['width_value'])
			{
				$arr_styles[] = "width:" . $atts['width_value'] . $atts['width_unit'] . '; margin:0 auto';
			}
			$arr_styles = implode("", $arr_styles);
			$style      = !empty($arr_styles) ? "style=\"$arr_styles\"" : "";
		}
		$column_html = empty($content) ? JSNPagebuilderHelpersShortcode::removeAutop($content) : JSNPagebuilderHelpersBuilder::generateShortCode($content, true, 'frontend');
		$extra_class .= !empty ($atts['css_suffix']) ? ' ' . htmlspecialchars($atts['css_suffix']) : '';
		$extra_class = ltrim($extra_class, ' ');

		return $common_style . "<div class='jsn-bootstrap3'><div id='$extra_id' class='$extra_class row' $style $data_attr>" . $column_html . "</div></div>";
	}

	static function enqueue_player_scripts()
	{
		$document = JFactory::getDocument();
		$document->addStyleSheet(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/YTPlayer/YTPlayer.css', 'text/css');
		$document->addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/YTPlayer/jquery.mb.YTPlayer.js', 'text/javascript');
	}
}

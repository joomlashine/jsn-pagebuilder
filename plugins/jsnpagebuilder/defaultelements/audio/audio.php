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
 * Audio shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeAudio extends IG_Pb_Element
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
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-select2/select2.min.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-select2/select2.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/js/colorpicker.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/css/colorpicker.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-colorpicker.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/audio/assets/js/audio-setting.js', 'js');
	}

	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config()
	{
		$this->config['shortcode']   = 'pb_audio';
		$this->config['name']        = JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO');
		$this->config['cat']         = JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_MEDIA');
		$this->config['icon']        = 'icon-audio';
		$this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_AUDIO_DESCRIPTION");
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
			// Audio source dropdown list on top.
			'generalaction' => array(
				'settings' => array(
					'id'    => 'general_action',
					'class' => 'general-action no-label pull-left',
				),
				array(
					'id'         => 'audio_sources',
					'type'       => 'select',
					'has_depend' => '1',
					'std'        => 'local',
					'options'    => array(
						'local'      => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_LOCAL_FILES'),
						'soundcloud' => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_SOUNDCLOUD')
					)
				)
			),
			// Content tab.
			'content'       => array(
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE'),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_ELEMENT_TITLE_STD'),
					'role'    => 'title',
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES')
				),
				array(
					'id'         => 'audio_source_link',
					'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_AUDIO_LINK'),
					'type'       => 'text_append',
					'type_input' => 'text',
					'dependency' => array('audio_sources', '=', 'soundcloud'),
					'class'      => 'span6 jsn-input-xxlarge-fluid',
				),
				array(
					'id'          => 'audio_source_local',
					'name'        => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_FILE_URL'),
					'type'        => 'select_media',
					'filter_type' => 'audio',
					'media_type'  => 'video',
					'class'       => 'jsn-input-large-fluid',
					'dependency'  => array('audio_sources', '=', 'local')
				),
			),
			// Styling tab .
			'styling'       => array(
				array(
					'type' => 'preview',
				),
				// SoundCloud parameters
				array(
					'name'                   => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_DIMENSION'),
					'container_class'        => 'combo-group',
					'dependency'             => array('audio_sources', '=', 'soundcloud'),
					'type'                   => 'dimension',
					'id'                     => 'audio_dimension',
					'extended_ids'           => array('audio_dimension_width', 'audio_dimension_height'),
					'audio_dimension_width'  => array('std' => '500'),
					'audio_dimension_height' => array('std' => '120')
				),

				array(
					'name'            => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENTS'),
					'id'              => 'audio_elements',
					'type'            => 'checkbox',
					'class'           => 'jsn-column-item  checkbox',
					'container_class' => 'jsn-columns-container jsn-columns-count-two',
					'dependency'      => array('audio_sources', '=', 'soundcloud'),
					'std'             => 'artwork__#__download_button__#__share_button__#__bpm__#__play_count__#__comments',
					'options'         => array(
						'artwork'         => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_ARTWORK'),
						'download_button' => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_DOWNLOAD_BUTTON'),
						'share_button'    => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_SHARE_BUTTON'),
						'bpm'             => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_BPM'),
						'play_count'      => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_PLAY_COUNT'),
						'comments'        => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_COMMENTS')
					)
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_AUTO_PLAY'),
					'id'         => 'audio_auto_play',
					'type'       => 'radio',
					'std'        => '0',
					'dependency' => array('audio_sources', '=', 'soundcloud'),
					'options'    => array(
						'1' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES'),
						'0' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO')
					)
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_START_TRACK'),
					'id'         => 'audio_start_track',
					'type'       => 'text_number',
					'dependency' => array('audio_sources', '=', 'soundcloud'),
					'class'      => 'input-mini',
				),
				/**
				 * Parameters for local audio
				 */
				array(
					'name'                         => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_DIMENSION'),
					'container_class'              => 'combo-group',
					'dependency'                   => array('audio_sources', '=', 'local'),
					'id'                           => 'audio_local_dimension',
					'type'                         => 'dimension',
					'extended_ids'                 => array('audio_local_dimension_width', 'audio_local_dimension_height'),
					'audio_local_dimension_width'  => array('std' => '500'),
					'audio_local_dimension_height' => array('std' => '30')
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENTS'),
					'id'              => 'audio_local_elements',
					'type'            => 'checkbox',
					'class'           => 'jsn-column-item checkbox',
					'container_class' => 'jsn-columns-container jsn-columns-count-two',
					'dependency'      => array('audio_sources', '=', 'local'),
					'std'             => 'play_button__#__current_time__#__time_rail__#__track_duration__#__volume_button__#__volume_slider',
					'options'         => array(
						'play_button'    => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_PLAY_PAUSE_BUTTON'),
						'current_time'   => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_CURRENT_TIME'),
						'time_rail'      => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_TIME_RAIL'),
						'track_duration' => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_TRACK_DURATION'),
						'volume_button'  => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_VOLUME_BUTTON'),
						'volume_slider'  => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_VOLUME_SLIDER')
					)
				),
				array(
					'name'         => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_START_VOLUME'),
					'id'           => 'audio_local_start_volume',
					'type'         => 'text_append',
					'type_input'   => 'number',
					'class'        => 'jsn-input-number input-mini',
					'parent_class' => 'combo-item',
					'std'          => '80',
					'append'       => '%',
					'dependency'   => array('audio_sources', '=', 'local'),
					'validate'     => 'number',
				),
				array(
					'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_LOOP'),
					'id'         => 'audio_local_loop',
					'type'       => 'radio',
					'std'        => 'false',
					'dependency' => array('audio_sources', '=', 'local'),
					'options'    => array(
						'true'  => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES'),
						'false' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO')
					)
				),
				array(
					'type' => 'hr',
				),
				// Basic audio parameters
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT'),
					'id'      => 'audio_alignment',
					'type'    => 'select',
					'std'     => 'center',
					'options' => array(
						'0'      => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO_ALIGNMENT'),
						'left'   => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_LEFT'),
						'right'  => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_RIGHT'),
						'center' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_CENTER')
					)
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_MARGIN'),
					'container_class' => 'combo-group',
					'id'              => 'audio_margin',
					'type'            => 'margin',
					'extended_ids'    => array('audio_margin_top', 'audio_margin_right', 'audio_margin_bottom', 'audio_margin_left')
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
		if (file_exists(dirname(__FILE__) . "/helpers/validate_file.php"))
		{
			$_initJSNPbAudioHelper = false;
			require_once dirname(__FILE__) . "/helpers/validate_file.php";
		}

		$objJSNPbAudioHelper = new JSNPbAudioHelper(false);

		$isValidFile = $objJSNPbAudioHelper->validateFile(@$atts['audio_source_link'], false);

		if ($atts['audio_sources'] == 'soundcloud')
		{
			$atts['audio_dimension_width'] = $atts['audio_dimension_width'] ? $atts['audio_dimension_width'] : '100%';
			$arr_params                    = (JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts));
			if (empty($arr_params['audio_source_link']))
			{
				$html_element = "<div class='alert alert-warning'><p class='jsn-bglabel' style='padding-top:0; color:inherit;'>" . JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_NO_AUDIO_SOURCE_SELECTED') . '</p></div>';
			}
			elseif (isset($isValidFile['valid']) && !$isValidFile["valid"])
			{
				$html_element = "<div class='alert alert-warning'><p class='jsn-bglabel' style='padding-top:0; color:inherit;'>" . JText::_($isValidFile["message"]) . '</p></div>';
			}
			else
			{
				$html_element = $this->generate_sound_cloud($arr_params);
			}
		}
		else if ($atts['audio_sources'] == 'local')
		{
			$atts['audio_local_dimension_width'] = $atts['audio_local_dimension_width'] ? $atts['audio_local_dimension_width'] : '100%';
			$arr_params                          = (JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts));
			if (empty($arr_params['audio_source_local']))
			{
				$html_element = "<p class='jsn-bglabel'>" . JText::_('JSN_PAGEBUILDER_ELEMENT_AUDIO_NO_AUDIO_SOURCE_SELECTED') . '</p>';
			}
			else
			{
				$this->load_local_audio_script();
				$html_element = $this->generate_local_files($arr_params);
			}
		}

		return $this->element_wrapper($html_element, $arr_params);
	}

	/**
	 * Method to generate HTML code for SoundCloud
	 *
	 * @param array $params
	 *
	 * @return string HTML code
	 */
	private function generate_sound_cloud($params)
	{
		$random_id = JSNPagebuilderHelpersShortcode::generateRandomString();

		// Proceed embed code dimensions
		$_w = $params['audio_dimension_width'];
		$_h = $params['audio_dimension_height'] ? $params['audio_dimension_height'] : '';
		$_w = ' width="' . $_w . '" ';
		$_h = $_h ? ' height="' . $_h . '" ' : '';

		$params['audio_elements'] = explode('__#__', $params['audio_elements']);

		// Container style
		$container_class = (isset($params['audio_container_style']) && $params['audio_container_style'] != '0') ? $params['audio_container_style'] . ' ' : '';

		$container_style = $object_style = '';

		if ($params['audio_alignment'] === 'right')
		{
			$object_style .= 'float:right;';
			$container_class .= 'clearafter ';
		}
		else if ($params['audio_alignment'] === 'center')
		{
			$object_style .= 'margin: 0 auto;';
		}
		else if ($params['audio_alignment'] === 'left')
		{
			$object_style .= 'float:left;';
			$container_class .= 'clearafter ';
		}

		// Genarate Container class
		$container_class = $container_class ? ' class="' . $container_class . '" ' : '';

		$container_style .= (isset($params['audio_margin_left']) && $params['audio_margin_left'] != '') ? 'margin-left:' . $params['audio_margin_left'] . 'px;' : '';
		$container_style .= (isset($params['audio_margin_top']) && $params['audio_margin_top'] != '') ? 'margin-top:' . $params['audio_margin_top'] . 'px;' : '';
		$container_style .= (isset($params['audio_margin_right']) && $params['audio_margin_right'] != '') ? 'margin-right:' . $params['audio_margin_right'] . 'px;' : '';
		$container_style .= (isset($params['audio_margin_bottom']) && $params['audio_margin_bottom'] != '') ? 'margin-bottom:' . $params['audio_margin_bottom'] . 'px;' : '';
		$container_style .= (isset($params['audio_alignment']) && $params['audio_alignment'] === 'center') ? 'text-align: center' : '';
		$container_style = $container_style ? ' style=" ' . $container_style . ' " ' : '';

		$embed = '<div ' . $container_class . $container_style . '>';
		$embed .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="' . $random_id . '" style="' . $object_style . '"';
		$embed .= $_w . $_h;
		$embed .= '>';

		// Generate soundcloud URL with parameters
		$source_url = $params['audio_source_link'];

		$source_url .= in_array('artwork', $params['audio_elements']) ? '&show_artwork=true' : '&show_artwork=false';
		$source_url .= in_array('download_button', $params['audio_elements']) ? '&download=true' : '&download=false';
		$source_url .= in_array('share_button', $params['audio_elements']) ? '&sharing=true' : '&sharing=false';
		$source_url .= in_array('bpm', $params['audio_elements']) ? '&show_bpm=true' : '&show_bpm=false';
		$source_url .= in_array('play_count', $params['audio_elements']) ? '&show_playcount=true' : '&show_playcount=false';
		$source_url .= in_array('comments', $params['audio_elements']) ? '&show_comments=true' : '&show_comments=false';
		$source_url .= $params['audio_auto_play'] ? '&auto_play=true' : '&auto_play=false';
		$source_url .= $params['audio_start_track'] ? '&start_track=' . ((int ) $params['audio_start_track'] - 1) : '';

		// Combine HTML
		$embed .= '<param name="movie"
					value="' . $source_url . '">
					</param>';

		$embed .= '<param name="allowscriptaccess" value="always"></param>';
		$embed .= '<iframe id="sc-widget" name="' . $random_id . ' " ' . $_w . $_h . '" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=' . $source_url . '"></iframe>';
		$embed .= '<script src="https://w.soundcloud.com/player/api.js" type="text/javascript"></script>';
		$embed .= '<script type="text/javascript">(function(){var widgetIframe = document.getElementById("sc-widget"),widget = SC.Widget(widgetIframe),newSoundUrl = "' . $source_url . '";widget.bind(SC.Widget.Events.READY, function() {widget.bind(SC.Widget.Events.FINISH, function() {widget.load(newSoundUrl, {show_artwork: false});});});}());</script>';
		$embed .= '</object>';
		$embed .= '</div>';

		return $embed;
	}

	/**
	 * Generate HTML for local audio player
	 */
	function generate_local_files($params)
	{
		$random_id            = JSNPagebuilderHelpersShortcode::generateRandomString();
		$audio_size           = array();
		$audio_size['width']  = ' width="' . $params['audio_local_dimension_width'] . '" ';
		$audio_size['height'] = ($params['audio_local_dimension_height'] != '') ? ' height="' . $params['audio_local_dimension_height'] . '" ' : '';

		$player_options = '{';
		$player_options .= ($params['audio_local_start_volume'] != '') ? 'startVolume: ' . ( int ) $params['audio_local_start_volume'] / 100 . ',' : '';
		$player_options .= ($params['audio_local_loop'] != '') ? 'loop: ' . $params['audio_local_loop'] . ',' : '';


		if (!isset($params['audio_local_player_image']))
		{
			$_player_color = isset($params['audio_local_player_color']) ? '$( ".mejs-mediaelement, .mejs-controls", audio_container ).css( "background", "none repeat scroll 0 0 ' . $params['audio_local_player_color'] . '" );' : '';
		}
		else
		{
			$_player_color = isset($params['audio_local_player_color']) ? '$( ".mejs-mediaelement, .mejs-controls", audio_container ).css( "background", "url(\'' . $params['audio_local_player_image'] . '\' ) repeat scroll 0 0 ' . $params['audio_local_player_color'] . '");' : '';
		}

		$_progress_bar_color = isset($params['audio_local_progress_color']) ? '$( ".mejs-time-loaded, .mejs-horizontal-volume-current", audio_container ).css( "background", "none repeat scroll 0 0 ' . $params['audio_local_progress_color'] . '" );' : '';

		$params['audio_local_elements'] = explode('__#__', $params['audio_local_elements']);
		$player_elements                = '';
		$player_elements .= in_array('play_button', $params['audio_local_elements']) ? '' : '$( ".mejs-playpause-button", audio_container ).hide();';
		$player_elements .= in_array('current_time', $params['audio_local_elements']) ? '' : '$( ".mejs-currenttime-container", audio_container ).hide();';
		$player_elements .= in_array('time_rail', $params['audio_local_elements']) ? '' : '$( ".mejs-time-rail", audio_container ).hide();';
		$player_elements .= in_array('track_duration', $params['audio_local_elements']) ? '' : '$( ".mejs-duration-container", audio_container ).hide();';
		$player_elements .= in_array('volume_button', $params['audio_local_elements']) ? '' : '$( ".mejs-volume-button", audio_container ).hide();';
		$player_elements .= in_array('volume_slider', $params['audio_local_elements']) ? '' : '$( ".mejs-horizontal-volume-slider", audio_container ).hide();';

		$container_class = '';
		if ($params['audio_alignment'] === 'right')
		{
			$player_elements .= 'audio_container.css( "float", "right" )';
			$container_class .= 'clearafter ';
		}
		else if ($params['audio_alignment'] === 'center')
		{
			$player_elements .= 'audio_container.css( "margin", "0 auto" )';
		}
		else if ($params['audio_alignment'] === 'left')
		{
			$player_elements .= 'audio_container.css( "float", "left" )';
			$container_class .= 'clearafter ';
		}
		// Genarate Container class
		$container_class = $container_class ? ' class="' . $container_class . '" ' : '';

		$player_options .= 'success: function( mediaElement, domObject ){
			
			var audio_container	= $( domObject ).parents( ".mejs-container" );
			' . $player_elements . '
		},';
		$player_options .= 'keyActions:[]}';

		if (isset($params['audio_source_local']) && !empty($params['audio_source_local']))
		{
			$script = 'JoomlaShine.jQuery( document ).ready( function ($ ){
				new MediaElementPlayer("#' . $random_id . '",
					' . $player_options . '
				);
			});';

			$document = JFactory::getDocument();
			$document->addScriptDeclaration($script, 'text/javascript');
		}

		$container_style = '';
		$container_style .= (isset($params['audio_margin_left']) && $params['audio_margin_left'] != '') ? 'margin-left:' . $params['audio_margin_left'] . 'px;' : '';
		$container_style .= (isset($params['audio_margin_top']) && $params['audio_margin_top'] != '') ? 'margin-top:' . $params['audio_margin_top'] . 'px;' : '';
		$container_style .= (isset($params['audio_margin_right']) && $params['audio_margin_right'] != '') ? 'margin-right:' . $params['audio_margin_right'] . 'px;' : '';
		$container_style .= (isset($params['audio_margin_bottom']) && $params['audio_margin_bottom'] != '') ? 'margin-bottom:' . $params['audio_margin_bottom'] . 'px;' : '';
		$container_style = $container_style ? ' style=" ' . $container_style . ' " ' : '';

		// Define the media type
		$src    = str_replace(' ', '+', $params['audio_source_local']);
		$source = '<source type="%s" src="%s" />';
		$type   = JSNPagebuilderHelpersShortcode::checkFiletype($src);
		$source = sprintf($source, $type['type'], $src);

		return '<div ' . $container_class . $container_style . '>
								<audio controls="controls" preload="none" ' . $audio_size['width'] . $audio_size['height'] . ' id="' . $random_id . '" src="' . $src . '" >
									' . $source . '
								</audio>
							</div><div style="clear: both"></div>';
	}

	/**
	 * Method to load needed script
	 * to render local audio player.
	 */
	function load_local_audio_script()
	{
		if (JFactory::getApplication()->isAdmin())
		{
			JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/mediaelement/mediaelement.min.js', 'js');
			JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/mediaelement/mediaelementplayer.min.js', 'js');
			JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/mediaelement/mediaelementplayer.min.css', 'css');
		}
		else
		{
			$document = JFactory::getDocument();
			$document->addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/mediaelement/mediaelement.min.js', 'text/javascript');
			$document->addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/mediaelement/mediaelementplayer.min.js', 'text/javascript');
			$document->addStyleSheet(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/mediaelement/mediaelementplayer.min.css', 'text/css');
		}
	}

}

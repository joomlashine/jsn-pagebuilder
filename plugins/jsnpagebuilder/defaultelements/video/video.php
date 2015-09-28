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
 * Video shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeVideo extends IG_Pb_Element {

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
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-select2/select2.min.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-select2/select2.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/js/colorpicker.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-colorpicker/css/colorpicker.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-colorpicker.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ELEMENT_URL.'/video/assets/js/video-setting.js', 'js' );
	}
	
	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config() {
		$this->config['shortcode'] = 'pb_video';
		$this->config['name']      = JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO' );
		$this->config['cat']       = JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_MEDIA' );
		$this->config['icon']      = 'icon-video';
		$this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_VIDEO_DES");
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
			// video source dropdown list on top.
			'generalaction' => array(
				'settings' => array(
					'id'    => 'general_action',
					'class' => 'general-action no-label pull-left',
				),
				array(
					'id'         => 'video_sources',
					'type'       => 'select',
					'has_depend' => '1',
					'std'        => 'local',
					'class'      => '',
					'options'    => array(
						'local'   => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_LOCAL_FILE' ),
						'youtube' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_YOUTUBE' ),
						'vimeo'   => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_VIMEO' )
					)
				)
			),
			// Content Tab
			'content' => array(
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE' ),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_ELEMENT_TITLE_STD' ),
					'role'    => 'title',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES' )
				),
				array(
					'id'          => 'video_source_local',
					'name'        => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_FILE_URL' ),
					'type'        => 'select_media',
					'filter_type' => 'video',
					'media_type'  => 'video',
					'class'       => 'jsn-input-large-fluid',
					'dependency'  => array( 'video_sources', '=', 'local' ),
					'tooltip'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_FILE_URL_DES' ),
				),
				// Youtube.
				array(
					'id'         => 'video_source_link_youtube',
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_VIDEO_LINK' ),
					'type'       => 'text_append',
					'type_input' => 'text',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'class'      => 'span6 jsn-input-xxlarge-fluid',
					'tooltip'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_VIDEO_LINK_DES' ),
				),
				// Vimeo.
				array(
					'id'         => 'video_source_link_vimeo',
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_VIDEO_LINK' ),
					'type'       => 'text_append',
					'type_input' => 'text',
					'dependency' => array( 'video_sources', '=', 'vimeo' ),
					'class'      => 'span6 jsn-input-xxlarge-fluid',
					'tooltip'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_VIDEO_LINK_DES' ),
				),
			),
			// Styling tab .
			'styling' => array(
				array(
					'type' => 'preview',
				),
				/**
				 * Parameters for local video
				 */
				array(
					'name'                         => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_DIMENSION' ),
					'container_class'              => 'combo-group',
					'dependency'                   => array( 'video_sources', '=', 'local' ),
					'id'                           => 'video_local_dimension',
					'type'                         => 'dimension',
					'extended_ids'                 => array( 'video_local_dimension_width', 'video_local_dimension_height' ),
					'video_local_dimension_width'  => array( 'std' => '500' ),
					'video_local_dimension_height' => array( 'std' => '330' ),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_DIMENSION_DES' ),
				),
				array(
					'name'            => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENTS' ),
					'id'              => 'video_local_elements',
					'type'            => 'checkbox',
					'class'           => 'jsn-column-item checkbox',
					'container_class' => 'jsn-columns-container jsn-columns-count-two',
					'dependency'      => array( 'video_sources', '=', 'local' ),
					'std'             => 'play_button__#__overlay_play_button__#__current_time__#__time_rail__#__track_duration__#__volume_button__#__volume_slider__#__fullscreen_button',
					'options'         => array(
						'play_button' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_PLAY_PAUSE_BUTTON' ),
						'overlay_play_button' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_OVERLAY_PLAY_BUTTON' ),
						'current_time'        => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_CURRENT_TIME' ),
						'time_rail'           => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_TIME_RAIL' ),
						'track_duration'      => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_TRACK_DURATION' ),
						'volume_button'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_VOLUME_BUTTON' ),
						'volume_slider'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_VOLUME_SLIDER' ),
						'fullscreen_button'   => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_FULLSCREEN_BUTTON' )
					),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENTS_DES' ),
				),
				array(
					'name'         => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_START_VOLUME' ),
					'id'           => 'video_local_start_volume',
					'type'         => 'text_append',
					'type_input'   => 'number',
					'class'        => 'jsn-input-number input-mini',
					'parent_class' => 'combo-item',
					'std'          => '80',
					'append'       => '%',
					'dependency'   => array( 'video_sources', '=', 'local' ),
					'validate'     => 'number',
					'tooltip'      => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_START_VOLUME_DES' ),
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_LOOP' ),
					'id'         => 'video_local_loop',
					'type'       => 'radio',
					'std'        => 'false',
					'dependency' => array( 'video_sources', '=', 'local' ),
					'options'    => array(
						'true'  => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ),
						'false' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' )
					),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_LOOP_DES' ),
				),
				// Youtube video parameters
				array(
					'name'                           => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_DIMENSION' ),
					'container_class'                => 'combo-group',
					'dependency'                     => array( 'video_sources', '=', 'youtube' ),
					'id'                             => 'video_youtube_dimension',
					'type'                           => 'dimension',
					'extended_ids'                   => array( 'video_youtube_dimension_width', 'video_youtube_dimension_height' ),
					'video_youtube_dimension_width'  => array( 'std' => '500' ),
					'video_youtube_dimension_height' => array( 'std' => '270' ),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_DIMENSION_DES' ),
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_SHOW_LIST' ),
					'id'         => 'video_youtube_show_list',
					'type'       => 'radio',
					'std'        => '0',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'options'    => array(
						'1' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ),
						'0' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' )
					)
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_AUTO_PLAY' ),
					'id'         => 'video_youtube_autoplay',
					'type'       => 'radio',
					'std'        => '0',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'options'    => array(
						'1' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ),
						'0' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' )
					),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_AUTO_PLAY_DES' ),
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_LOOP' ),
					'id'         => 'video_youtube_loop',
					'type'       => 'radio',
					'std'        => '0',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'options'    => array(
						'1' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ),
						'0' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' )
					),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_LOOP_DES' ),
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_CONTROLS_AUTO_HIDE' ),
					'id'         => 'video_youtube_autohide',
					'type'       => 'select',
					'std'        => '2',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'options'    => array(
						'2' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_CONTROLS_AUTO_HIDE_AUTO_MINIMIZE_PROGRESS_BAR' ),
						'1' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_CONTROLS_AUTO_HIDE_BOTH_AFTER_PLAYING_A_COUPLE_SECONDS' ),
						'0' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_CONTROLS_AUTO_HIDE_NEVER_HIDE' )
					),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_CONTROLS_AUTO_HIDE_DES' ),
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_SHOW_CAPTION_CC' ),
					'id'         => 'video_youtube_cc',
					'type'       => 'radio',
					'std'        => '0',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'options'    => array(
						'1' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NEVER' ),
						'0' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' )
					),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_SHOW_CAPTION_CC_DES' ),
				),
				// Vimeo video parameters
				array(
					'name'                         => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_DIMENSION' ),
					'container_class'              => 'combo-group',
					'dependency'                   => array( 'video_sources', '=', 'vimeo' ),
					'id'                           => 'video_vimeo_dimension',
					'type'                         => 'dimension',
					'extended_ids'                 => array( 'video_vimeo_dimension_width', 'video_vimeo_dimension_height' ),
					'video_vimeo_dimension_width'  => array( 'std' => '500' ),
					'video_vimeo_dimension_height' => array( 'std' => '270' ),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_DIMENSION_DES' ),
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_AUTO_PLAY' ),
					'id'         => 'video_vimeo_autoplay',
					'type'       => 'radio',
					'std'        => 'false',
					'dependency' => array( 'video_sources', '=', 'vimeo' ),
					'options'    => array(
						'true'  => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ),
						'false' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' )
					),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_AUTO_PLAY_DES' ),
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_LOOP' ),
					'id'         => 'video_vimeo_loop',
					'type'       => 'radio',
					'std'        => 'false',
					'dependency' => array( 'video_sources', '=', 'vimeo' ),
					'options'    => array(
						'true'  => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ),
						'false' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' )
					),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_LOOP_DES' ),
				),
				array(
					'name'       => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_CONTROLS_COLOR' ),
					'id'         => 'video_vimeo_color',
					'type'       => 'color_picker',
					'std'        => '#54BBFC',
					'dependency' => array( 'video_sources', '=', 'vimeo' ),
					'hide_value' => true,
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_CONTROLS_COLOR_DES' ),
				),
				array(
					'type' => 'hr',
				),
				// Basic styling parameters
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT' ),
					'id'      => 'video_alignment',
					'type'    => 'select',
					'std'     => 'center',
					'options' => array(
						'0'      => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO_ALIGNMENT' ),
						'left'   => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_LEFT' ),
						'right'  => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_RIGHT' ),
						'center' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_CENTER' ),
					),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_DES' ),
				),
				array(
					'name'            => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_MARGIN' ),
					'container_class' => 'combo-group',
					'id'              => 'video_margin',
					'type'            => 'margin',
					'extended_ids'    => array( 'video_margin_top', 'video_margin_right', 'video_margin_bottom', 'video_margin_left' ),
					'tooltip'         => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_MARGIN_DES' ),
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
		$html_element = '';
		if ( $atts['video_sources'] == 'local' ) {
			$atts['video_local_dimension_width'] = $atts['video_local_dimension_width'] ? $atts['video_local_dimension_width'] : '100%';
			$arr_params                          = ( JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts ) );
			if ( empty( $arr_params['video_source_local'] ) ){
				$html_element = "<p class='jsn-bglabel'>" . JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_NO_VIDEO_FILE_SELECTED' ) . '</p>';
			} else {
				$this->load_local_video_assets();
				$html_element = $this->generate_local_file( $arr_params );
			}
		} else if ( $atts['video_sources'] == 'youtube' ) {
			$atts['video_youtube_dimension_width'] = $atts['video_youtube_dimension_width'] ? $atts['video_youtube_dimension_width'] : '100%';
			$arr_params                            = ( JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts ) );
			if ( empty( $arr_params['video_source_link_youtube'] ) ){
				$html_element = "<p class='jsn-bglabel'>" . JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_NO_VIDEO_FILE_SELECTED' ) . '</p>';
			} else {
				$html_element = $this->generate_youtube( $arr_params );
			}
		} else if ( $atts['video_sources'] == 'vimeo' ) {
			$atts['video_vimeo_dimension_width'] = $atts['video_vimeo_dimension_width'] ? $atts['video_vimeo_dimension_width'] : '100%';
			$arr_params                              = ( JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts ) );
			if ( empty( $arr_params['video_source_link_vimeo'] ) ){
				$html_element = "<p class='jsn-bglabel'>" . JText::_( 'JSN_PAGEBUILDER_ELEMENT_VIDEO_NO_VIDEO_FILE_SELECTED' ) . '</p>';
			} else {
				$html_element = $this->generate_vimeo( $arr_params );
			}
		}

		return $this->element_wrapper( $html_element, $arr_params );
	}

	/**
	 * Generate HTML for local video player
	 * 
	 * @return string
	 */
	function generate_local_file( $params ) {
		$random_id = JSNPagebuilderHelpersShortcode::generateRandomString();
		$video_size = array();
		$video_size['width']  = ' width="' . $params['video_local_dimension_width'] . '" ';
		$video_size['height'] = ( $params['video_local_dimension_height'] != '' ) ? ' height="' . $params['video_local_dimension_height'] . '" ' : '';

		$player_options = '{';
		$player_options .= $params['video_local_start_volume'] ? 'startVolume: ' . ( int ) $params['video_local_start_volume'] / 100 . ',' : '';
		$player_options .= $params['video_local_loop'] ? 'loop: ' . $params['video_local_loop'] . ',' : '';

		$_progress_bar_color = isset($params['video_local_progress_color']) ? '$(".mejs-time-loaded, .mejs-horizontal-volume-current", video_container).css("background", "none repeat scroll 0 0 ' . $params['video_local_progress_color'] . '");' : '';

		$params['video_local_elements'] = explode( '__#__', $params['video_local_elements'] );

		$player_elements = '';
		$player_elements .= in_array( 'play_button', $params['video_local_elements'] ) ? '' : '$(".mejs-playpause-button", video_container).hide();';
		$player_elements .= in_array( 'overlay_play_button', $params['video_local_elements'] ) ? '' : '$(".mejs-overlay-button", video_container).hide();';
		$player_elements .= in_array( 'current_time', $params['video_local_elements'] ) ? '' : '$(".mejs-currenttime-container", video_container).hide();';
		$player_elements .= in_array( 'time_rail', $params['video_local_elements'] ) ? '' : '$(".mejs-time-rail", video_container).hide();';
		$player_elements .= in_array( 'track_duration', $params['video_local_elements'] ) ? '' : '$(".mejs-duration-container", video_container).hide();';
		$player_elements .= in_array( 'volume_button', $params['video_local_elements'] ) ? '' : '$(".mejs-volume-button", video_container).hide();';
		$player_elements .= in_array( 'volume_slider', $params['video_local_elements'] ) ? '' : '$(".mejs-horizontal-volume-slider", video_container).hide();';
		$player_elements .= in_array( 'fullscreen_button', $params['video_local_elements'] ) ? '' : '$(".mejs-fullscreen-button", video_container).hide();';

		// Alignment
		$container_class = 'local_file ';
		$container_style = '';
		if ( $params['video_alignment'] === 'right' ) {
			$container_style .= 'float: right;';
			$container_class .= 'clearafter ';
		} else if ( $params['video_alignment'] === 'center' ) {
			$container_style .= 'margin: 0 auto;';
		} else if ( $params['video_alignment'] === 'left' ) {
			$container_style .= 'float: left;';
			$container_class .= 'clearafter ';
		}
		// Genarate Container class
		$container_class = $container_class ? ' class="' . $container_class . '" ' : '';

		$player_options .= 'defaultVideoHeight:' . ( intval( $params['video_local_dimension_height'] ) - 10 ) . ',';
		$player_options .= 'success: function(mediaElement, domObject){

var video_container= $(domObject).parents(".mejs-container");
' . $player_elements . '
},';
		$player_options .= 'keyActions:[], pluginPath:"' . JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/mediaelement/' . '"}';

		if ( isset( $params['video_source_local'] ) && $params['video_source_local'] != '' ) {
			$script = '
			JoomlaShine.jQuery(document).ready(function ($){
			new MediaElementPlayer("#' . $random_id . '",
			' . $player_options . '
			);
			});';
			$document = JFactory::getDocument();
			$document->addScriptDeclaration( $script, 'text/javascript' );
		}

		$container_style .= (isset($params['video_margin_left']) && $params['video_margin_left'] != '') ? 'margin-left:' . $params['video_margin_left'] . 'px;' : '';
		$container_style .= (isset($params['video_margin_top']) && $params['video_margin_top'] != '') ? 'margin-top:' . $params['video_margin_top'] . 'px;' : '';
		$container_style .= (isset($params['video_margin_right']) && $params['video_margin_right'] != '') ? 'margin-right:' . $params['video_margin_right'] . 'px;' : '';
		$container_style .= (isset($params['video_margin_bottom']) && $params['video_margin_bottom'] != '') ? 'margin-bottom:' . $params['video_margin_bottom'] . 'px;' : '';
		// This under is the fix for Chrome video dimension issue
		$container_style .= 'width: ' . $params['video_local_dimension_width'] . 'px;';
		$container_style .= 'height: ' . ($params['video_local_dimension_height'] + 5) . 'px;';

		$container_style = $container_style ? ' style=" ' . $container_style . ' " ' : '';

		// Define the media type
		$src    = str_replace( ' ', '+', urldecode( $params['video_source_local'] ) );
		$source = '<source type="%s" src="%s" />';
		$type   = JSNPagebuilderHelpersShortcode::checkFiletype( $src );
		$source = sprintf( $source, $type['type'], $src );

		$video  = '<video id="' . $random_id . '" ' . $video_size['width'] . $video_size['height'] . ' controls="controls" preload="none" src="' . $src . '">
' . $source . '
</video>';

		return '<div ' . $container_class . $container_style . '>'
				. $video . '
</div><div class="clear:both"></div>';
	}

	/**
	 * Generate HTML for Youtube
	 * 
	 * @return string
	 */
	function generate_youtube( $params ) {
		$random_id = JSNPagebuilderHelpersShortcode::generateRandomString();

		$_w = ' width="' . $params['video_youtube_dimension_width'] . '" ';
		$_h = $params['video_youtube_dimension_height'] ? ' height="' . $params['video_youtube_dimension_height'] . '" ' : '';

		// Alignment
		$container_class = '';
		$object_style = '';
		if ( $params['video_alignment'] === 'right' ) {
			$object_style    .= 'float:right;';
			$container_class .= 'clearafter ';
		} else if ( $params['video_alignment'] === 'center' ) {
			$object_style .= 'margin: 0 auto;';
		} else if ( $params['video_alignment'] === 'left' ) {
			$object_style    .= 'float:left;';
			$container_class .= 'clearafter ';
		}

		// Genarate Container class
		$container_class = $container_class ? 'class="' . $container_class . '" ' : '';

		// Margin.
		$container_style = '';
		$container_style .= (isset($params['video_margin_left']) && $params['video_margin_left'] != '') ? 'margin-left:' . $params['video_margin_left'] . 'px;' : '';
		$container_style .= (isset($params['video_margin_top']) && $params['video_margin_top'] != '') ? 'margin-top:' . $params['video_margin_top'] . 'px;' : '';
		$container_style .= (isset($params['video_margin_right']) && $params['video_margin_right'] != '') ? 'margin-right:' . $params['video_margin_right'] . 'px;' : '';
		$container_style .= (isset($params['video_margin_bottom']) && $params['video_margin_bottom'] != '') ? 'margin-bottom:' . $params['video_margin_bottom'] . 'px;' : '';
		$container_style = $container_style ? ' style=" ' . $container_style . ' " ' : '';

		$params['video_source_link_youtube'] = urldecode( $params['video_source_link_youtube'] );
		// Get video ID.
		$video_info = JSNPbVideoHelper::getYoutubeVideoInfo( $params['video_source_link_youtube'] );
		$video_info = json_decode( $video_info );
		if ( ! $video_info )
			return;
		$video_info = $video_info->html;
		$_arr = array();
		$video_src = '';
		preg_match( '/src\s*\n*=\s*\n*"([^"]*)"/i', $video_info, $_arr );

		if ( count( $_arr ) ) {
			// Check if video url included playlist id.
			$pattern = '#list=([A-Za-z0-9^/]*)#i';
			$matches = array();
			preg_match_all( $pattern, $params['video_source_link_youtube'], $matches, PREG_SET_ORDER );

			if ( count( $matches ) ) {
				if ( isset( $params['video_youtube_show_list'] ) && $params['video_youtube_show_list'] == '1' ) {
					$video_src = 'http://www.youtube.com/embed?listType=playlist&list=';
					$_list_id = $matches[0][1];
					$video_src .= $_list_id;
					$video_src .= '&innerframe=true';
				} else {
					$video_src = $_arr[1];
					$video_src .= '&innerframe=true';
				}
			} else {
				$video_src = $_arr[1];
				$video_src .= '&innerframe=true';
			}

			$video_src .= isset($params['video_youtube_autoplay']) ? '&autoplay=' . (int) $params['video_youtube_autoplay'] : '';
			$video_src .= isset($params['video_youtube_autohide']) ? '&autohide=' . (int) $params['video_youtube_autohide'] : '';
			$video_src .= isset($params['video_youtube_controls']) ? '&controls=' . (int) $params['video_youtube_controls'] : '';
            // Specific playlist id for feature loop youtube video.
            $videoYoutubeLoop = isset($params['video_youtube_loop']) ? ($params['video_youtube_loop'] == '1' ? true : false) : false;
            if ($videoYoutubeLoop) {
                $video_src .= '&loop=1';
                $youtube_id = isset( $params['video_source_link_youtube'] ) ? $this->getYoutubeID( $params['video_source_link_youtube'] ) : '';
                $video_src .= $youtube_id  ? '&playlist=' . $youtube_id : '';
            }
			$video_src .= (isset($params['video_youtube_cc']) && (int) $params['video_youtube_cc'] == 1) ? '&cc_load_policy =1' : '';
		}
		
		$video_src .= '&showinfo=0';
		$embed = '<div ' . $container_class . $container_style . '>';
		$embed .= '<iframe style="display:block;' . $object_style . '" ' . $_w . $_h . 'src="' . $video_src . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		$embed .= '</div>';
        $embed .= '<div class="clear:both"></div>';

		return $embed;
	}

	/**
	 * Get youtube video id from URL.
	 * 
	 * @param string $url
	 * 
	 * @return string
	 */
	function getYoutubeID( $url = '' )
	{
		if ( ! $url )
			return '';
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
		preg_match( $pattern, $url, $matches );
		return ( isset( $matches[1] ) ) ? $matches[1] : false;
	}
	
	/**
	 * Generate HTML for Vimeo
	 * 
	 * @return string
	 */
	function generate_vimeo( $params ) {
		$random_id = JSNPagebuilderHelpersShortcode::generateRandomString();

		$_w = ' width="' . $params['video_vimeo_dimension_width'] . '" ';
		$_h = $params['video_vimeo_dimension_height'] ? ' height="' . $params['video_vimeo_dimension_height'] . '" ' : '';
		// Alignment
		$container_class = '';
		$object_style = '';
		if ( $params['video_alignment'] === 'right' ) {
			$object_style    .= 'float:right;';
			$container_class .= 'clearafter ';
		} else if ( $params['video_alignment'] === 'center' ) {
			$object_style .= 'margin: 0 auto;';
		} else if ( $params['video_alignment'] === 'left' ) {
			$object_style    .= 'float:left;';
			$container_class .= 'clearafter ';
		}

		// Genarate Container class
		$container_class = $container_class ? 'class="' . $container_class . '" ' : '';

		// Margin.
		$container_style = '';
		$container_style .= (isset($params['video_margin_left']) && $params['video_margin_left'] != '') ? 'margin-left:' . $params['video_margin_left'] . 'px;' : '';
		$container_style .= (isset($params['video_margin_top']) && $params['video_margin_top'] != '') ? 'margin-top:' . $params['video_margin_top'] . 'px;' : '';
		$container_style .= (isset($params['video_margin_right']) && $params['video_margin_right'] != '') ? 'margin-right:' . $params['video_margin_right'] . 'px;' : '';
		$container_style .= (isset($params['video_margin_bottom']) && $params['video_margin_bottom'] != '') ? 'margin-bottom:' . $params['video_margin_bottom'] . 'px;' : '';
		$container_style = $container_style ? ' style=" ' . $container_style . ' " ' : '';

		// Get video ID.
		$params['video_source_link_vimeo'] = urldecode( $params['video_source_link_vimeo'] );
		$video_info                        = JSNPbVideoHelper::getVimeoVideoInfo( $params['video_source_link_vimeo'] );
		$video_info                        = json_decode( $video_info );
		if ( ! $video_info )
			return;
		$video_info = $video_info->html;
		$_arr = array();
		$video_src = '';
		preg_match( '/src\s*\n*=\s*\n*"([^"]*)"/i', $video_info, $_arr );
		if ( count( $_arr ) ) {
			$video_src = $_arr[1];
			$video_src .= '?innerframe=true';
			$video_src .= isset($params['video_vimeo_autoplay']) ? '&autoplay=' . (string) $params['video_vimeo_autoplay'] : '';
			$video_src .= isset($params['video_vimeo_loop']) ? '&loop=' . (string) $params['video_vimeo_loop'] : '';
			$video_src .= isset($params['video_vimeo_title']) ? '&title=' . (string) $params['video_vimeo_title'] : '';
			$video_src .= isset($params['video_vimeo_color']) ? '&color=' . str_replace( '#', '', (string) $params['video_vimeo_color'] ) : '';
		}

		$embed = '<div ' . $container_class . $container_style . '>';
		$embed .= '<iframe webkitallowfullscreen mozallowfullscreen allowfullscreen style="display:block;' . $object_style . '" ' . $_w . $_h . '"
src="' . $video_src . '" frameborder="0"></iframe>';
		$embed .= '</div>';
        $embed .= '<div class="clear:both"></div>';

		return $embed;
	}

	/**
	 * Method to load needed assets
	 * to render local video player.
	 * 
	 * @return type
	 */
	function load_local_video_assets() {
		$document = JFactory::getDocument();
		$document->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/mediaelement/mediaelement.min.js', 'text/javascript' );
		$document->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/mediaelement/mediaelementplayer.min.js', 'text/javascript' );
		$document->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/mediaelement/mediaelementplayer.min.css', 'text/css' );
	}
}

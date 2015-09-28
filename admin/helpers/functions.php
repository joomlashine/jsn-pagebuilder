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
 * This class includes common functions used for
 * JSN_PageBuilder.
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPagebuilderHelpersFunctions
{
	/**
	 * Generate random string
	 *
	 * @param string $valid_chars
	 * @param int $length
	 *
	 * @return string
	 */
	function get_random_string($valid_chars, $length)
	{
		// start with an empty random string
		$random_string = "";

		// count the number of chars in the valid chars string so we know how many choices we have
		$num_valid_chars = strlen($valid_chars);

		// repeat the steps until we've created a string of the right length
		for ($i = 0; $i < $length; $i++)
		{
			// pick a random number from 1 up to the number of valid chars
			$random_pick = mt_rand(1, $num_valid_chars);

			// take the random character out of the string of valid chars
			// subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
			$random_char = $valid_chars[$random_pick-1];

			// add the randomly-chosen char onto the end of our string so far
			$random_string .= $random_char;
		}

		// return our finished random string
		return $random_string;
	}

	/**
	 * Remove ' and " from string
	 *
	 * @param string $str
	 *
	 * @return string
	 */
	public static function removeQuotes($str) {
		$str = stripslashes($str);
		$result = preg_replace("/[\'\"]+/", "", $str);
		return $result;
	}


	/**
	 * JS to load Fancybox library
	 *
	 * @return void
	 */
	static function loadFancyboxJS() {
		$document = JFactory::getDocument();
		$document->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/jquery-fancybox/jquery.mousewheel-3.0.4.pack.js' );
		$document->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/jquery-fancybox/jquery.fancybox-1.3.4.js' );
		$document->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/jquery-fancybox/jquery.fancybox-1.3.4.css' );
	}

	/**
	 * Get html item
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	public static function getElementItemHtml( $data ) {
		$default = array(
			'element_wrapper' => '',
			'modal_title' => '',
			'element_type' => '',
			'name' => '',
			'shortcode' => '',
			'shortcode_data' => '',
			'content_class' => '',
			'content' => '',
			'action_btn' => '',
			'exclude_gen_shortcode' => '',
			'has_preview' => true,
			'this_' => '',
		);
		$data = array_merge( $default, $data );
		extract( $data );

		$input_html = '';
		$preview_html = '';
		if ( $has_preview ) {
			$preview_html = '<div class="shortcode-preview-container" style="display: none">
					<div class="shortcode-preview-fog"></div>
					<div class="jsn-overlay jsn-bgimage image-loading-24"></div>
				</div>';
		}
		$extra_class  = 'EXTRA_CLASS';
		$custom_style = 'STYLE';
		$other_class  = '';

		if ( ! empty( $this_ ) ) {
			$match = preg_match( "/\[$shortcode" . '\s' . '([^\]])*' . 'disabled="yes"'. '([^\]])*' . '\]/', $shortcode_data );
			if ( $match ) {
				$other_class = 'disabled';
			}
		}
		$buttons = array(
			'edit'   => '<a href="#" onclick="return false;" title="' . JText::_( 'JSN_PAGEBUILDER_HELPER_BUILDER_EDIT_ELEMENT' ) . '" data-shortcode="' . $shortcode . '" class="element-edit"><i class="icon-pencil"></i></a>',
			'clone'  => '<a href="#" onclick="return false;" title="' . JText::_( 'JSN_PAGEBUILDER_HELPER_BUILDER_DUPLICATE_ELEMENT' ) . '" data-shortcode="' . $shortcode . '" class="element-clone"><i class="icon-copy"></i></a>',
		//'deactivate'  => '<a href="#" onclick="return false;" title="' . JText::_( 'Deactivate element' ) . '" data-shortcode="' . $shortcode . '" class="element-deactivate"><i class="icon-cancel"></i></a>',
			'delete' => '<a href="#" onclick="return false;" title="' . JText::_( 'JSN_PAGEBUILDER_HELPER_BUILDER_DELETE_ELEMENT' ) . '" class="element-delete"><i class="icon-trash"></i></a>'
			);
			if ( ! empty ( $other_class ) ) {
				$buttons = array_merge(
				$buttons, array(
					'deactivate'  => '<a href="#" onclick="return false;" title="' . JText::_( 'JSN_PAGEBUILDER_HELPER_BUILDER_REACTIVE_ELEMENT' ) . '" data-shortcode="' . $shortcode . '" class="element-deactivate"><i class="icon-checkmark"></i></a>',
				)
				);
			}

			// Add drag handle
			if($shortcode === 'pb_table_item') {
				$drag_handle_html = "";
			}else{
				$drag_handle_html = "<div class='heading'><a class='element-drag'></a></div>";
			}

		$action_btns = ( empty( $action_btn) ) ? implode( '', $buttons ) : $buttons[$action_btn];

		if(!empty($shortcode_data) && $shortcode == 'pb_pricingtable_item_item'){
			$attrs = JSNPagebuilderHelpersShortcode::shortcodeParseAtts($shortcode_data);
			$matchtype = preg_match( "/\[$shortcode" . '\s' . '([^\]])*' . 'prtbl_item_attr_type="checkbox"'. '([^\]])*' . '\]/', $shortcode_data );
			if($matchtype == 1){
				$check_value = (isset( $attrs['prtbl_item_attr_value'] ) && $attrs['prtbl_item_attr_value'] != '') ? $attrs['prtbl_item_attr_value'] : 'no';

				$option                              = array(
					'id'      => 'prtbl_item_attr_type_' . $attrs['prtbl_item_attr_id'],
					'type'    => 'radio',
					'std'     => $check_value,
					'options' => array( 'yes' => JText::_('JSN_PAGEBUILDER_HELPER_BUILDER_YES'), 'no' => JText::_('JSN_PAGEBUILDER_HELPER_BUILDER_NO') ),
					'parent_class'   => 'no-hover-subitem prtbl_item_attr_type'
				);
				$content = IG_Pb_Helper_Html::radio($option);

				return "<$element_wrapper class='jsn-item jsn-element ui-state-default shortcode-container radio-type ' $element_type data-name='$name' $custom_style>
			<textarea class='hidden $exclude_gen_shortcode shortcode-content' shortcode-name='$shortcode' data-sc-info='shortcode_content' name='shortcode_content[]' >$shortcode_data</textarea>
			$content
			</$element_wrapper>";

			}

		}
		return "<$element_wrapper class='jsn-item jsn-element ui-state-default jsn-iconbar-trigger shortcode-container $extra_class $other_class' $modal_title $element_type data-name='$name' $custom_style>

		<textarea class='hidden $exclude_gen_shortcode shortcode-content' shortcode-name='$shortcode' data-sc-info='shortcode_content' name='shortcode_content[]' >$shortcode_data</textarea>
		$drag_handle_html
        <div class='$content_class'>$content</div>
        $input_html
		<div class='jsn-iconbar'>$action_btns</div>
		$preview_html
		</$element_wrapper>";
	}

	/**
	 * Get custom CSS meta data of post
	 *
	 * @param type $article_id
	 * @param type $meta_key
	 * @param type $action: get/put
	 *
	 * @return type
	 */
	static function custom_css($content_id, $css_key, $action = 'get', $value =''){
		switch($css_key){
			case 'css_file':
				if($action == 'get') {
					$result = self::get_content_css($content_id, '_jsn_pagebuilder_css_file', true);

				}
				else{
					$result = self::update_content_css($content_id, '_jsn_pagebuilder_css_file', $value);
				}
				break;
			case 'css_custom':
				if($action == 'get') {
					$result = self::get_content_css($content_id, '_jsn_pagebuilder_css_custom', true);
				}
				else{
					$result = self::update_content_css($content_id, '_jsn_pagebuilder_css_custom', $value);
				}
				break;
			default:
				break;
		}

		return @$result;
	}

	/**
	 * Get custom data: css files, css code of article
	 *
	 * @global type $content
	 * @param type $content_id
	 * @return type
	 *
	 */
	static function custom_css_data($content_id){
		$array = array('css_files' => '', 'css_custom' => '');
		if(isset ($content_id)){
			$array['css_files'] = self::custom_css($content_id, 'css_files');
			$array['css_custom'] = self::custom_css($content_id, 'css_custom');
		}
		return $array;
	}

	static function get_content_css($content_id, $key ='', $single = false){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->clear();
		$query->select('css_value');
		$query->from('#__jsn_pagebuilder_content_custom_css');
		$query->where('content_id=' .$content_id . ' AND css_key="'. $key. '"' );
		$db->setQuery($query);
		$data = $db->loadResult();
		if(empty($data)){
			return false;
		}
		if(is_array($data)) {
			foreach ($data as $k => $value) {
				$data[$k] = $value;
			}
			return $data;
		}

		return $data;


	}

	static function update_content_css($content_id, $key , $value){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->clear();
		$query->update('#__jsn_pagebuilder_content_custom_css');
		$query->set('css_value='. $value);
		$query->where('content_id='.$content_id . ' AND css_key="'. $key . '"');
		return $db->execute();
	}

	/**
	 * Method to print the script/style tags directly
	 * Should be used in ajax responses that not include head and body
	 */
	public static function print_asset_tag($src, $type = 'css', $media = 'screen', $inline = false, $echo = true ) {
		$tag = '';
		if ($type == 'css') {
			if ( !$inline ) {
				$tag = '<link rel="stylesheet" href="' . $src . '" type="text/css" media="' . $media . '" />';
			}else{
				$tag = '<style type="text/css">' . $src . '</style>';
			}
			
		}else if ($type == 'js'){
			if (!$inline) {
				$tag = ' <script src="' . $src . '" type="text/javascript"></script>';
			}else{
				$tag = '<script type="text/javascript">' . $src . '</script>';
			}
		}
		if ( $echo ){
			echo $tag;
		}else{
			return $tag;
		}
	}

	public static function add_absolute_path_to_image_url ($content)
	{
		$app = JFactory::getApplication();
		if( $app->isSite())
		{
			return $content;
		}
		preg_match_all('#(<img.*?>)#', $content, $results, PREG_SET_ORDER);
		if ( count($results))
		{
			for ($i = 0, $count = count($results); $i <= $count; $i++)
			{
				$imageTag = $results[$i][1];

				preg_match_all('# src="([^"]+)"#', $imageTag, $imageTagResults, PREG_SET_ORDER);
				if (count($imageTagResults))
				{
					for ($j = 0, $count = count($imageTagResults); $j <= $count; $j++)
					{
						$imgTag = $imageTagResults[$j][1];

						preg_match_all('/^(http|https)/', $imgTag, $imgRes, PREG_SET_ORDER);
						if (!count($imgRes))
						{
							$url     = JUri::root() . $imgTag;
							$content = str_replace($imgTag, $url, $content);
						}
					}
				}
			}
		}
		return $content;
	}

}
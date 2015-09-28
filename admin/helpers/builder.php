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
 * Helper for JSN PageBuilder
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPagebuilderHelpersBuilder
{
	/**
	 * Container for storing shortcode tags and their hook to call for the shortcode
	 *
	 * @since 2.5.0
	 *
	 * @name $shortcode_tags
	 * @var array
	 * @global array $shortcode_tags
	 */
    static $shortcode_tags = array();

	/**
	 * Generate column template for visual builder
	 * 
	 * @return string
	 */
	public function generateElementColumnTemplate()
	{
		$html[]	=  '<div id="tmpl-jsn_pagebuilder_layout_column" class="hidden">';
		$html[]	= $this->getColumnStructure();
		$html[]	= '</div>';
		
		$html[]	= '<div id="tmpl-jsn_pagebuilder_item" class="hidden">';
		$html[]	= $this->getItemStructure();
		$html[]	= '</div>';
		
		echo implode("\n", $html);
	}

	/**
	 * Generate row template for visual builder
	 * 
	 * @return string
	 */
	public function generateElementRowTemplate()
	{
		$html[]	= '<div id="tmpl-jsn_pagebuilder_layout_row" class="hidden">';
		$html[]	= $this->getRowStructure();
		$html[]	= '</div>';
		echo implode("\n", $html);
	}
	
	/**
	 * Method to get Builder Column HTML structure
	 * 
	 * @return string column HTML structure
	 */
	public function getColumnStructure()
	{
		$col			= new JSNPBShortcodeColumn();
		
		$shortcode_data = $col->config['shortcode_structure'];
		$shortcode_data = explode("][", $shortcode_data);
		$shortcode_data = $shortcode_data[0] . "]";
		return '<div class="jsn-column-container clearafter shortcode-container ">
			<div class="jsn-column span12">
				<div class="thumbnail clearafter">
					<textarea name="shortcode_content[]" class="hidden">[' . $col->config['shortcode'] . ' span="span12"]</textarea>
					<div class="jsn-column-content item-container" data-column-class="span12" >
						<div class="jsn-handle-drag jsn-horizontal jsn-iconbar-trigger">
							<div class="jsn-iconbar layout"><a class="item-delete column" onclick="return false;" title="Delete column" href="#"><i class="icon-trash"></i></a>
							</div>
						</div>
						<div class="jsn-element-container item-container-content ui-sortable"></div>
						<a class="jsn-add-more pb-more-element" href="javascript:void(0);"><i class="icon-plus"></i>' . JText::_("JSN_PAGEBUILDER_HELPER_BUILDER_ADD_ELEMENT") . '</a>
					</div>
					<textarea class="hidden" name="shortcode_content[]" >[/' . $col->config['shortcode'] . ']</textarea>
				</div>
			</div>
		</div>';
	}

	/**
	 * Method to get Builder Column HTML structure
	 * 
	 * @return string row HTML structure
	 */
	public function getRowStructure()
	{
		$row			= new JSNPBShortcodeRow();
		
		$shortcode_data = $row->config['shortcode_structure'];
		// remove [/it_row][it_column...] from $shortcode_data
		$shortcode_data = explode("][", $shortcode_data);
		$shortcode_data = $shortcode_data[0] . "]";
		return '<div class="jsn-row-container ui-sortable row-fluid shortcode-container">
			<div class="jsn-iconbar left">
				<a href="javascript:void(0);" title="Move Up" class="jsn-move-up disabled"><i class="icon-chevron-up"></i></a>
				<a href="javascript:void(0);" title="Move Down" class="jsn-move-down disabled"><i class=" icon-chevron-down"></i></a>
			</div>
			<textarea name="shortcode_content[]" class="hidden">' . $shortcode_data .'</textarea>	
			<div class="jsn-pb-row-content">
				' . self::getColumnStructure() .'
			</div>
			<div class="jsn-iconbar jsn-vertical">
				<a href="javascript:void(0);" class="add-container"><i class="icon-plus"></i></a>
				<a href="javascript:void(0);" class="element-edit row" data-shortcode="pb_row"><i class="icon-pencil"></i></a>
				<a href="javascript:void(0);" class="item-delete"><i class="icon-trash"></i></a>
			</div>
			<textarea class="hidden" name="shortcode_content[]" >[/' . $row->config['shortcode'] . ']</textarea>
		</div>';
	}
	
	/**
	 * Method to get Builder Item HTML structure
	 * 
	 * @return string
	 */
	public function getItemStructure()
	{
		return '<div class="jsn-item jsn-element ui-state-default jsn-iconbar-trigger" element-id="{element_id}" element-name="{element_name}">
			<input type="hidden" name="element_options" class="element_options" value="{element_options}" />
			<div class="jsn-pb-element">{element_title}</div>
			<div class="jsn-iconbar">
				<a class="element-edit" title="' . JText::_("JSN_PAGEBUILDER_HELPER_BUILDER_EDIT_ELEMENT") . '" onclick="return false;" href="#"><i class="icon-pencil"></i></a>
				<a class="element-delete" title="' . JText::_("JSN_PAGEBUILDER_HELPER_BUILDER_REMOVE_ELEMENT") . '" onclick="return false;" href="#"><i class="icon-trash"></i></a>
			</div>
		</div>';
	}
	
	/**
	 * Render the popup to add elements
	 * 
	 * @return string
	 */
	public function getAddShortcodesPopup()
	{
		global $JSNPbElements;
		$elements = $JSNPbElements->getElements();
		
		//HTML structure of elements
		$elements_html = array();
		//list of criteria to sort: All, Typography, Media...
		$criterias = array("All");
		
		foreach ($elements as $idx => $element) {
			// don't show sub-shortcode
			if (!isset($element->config['name']))
				continue;
			$elements_html[] = $element->element_button($idx + 1);
		
			if (!is_array(@$element->config['cat'])) {
				if (!in_array(@$element->config['cat'], $criterias))
					$criterias[] = @$element->config['cat'];
			}
			else {
				foreach ($element->config['cat'] as $type) {
					if (!in_array($type, $criterias))
						$criterias[] = $type;
				}
			}
		}
		$html[]	= '<div id="pb-add-element" class="pb-add-element add-field-dialog" style="display: none; background-color:#FFFFFF;">
				        <div class="popover-content">
				            <div class="jsn-elementselector">
				                <!-- Elements -->
				                <ul class="jsn-items-list" style="background-color:#FFFFFF; border:none;">';		
		foreach ($elements_html as $idx => $element) {
			$html[]	= $element;
		}
				                  
		$html[]	= '				</ul>
				            </div>
				        </div>
				</div>';
		
		return implode("\n", $html);
	}
	
	/**
	 * Method to transform source text to pagebuilder HTML
	 * 
	 * @return string
	 */
	public static function transFormTextToHTML($content = '', $column = false, $refine = false) {
		if (empty($content))
			return '';
		self::$pattern = self::shortcodes_pattern();
		$content = trim($content);
		if ($refine) {
			$row_start = '\[pb_row';
			$col_start = '\[pb_column';
			$row_end = '\[\/pb_row\]';
			$col_end = '\[\/pb_column\]';
			$content = preg_replace("/$row_start([^($row_start)|($col_start)]*)$col_start/", "[pb_row][pb_column", $content);
			$content = preg_replace("/$col_end([^($row_end)|($col_end)]*)$row_end/", "[/pb_column][/pb_row]", $content);
		}
		
		// wrap alone text
		$text_nodes = preg_split(self::$pattern, $content, -1, PREG_SPLIT_OFFSET_CAPTURE);
		$idx_change = 0;
		$content_flag = "X";
		$append_ = $column ? "[pb_text]{$content_flag}[/pb_text]" : "[pb_row][pb_column][pb_text]{$content_flag}[/pb_text][/pb_column][/pb_row]";
		foreach ($text_nodes as $node) {
			if (strlen(trim($node[0])) != 0 && strlen(trim(strip_tags($node[0]))) != 0) {
				$offset = intval($node[1]) + $idx_change;
				$replace = $node[0];
				$replace_html = $replace;
				
				$content = substr_replace($content, str_replace($content_flag, $replace_html, $append_), $offset, strlen($replace));
				$idx_change += strlen($append_) - strlen($content_flag) - (strlen($replace) - strlen($replace_html));
			}
		}
		
		return preg_replace_callback(self::$pattern, array('self', 'do_shortcode_tag'), $content);
	}
	
	/**
	 * Generate shortcode layout in visual pagebuilder
	 * 
	 * @param string $content
	 * @param string $column
	 * @param string $client
	 * 
	 * @return string
	 */
	public static function generateShortCode($content, $column = false, $client = 'backend')
	{
		$helper				= new JSNPagebuilderHelpersShortcode();
		$content = trim($content);	
		$content_flag = "X";
		
		$shortcodeTags		= $helper::getShortcodeTags();
		$pattern			= JSNPagebuilderHelpersShortcode::getShortcodeRegex();
		
		if ($client === 'backend') {
			$append_            = $column ? "[pb_text]{$content_flag}[/pb_text]" : "[pb_row][pb_column][pb_text]{$content_flag}[/pb_text][/pb_column][/pb_row]";
			$content            = self::wrapContent("#{$pattern}#", $content, $content_flag, $append_);
	   		return preg_replace_callback("#{$pattern}#", array('self', 'doShortcodeTag'), $content );
	   	}else if ($client == 'frontend_assets'){
	   		$content            = self::wrapContent("#{$pattern}#", $content, $content_flag, $content_flag);
	   		return preg_replace_callback("#{$pattern}#", array('self', 'doShortcodeFrontendAssets'), $content );
	   	}else{
	   		$content            = self::wrapContent("#{$pattern}#", $content, $content_flag, $content_flag);
	   		return preg_replace_callback("#{$pattern}#", array('self', 'doShortcodeTagFrontend'), $content );
	   	}
		
	}
	
	/**
	 * Split string by regular expression, then replace nodes by string ([wrapper string]node content[/wrapper string])
	 *
	 * @param type $pattern
	 * @param type $content
	 * @param type $content_flag
	 * @param type $append_
	 * 
	 * @return type string
	 */
	private static function wrapContent($pattern, $content, $content_flag, $append_){
		global $JSNPbElements;
		if (!(trim($content))) {
			$JSNPbElements->setGeneratedStatus(false);
			return '';
		}
		   
		$nodes = preg_split($pattern, $content, -1, PREG_SPLIT_OFFSET_CAPTURE);
			  
	    $idx_change = 0;
	    foreach ($nodes as $node) {
	    	$replace = $node[0];
	    	$empty_str = self::checkEmpty( $content );
	        if (strlen(trim($replace)) != 0 && strlen(trim($empty_str)) != 0) {
	            $offset = intval($node[1]) + $idx_change;
	            $replace_html = $replace;
	            
	            $content = substr_replace($content, str_replace($content_flag, $replace_html, $append_), $offset, strlen($replace));
	            $idx_change += strlen($append_) - strlen($content_flag) - (strlen($replace) - strlen($replace_html));
	    		// Set generated shortcode status to false if idx_change was changed
	            if ($idx_change || !$JSNPbElements->getGeneratedStatus()) {
	    			$JSNPbElements->setGeneratedStatus(false);
	    		}                 
	        }
	    }
	    return $content;
	}
	
	/**
	 * Check string is empty
	 *
	 * @param string $m
	 *
	 * @return string
	 */
	public static function checkEmpty( $content ) {
		$empty_str = preg_replace( '/(<p>)+/', '', $content );
		$empty_str = preg_replace( '/(<\/p>)+/', '', $content );
		$empty_str = str_replace( '&nbsp;', '', $content );
	
		return $empty_str;
	}
	
	/**
	 * Excute the shortcode found in source content
	 * Enter description here ...
	 * 
	 * @param array $m
	 * 
	 * @return string
	 */
	public static function doShortcodeTag($m) {	
	    // allow [[foo]] syntax for escaping a tag
	    if ($m[1] == '[' && $m[6] == ']') {
	        return substr($m[0], 1, -1);
	    }
	
	    $tag = $m[2];
	    $content = isset($m[5]) ? trim($m[5]) : "";
	    return call_user_func(array('self', 'transformShortcodeToPagebuilder'), $tag, $content, $m[0], $m[3]);
	}

	/**
	 * Search content for shortcodes and filter shortcodes through their hooks.
	 *
	 * If there are no shortcode tags defined, then the content will be returned
	 * without any filtering. This might cause issues when plugins are disabled but
	 * the shortcode will still show up in the post or content.
	 *
	 * @since 2.5.0
	 *
	 * @uses $shortcode_tags
	 * @uses get_shortcode_regex() Gets the search pattern for searching shortcodes.
	 *
	 * @param string $content Content to search for shortcodes
	 * @return string Content with shortcodes filtered out.
	 */
	public static function doShortcode($content) {

//		if ( false === strpos( $content, '[' ) ) {
//			return $content;
//		}

		$pattern = JSNPagebuilderHelpersShortcode::getShortcodeRegex();
		return preg_replace_callback( "/$pattern/s", 'doShortcodeTag', $content );
	}

	/**
	 * Excute the shortcode for frontend
	 * 
	 * @param array $m
	 * 
	 * @return string
	 */
	public static function doShortcodeTagFrontend($m) {
		// allow [[foo]] syntax for escaping a tag
		if ($m[1] == '[' && $m[6] == ']') {
			return substr($m[0], 1, -1);
		}
	
		$tag = $m[2];
		$content = isset($m[5]) ? trim($m[5]) : "";		
		return call_user_func(array('self', 'transformShortcodeToPagebuilder'), $tag, $content, $m[0], $m[3], 'frontend');
	}
	
	/**
	 * Method to analyze appeared shortcodes then call 
	 * the frontend assets loading function
	 * 
	 * @param array $m
	 * 
	 */
	public static function doShortcodeFrontendAssets($m) {
		// allow [[foo]] syntax for escaping a tag
		if ($m[1] == '[' && $m[6] == ']') {
			return substr($m[0], 1, -1);
		}
	
		$tag = $m[2];
		$content = isset($m[5]) ? trim($m[5]) : "";		
		return call_user_func(array('self', 'loadShortcodeFrontendAssets'), $tag);
	}
	
	/**
	 * Method to load all appeared Elements' frontend assets
	 * Enter description here ...
	 */
	public function loadShortcodeFrontendAssets($shortcode_name)
	{
		$class	= JSNPagebuilderHelpersShortcode::getShortcodeClass($shortcode_name);
	    if (class_exists($class)) {
	   		global $JSNPbElements;
	        $elements = $JSNPbElements->getElements();
	      
	        $instance = isset($elements[strtolower($class)]) ? $elements[strtolower($class)] : null;
	        if (!is_object($instance)) {
	            $instance = new $class();
	            $instance->frontend_element_assets();
	        }
	        
	    }
	}
	/**
	 * Return html structure of shortcode in Page Builder area
	 * 
	 * @param string $shortcode_name
	 * @param string $content
	 * @param string $shortcode_data
	 * @param array $shortcode_params
	 * 
	 * @return string
	 */
	public static function transformShortcodeToPagebuilder($shortcode_name, $content= '', $shortcode_data = '', $shortcode_params = '', $client = 'backend') {
		$class	= JSNPagebuilderHelpersShortcode::getShortcodeClass($shortcode_name);
	    if (class_exists($class)) {
		    $content = html_entity_decode($content, ENT_COMPAT, 'UTF-8');
	        global $JSNPbElements;
	        $JSNPbElements->setGeneratedStatus(true);
	        $elements = $JSNPbElements->getElements();
	      
	        $instance = isset($elements[strtolower($class)]) ? $elements[strtolower($class)] : null;
	        if (!is_object($instance)) {
	            $instance = new $class();
	        }
	        $el_title = "";
	       
            // extract param of shortcode (now for column)
            if (isset($instance->config['extract_param'])) {
                parse_str(trim($shortcode_params), $output);
                foreach ($instance->config['extract_param'] as $param) {
                    if (isset($output[$param]))
                        $instance->params[$param] = JSNPagebuilderHelpersFunctions::removeQuotes($output[$param]);
                }
            }

            // get content in pagebuilder of shortcode: Element Title must always first option of Content tab
            if (isset($instance->items["content"]) && isset($instance->items["content"][0])) {
                $title = $instance->items["content"][0];
                if (@$title["role"] == "title") {
                    $params = JSNPagebuilderHelpersShortcode::shortcodeParseAtts($shortcode_params);
                    $el_title = !empty($params[$title["id"]]) ? $params[$title["id"]] : "";
                }
            }
            if ($client === 'backend'){
            	$shortcode_view = $instance->element_in_pgbldr($content, $shortcode_data, $el_title);
            }else{	
    			// Render the shortcode frontend HTML
            	$params = JSNPagebuilderHelpersShortcode::shortcodeParseAtts($shortcode_params);
            	$shortcode_view = $instance->element_shortcode($params, $content);
            }
	  
	        return $shortcode_view;
	    }
	}
	
}
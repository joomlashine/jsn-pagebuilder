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
// Load language
$app = JFactory::getApplication();
$lang 			= JFactory::getLanguage();
$lang->load('plg_pagebuilder_defaultelements');
if($app->isSite())
{
	$lang->load('plg_pagebuilder_defaultelements');
}

/**
 * Helper for shortcode elements
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPagebuilderHelpersShortcode
{
	/**
	 * Pattern variable
	 *
	 * @var string
	 */
	static $pattern = "";

	/**
	 * Group shortcodes
	 *
	 * @var array
	 */
	static $group_shortcodes = array("group","group_table", "table");

	/**
	 * Item HTML template
	 *
	 * @var array
	 */
	static $item_html_template = array(
		"icon" => "<i class='STD'></i>",
	);

	/**
	 * Shortcode tag list
	 *
	 * @var array
	 */
	public static $shortcodeTags = null;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	function __construct()
	{
		//$this->shortcodeTags	= self::getShortcodeTags();	
	}

	/**
	 * Function to get shortcode regex, this follows Wordpress
	 * shortcode structure, so the function content would be
	 * the same
	 *
	 * @return array
	 */
	static function getShortcodeRegex()
	{
		$tagnames  = array_keys(self::getShortcodeTags());
		$tagregexp = join('|', array_map('preg_quote', $tagnames));

		return
			'\\['                              // Opening bracket
			. '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
			. "($tagregexp)"                     // 2: Shortcode name
			. '(?![\\w-])'                       // Not followed by word character oftttt        r hyphen
			. '('                                // 3: Unroll the loop: Inside the opening shortcode tag
			. '[^\\]\\/]*'                   // Not a closing bracket or forward slash
			. '(?:'
			. '\\/(?!\\])'               // A forward slash not followed by a closing bracket
			. '[^\\]\\/]*'               // Not a closing bracket or forward slash
			. ')*?'
			. ')'
			. '(?:'
			. '(\\/)'                        // 4: Self closing tag ...
			. '\\]'                          // ... and closing bracket
			. '|'
			. '\\]'                          // Closing bracket
			. '(?:'
			. '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
			. '[^\\[]*+'             // Not an opening bracket
			. '(?:'
			. '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
			. '[^\\[]*+'         // Not an opening bracket
			. ')*+'
			. ')'
			. '\\[\\/\\2\\]'             // Closing shortcode tag
			. ')?'
			. ')'
			. '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
	}

	/**
	 * Generate random id for element
	 *
	 * @param type $length
	 *
	 * @return string
	 */
	static function generateRandomString($length = 6, $is_lower_no_number = false)
	{
		if (!$is_lower_no_number)
		{
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		else
		{
			$characters = 'abcdefghijklmnopqrstuvwxyz';
		}

		$randomString = '';
		for ($i = 0; $i < $length; $i++)
		{
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}

		return $randomString;
	}

	/**
	 * Remove HTML/PHP tag & other tag in ID of an element
	 *
	 * @param type $string
	 *
	 * @return type
	 */
	static function removeTag($string)
	{
		$string = strip_tags($string);
		$string = str_replace('-value-', '', $string);
		$string = str_replace('-type-', '', $string);

		return $string;
	}

	/**
	 * Retrieve all attributes from the shortcodes tag.
	 *
	 * The attributes list has the attribute name as the key and the value of the
	 * attribute as the value in the key/value pair. This allows for easier
	 * retrieval of the attributes, since all attributes have to be known.
	 *
	 * @since 2.5
	 *
	 * @param string $text
	 *
	 * @return array List of attributes and their value.
	 */
	public static function shortcodeParseAtts($text)
	{
		$atts    = array();
		$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
		$text    = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
		if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER))
		{
			foreach ($match as $m)
			{
				if (!empty($m[1]))
					$atts[strtolower($m[1])] = stripcslashes($m[2]);
				elseif (!empty($m[3]))
					$atts[strtolower($m[3])] = stripcslashes($m[4]);
				elseif (!empty($m[5]))
					$atts[strtolower($m[5])] = stripcslashes($m[6]);
				elseif (isset($m[7]) and strlen($m[7]))
					$atts[] = stripcslashes($m[7]);
				elseif (isset($m[8]))
					$atts[] = stripcslashes($m[8]);
			}
		}
		else
		{
			$atts = ltrim($text);
		}

		return $atts;
	}

	/**
	 * Get Shortcode class from shortcode name
	 *
	 * @param type $shortcode_name
	 *
	 * @return type
	 */
	static function getShortcodeClass($shortcodeName)
	{
		$shortcodeName = str_replace(JSNPB_SHORTCODE_PREFIX, strtoupper(JSNPB_SHORTCODE_PREFIX) . "Shortcode_", $shortcodeName);
		$shortcode     = str_replace('_', ' ', $shortcodeName);
		$class         = ucwords($shortcode);
		$class         = 'JSN' . str_replace(' ', '', $class);

		return $class;
	}

	/**
	 * Get params list/ get value of a param
	 *
	 * @param type $arr            : $items array of a shortcode
	 * @param type $paramID        : get std of a option by its ID
	 * @param type $filter_arr     : re-assign value for some params ("pram id" => "new std value")
	 * @param type $assign_content : assign content of  $filter_arr['sc_inner_content'] to the param which has role = 'content'
	 * @param type $assign_title
	 *
	 * @return type
	 */
	public static function generateShortcodeParams(&$arr, $paramID = null, $filter_arr = null, $assign_content = false, $extract_content = false, $assign_title = "")
	{
		$params = array();
		if ($arr)
		{
			foreach ($arr as $tab => &$options)
			{
				foreach ($options as &$option)
				{
					$type          = isset($option['type']) ? $option['type'] : '';
					$option['std'] = !isset($option['std']) ? "" : $option['std'];

					if (isset($option['role']) && $option['role'] == "content")
					{
						if ($assign_content)
						{
							if (!empty($filter_arr) && isset($filter_arr['sc_inner_content']))
								$option['std'] = $filter_arr['sc_inner_content'];
						}
						if ($extract_content)
						{
							$params["sub_sc_extract_content"][$option['id']] = $option['std'];
						}
						else
						{
							// remove option which role = content from Shortcode structure (except option which has another role: title)
							if (!((isset($option["role"]) && $option["role"] == "title") || (isset($option["role_2"]) && $option["role_2"] == "title") || (isset($option["role"]) && $option["role"] == "title_prepend")))
							{
								unset($option);
								continue;
							}
						}
					}
					if ($type != "preview")
					{
						if (!is_array($type))
						{
							if (!in_array($type, self::$group_shortcodes))
							{
								if (empty($filter_arr))
								{
									if (!empty($paramID))
									{
										if ($option['id'] == $paramID)
											return $option['std'];
									}
									else if (isset($option['id']))
										$params[$option['id']] = $option['std'];
								}
								else
								{
									if (isset($option['id']) && array_key_exists($option['id'], $filter_arr))
										$option['std'] = $filter_arr[$option['id']];
									// Process empty title
									if (isset($option['role']) && $option['role'] == 'title' && (!isset($filter_arr[$option['id']])))
									{
										$option['std'] = '';
									}
								}
								if (!empty($assign_title))
								{
									// default std
									if (strpos($option["std"], "PB_INDEX_TRICK") !== false)
									{
										$option['std'] = $params["assign_title"] = $assign_title;
									}
									else if ((isset($option["role"]) && $option["role"] == "title") || (isset($option["role_2"]) && $option["role_2"] == "title"))
									{
										if ($option["role"] == "title")
											$params["assign_title"] = $option["std"];
										else
											$params["assign_title"] = self::sliceContent($option["std"]);
									}
									else if ((isset($option["role"]) && $option["role"] == "title_prepend") && !empty($option["role_type"]) && !empty($option["std"]))
									{
										$params["assign_title"] = str_replace("STD", $option["std"], JSNPagebuilderHelpersShortcode::$item_html_template[$option["role_type"]]) . $params["assign_title"];
									}
								}
							}
							else
							{
								// shortcode in shortcode
								if (empty($filter_arr))
								{
									foreach ($option['sub_items'] as &$sub_items)
									{
										$sub_items['std'] = !isset($sub_items['std']) ? "" : $sub_items['std'];
										if (!empty($paramID))
										{
											if ($sub_items['id'] == $paramID)
												return $sub_items['std'];
										}
										else
											$params["sub_sc_content"][$option['sub_item_type']][] = $sub_items;
									}
								}
								else
								{
									// Assign Content For Sub-Shortcode
									$count_default = count($option['sub_items']);
									$count_real = isset($filter_arr['sub_sc_content'][$option['sub_item_type']]) ? count($filter_arr['sub_sc_content'][$option['sub_item_type']]) : 0;
									if ($count_real > 0)
									{
										if ($count_default < $count_real)
										{
											for ($index = $count_default; $index < $count_real; $index++)
											{
												$option['sub_items'][$index] = array("std" => "");
											}
										}
										elseif ($count_default > $count_real)
										{
											for ($index = $count_real; $index < $count_default; $index++)
											{
												unset($option['sub_items'][$index]);
											}
										}
										array_walk($option['sub_items'], array('self', 'arrWalkSubsc'), $filter_arr['sub_sc_content'][$option['sub_item_type']]);
									}
								}
							}
						}
						else
						{
							if (empty($filter_arr))
							{
								foreach ($option['type'] as &$sub_options)
								{
									$sub_options['std'] = !isset($sub_options['std']) ? "" : $sub_options['std'];
									if (!empty($paramID))
									{
										if ($sub_options['id'] == $paramID)
											return $sub_options['std'];
									}
									else
										$params[$sub_options['id']] = $sub_options['std'];
								}
							}
							else
							{
								array_walk($option['type'], array('self', 'arrWalk'), $filter_arr);
							}
						}

						if (isset($option['extended_ids']))
						{
							foreach ($option['extended_ids'] as $_id)
							{
								$params[$_id] = isset($option[$_id]['std']) ? $option[$_id]['std'] : '';
							}
						}
					}
				}
			}
		}

		return $params;
	}

	/**
	 * Slice to get nth-child first word
	 *
	 * @param type $content
	 *
	 * @return string
	 */
	static function sliceContent($content)
	{
		$content = urldecode($content);
		$arr     = explode(' ', $content);
		$arr     = array_slice($arr, 0, 10);

		return implode(' ', $arr);
	}

	/**
	 * Generate shortcode structure from params array and name
	 *
	 * @param type $shortcode_name
	 * @param type $params
	 * @param type $content
	 *
	 * @return type
	 */
	static function generateShortcodeStructure($shortcode_name, $params, $content = '')
	{
		$shortcode_structure = "[$shortcode_name ";
		$arr                 = array();
		$exclude_params      = array("sub_sc_content", "sub_sc_extract_content");
		foreach ($params as $key => $value)
		{
			if (!in_array($key, $exclude_params) && $key != "")
				$arr[$key] = $value;
		}

		// get content of param which has: role = content
		if (!empty($params['sub_sc_extract_content']))
		{
			foreach ($params['sub_sc_extract_content'] as $paramId => $std)
			{
				unset($arr[$paramId]);
				$content = $std;
			}
		}

		foreach ($arr as $key => $value)
		{
			$shortcode_structure .= "$key=\"$value\" ";
		}
		$shortcode_structure .= "]";
		$shortcode_structure .= $content;
		$shortcode_structure .= "[/$shortcode_name]";

		return $shortcode_structure;
	}

	/**
	 * Extract params from POST string ( such as [param-tag=h3&param-text=Your+heading+text&param-font=custom] )
	 *
	 * @param type $param_str
	 * @param type $str_shortcode
	 *
	 * @return type
	 */
	static function extractParams($param_str, $str_shortcode = '')
	{
		$param_str = stripslashes($param_str);
		$params    = array();
		// get params of shortcode
		preg_match_all('/[A-Za-z0-9_-]+=\"[^"]*\"/u', $param_str, $tmp_params, PREG_PATTERN_ORDER);
		$arr_insert = array();
		foreach ($tmp_params[0] as $param_value)
		{
			$output = array();
			preg_match_all('/([A-Za-z0-9_-]+)=\"([^"]*)\"/u', $param_value, $output, PREG_SET_ORDER);
			foreach ($output as $item)
			{
				if (!in_array($item[1], array('css_suffix')) || !isset ($params[$item[1]]))
				{
//					$params[$item[1]] = urldecode($item[2]);
					$params[$item[1]] = $item[2];
				}
			}
		}
		$pattern = self::getShortcodeRegex();
		preg_match_all("/" . $pattern . "/s", $param_str, $tmp_params, PREG_PATTERN_ORDER);
		$content                    = isset($tmp_params[5][0]) ? trim($tmp_params[5][0]) : "";
		$content                    = preg_replace('/rich_content_param-[a-z_]+=/', "", $content);
		$params['sc_inner_content'] = $content;

		return $params;
	}

	/**
	 * Get all JSN PageBuilder shortcodes
	 *
	 * @return array An array of shortcodes
	 */
	public static function getShortcodeTags()
	{
		// If the shortcodes were fetech before, then use 
		if (self::$shortcodeTags)
		{
			return self::$shortcodeTags;
		}
		// Get shortcodes from cache, if it is existed, then used it
		$cachedShortCodes = self::getShortCodePluginsFromCache();

		if (count($cachedShortCodes))
		{
			if (!self::$shortcodeTags)
			{
				self::$shortcodeTags = $cachedShortCodes;
			}

			return $cachedShortCodes;
		}

		// Get additional shortcodes
		$additionalShortCodes = self::getAdditionalShortCodePlugins();

		// Get default shortcodes
		$defaultShortCodes = self::getDefaultShortcodePlugins();

		// Merge additional shortcodes and default shortcodes
		$shortcodes = array_merge($additionalShortCodes, $defaultShortCodes);

		//Add all shortcode to cache
		self::setShortCodePluginsToCache($shortcodes);

		//add article id to cache
		JSNPagebuilderHelpersArticles::updateArticleUsedPageBuilderToPlugin();


		if (!self::$shortcodeTags)
		{
			self::$shortcodeTags = $shortcodes;
		}

		return $shortcodes;
	}

	public static function getSettingsHtml(){
	
	}
	
	/**
	 * Get all default shortcodes
	 *
	 * @return array An array of shortcodes
	 */
	public static function getDefaultShortcodePlugins()
	{
		$shortcodes = array();

		// Get all default elements into folder elements
		$dir                        = JPATH_ROOT . '/plugins/jsnpagebuilder/defaultelements';
		$shortcodesOfElementsFolder = self::getShortCodePluginsFromFolder($dir);

		// Get all default elements into folder helpers shortcode
		$dir                         = JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/shortcode';
		$shortcodesOfShortCodeFolder = self::getShortCodePluginsFromFolder($dir);

		$shortcodes = array_merge($shortcodesOfElementsFolder, $shortcodesOfShortCodeFolder);

		return $shortcodes;
	}


	/**
	 * Get HTML of Modal Settings Box of Shortcode
	 *
	 * @param array  $settings
	 * @param string $shortcode
	 * @param string $input_params
	 *
	 * @return string
	 */
	static function getShortcodeModalSettings($settings, $shortcode = '', $input_params = null)
	{
        include_once JPATH_ROOT . '/administrator/components/com_pagebuilder/libraries/innotheme/shortcode/html.php';
        $helperHtml = 'IG_Pb_Helper_Html';
        if ($shortcode != '') {
            $element = str_replace('pb_', '', $shortcode);
            if (file_exists(JPATH_ROOT . '/plugins/jsnpagebuilder/defaultelements/' . $element . '/helpers/html.php')) {
                include_once(JPATH_ROOT . '/plugins/jsnpagebuilder/defaultelements/' . $element . '/helpers/html.php');
                $helperHtml = ucfirst($element) . 'HelperHtml';
            }
        }

		$i    = 0;
		$tabs = $contents = $actions = $general_actions = array();
		foreach ($settings as $tab => $options)
		{
			$options = self::ignoreSettings($options);
			if ($tab == "action")
			{
				foreach ($options as $option)
				{
					$actions[] = $helperHtml::$option['type']($option);
				}
			}
			else if ($tab == "generalaction")
			{
				foreach ($options as $option)
				{
					$option['id']      = isset($option['id']) ? ('param-' . $option['id']) : "";
					$general_actions[] = $helperHtml::$option['type']($option);
				}
			}
			else
			{
				$active = ($i++ == 0) ? "active" : "";
				if ($tab != "Notab")
				{
					$data_                = isset($settings[$tab]["settings"]) ? $settings[$tab]["settings"] : array();
					$data_["href"]        = "#$tab";
					$data_["data-toggle"] = "tab";
					$content_             = ucfirst($tab);
					$tabs[]               = "<li class='$active'>" . self::tabSettings("a", $data_, $content_) . "</li>";
				}

				$has_margin = 0;
				$param_html = array();
				foreach ($options as $idx => $option)
				{
					// check if this element has Margin param (1)
					if (isset($option['name']) && $option['name'] == JText::_('Margin') && $option['id'] != 'div_margin')
						$has_margin = 1;
					// if (1), don't use the 'auto extended margin ( top, bottom ) option'
					if ($has_margin && isset($option['id']) && $option['id'] == 'div_margin')
						continue;

					$type         = $option['type'];
					$option['id'] = isset($option['id']) ? ('param-' . $option['id']) : "$idx";
					if (!is_array($type))
					{
						//$content_tab .= $helperHtml::$type($option, $input_params);
						$param_html[$option['id']] = $helperHtml::$type($option, $input_params);
					}
					else
					{
						$output_inner = "";
						foreach ($type as $sub_options)
						{
							$sub_options['id'] = isset($sub_options['id']) ? ('param-' . $sub_options['id']) : "";
							/* for sub option, auto assign bound = 0 {not wrapped by <div class="controls"></div> } */
							$sub_options['bound'] = "0";
							/* for sub option, auto assign 'input-small' class */
							$sub_options['class'] = isset($sub_options['class']) ? ($sub_options['class']) : '';
							$type                 = $sub_options['type'];
							$output_inner .= $helperHtml::$type($sub_options);
						}
						$option = $helperHtml::get_extra_info($option);
						$label  = $helperHtml::get_label($option);
						//$content_tab .= $helperHtml::final_element($option, $output_inner, $label);
						$param_html[$option['id']] = $helperHtml::final_element($option, $output_inner, $label);
					}
				}

				if (!empty ($param_html['param-div_margin']))
				{
					$margin = $param_html['param-div_margin'];
					array_pop($param_html);
					// move "auto extended margin ( top, bottom ) option" to top of output
					$preview    = array_shift($param_html);
					$param_html = array_merge(
						array(
							$preview,
							$margin,
						),
						$param_html
					);
				}

				$param_html  = implode('', $param_html);
				$content_tab = "<div class='tab-pane $active' id='$tab'>";
				$content_tab .= $param_html;
				$content_tab .= '</div>';
				$contents[] = $content_tab;
			}
		}

		return self::settingTabHtml($shortcode, $tabs, $contents, $general_actions, $settings, $actions);
	}

	/**
	 * Generate tab with content, use for generating Modal
	 *
	 * @param type $shortcode
	 * @param type $tabs
	 * @param type $contents
	 * @param type $general_actions
	 * @param type $settings
	 * @param type $actions
	 *
	 * @return string
	 */
	static function settingTabHtml($shortcode, $tabs, $contents, $general_actions, $settings, $actions)
	{
		$output = '<input type="hidden" value="' . $shortcode . '" id="shortcode_name" name="shortcode_name" />';

		/* Tab Content - Styling */

		$output .= '<div class="jsn-tabs">';
		if (count($tabs) > 0)
		{
			$output .= '<ul class="" id="pb_option_tab">';
			$output .= implode('', $tabs);
			$output .= '</ul>';
		}
		/* Tab Content */

		$output .= implode('', $contents);

		$output .= "<div class='jsn-buttonbar pb_action_btn'>";

		/* Tab Content - General actions */
		if (count($general_actions))
		{
			$data_    = $settings["generalaction"]["settings"];
			$content_ = implode("", $general_actions);
			$output .= self::tabSettings("div", $data_, $content_);
		}

		$output .= implode("", $actions);
		$output .= "</div>";
		$output .= '</div>';

		return $output;
	}

	/**
	 * Removes wordpress autop and invalid nesting of p tags, as well as br tags
	 *
	 * @param string $content html content by the wordpress editor
	 *
	 * @return string $content
	 */
	static function removeAutop($content)
	{
		$content = preg_replace('#<p>[\s\t\r\n]*(\[)#', '$1', $content);
		$content = preg_replace('#(\])[\s\t\r\n]*</p>#', '$1', $content);

		$shortcode_tags = array();
		$tagregexp      = join('|', array_map('preg_quote', $shortcode_tags));

		// opening tag
		$content = preg_replace("/(<p>)?\[($tagregexp)(\s[^\]]+)?\](<\/p>|<br \/>)?/", '[$2$3]', $content);

		// closing tag
		$content = preg_replace("/(<p>)?\[\/($tagregexp)](<\/p>|<br \/>)?/", '[/$2]', $content);


		$content = preg_replace('#^<\/p>|^<br\s?\/?>|<p>$|<p>\s*(&nbsp;)?\s*<\/p>#', '', $content);

		return ($content);
	}


	/**
	 * @return int|mixed
	 */
	function countContentUsed()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(id)');
		$query->from('#__content');
		$query->where("introtext LIKE '%pb_row%'");
		$db->setQuery($query);
		$totalContent = $db->loadResult();
		if ($totalContent == '')
		{
			return 0;
		}
		else
			return $totalContent;
	}

	/**
	 * @return int
	 */
	function  pageBuilderStatus()
	{
		$db    = JFactory::getDbo();
		$id    = JRequest::getVar('id');
		$query = $db->getQuery(true);
		$query->select('introtext');
		$query->from('#__content');
		$query->where("id =" . $id . " AND introtext LIKE '%pb_row%'");
		$db->setQuery($query);
		$status = $db->loadResult();
		if ($status != null)
		{
			$pbStatus = 1;
		}
		else
		{
			$pbStatus = 0;
		}

		return $pbStatus;
	}

	/**
	 * @return int
	 */
	function  contentStatus()
	{
		$id = JRequest::getVar('id');
		if ($id == null)
		{
			$status = 0;
		}
		else
		{
			$status = 1;
		}

		return $status;
	}

	/**
	 * Add setting data to a tag
	 *
	 * @param type $tag
	 * @param type $data
	 * @param type $content
	 *
	 * @return type
	 */
	static function tabSettings($tag, $data, $content)
	{
		$tag_data = array();
		if (!empty($data))
		{
			foreach ($data as $key => $value)
			{
				if (!empty($value))
					$tag_data[] = "$key = '$value'";
			}
		}
		$tag_data = implode(" ", $tag_data);

		return "<$tag $tag_data>$content</$tag>";
	}

	/**
	 * Ignore settings key in array
	 *
	 * @param type $options
	 *
	 * @return type
	 */
	static function ignoreSettings($options)
	{
		if (array_key_exists("settings", $options))
		{
			$options = array_slice($options, 1);
		}

		return $options;
	}

	/**
	 * Extract sub-shortcode content of a shortcode
	 *
	 * @param type $content
	 * @param type $recursion
	 *
	 * @return type
	 */
	static function extractSubShortcode($content = '', $recursion = false) {
		preg_match_all('#' . self::getShortcodeRegex() . '#', $content, $out);
		if ($recursion){
			return self::extractSubShortcode($out[5][0]);
		}

		// categorize sub shortcodes content
		$sub_sc_tags = array();

		// sub sortcodes content
		$sub_sc_data = $out[0];

		foreach ( $sub_sc_data as $sc_data ) {

			// get shortcode name
			preg_match( '/\[([^\s\]]+)/', $sc_data, $matches );
			if ( $matches ) {
				$sc_class                 = self::getShortcodeClass( $matches[1] );
				$sub_sc_tags[$sc_class][] = $sc_data;
			}
		}

		return $sub_sc_tags;
	}

	/**
	 * Modify value in array
	 *
	 * @param type $value
	 * @param type $key
	 * @param type $filter_arr
	 *
	 * @return void
	 */
	static function arrWalk(&$value, $key, $filter_arr)
	{
		if (array_key_exists($value['id'], $filter_arr))
			$value['std'] = $filter_arr[$value['id']];
	}

	/**
	 * Modify value in array of sub-shortcode
	 *
	 * @param type $value
	 * @param type $key
	 * @param type $filter_arr
	 *
	 * @return void
	 */
	static function arrWalkSubsc(&$value, $key, $filter_arr)
	{
		$value['std'] = $filter_arr[$key];
	}

	/**
	 * Combine user attributes with known attributes and fill in defaults when needed.
	 *
	 * The pairs should be considered to be all of the attributes which are
	 * supported by the caller and given as a list. The returned attributes will
	 * only contain the attributes in the $pairs list.
	 *
	 * If the $atts list has unsupported attributes, then they will be ignored and
	 * removed from the final returned list.
	 *
	 * @since 2.5
	 *
	 * @param array  $pairs     Entire list of supported attributes and their defaults.
	 * @param array  $atts      User defined attributes in shortcode tag.
	 * @param string $shortcode Optional. The name of the shortcode, provided for context to enable filtering
	 *
	 * @return array Combined and filtered attribute list.
	 */
	public static function shortcodeAtts($pairs, $atts, $shortcode = '')
	{
		$atts = (array) $atts;
		$out  = array();
		foreach ($pairs as $name => $default)
		{
			if (array_key_exists($name, $atts))
				$out[$name] = $atts[$name];
			else
			{
				if (is_string($default) && strpos($default, 'PB_INDEX_TRICK') !== false)
				{
					$out[$name] = '';
				}
				else
				{
					$out[$name] = $default;
				}
			}
		}

		if ($shortcode)
			$out = apply_filters("shortcode_atts_{$shortcode}", $out, $pairs, $atts);

		return $out;
	}

	/**
	 * Retrieve the file type from the file name.
	 *
	 * @return array Values with extension first and mime type.
	 */
	public static function checkFiletype($filename, $mimes = null)
	{
		if (empty($mimes))
			$mimes = self::getAllowedMimeTypes();
		$type = false;
		$ext  = false;

		foreach ($mimes as $ext_preg => $mime_match)
		{
			$ext_preg = '!\.(' . $ext_preg . ')$!i';
			if (preg_match($ext_preg, $filename, $ext_matches))
			{
				$type = $mime_match;
				$ext  = $ext_matches[1];
				break;
			}
		}

		return compact('ext', 'type');
	}

	/**
	 * Retrieve list of allowed mime types and file extensions.
	 *
	 * @param type $user
	 *
	 * @return array Array of mime types keyed by the file extension regex corresponding to those types.
	 */
	public static function getAllowedMimeTypes($user = null)
	{
		$t = self::getMimeTypes();

		unset($t['swf'], $t['exe']);
		//if ( function_exists( 'current_user_can' ) )
		//$unfiltered = $user ? user_can( $user, 'unfiltered_html' ) : current_user_can( 'unfiltered_html' );

		if (empty($unfiltered))
			unset($t['htm|html']);

		return $t;
	}

	/**
	 * Retrieve list of mime types and file extensions.
	 *
	 * @return array Array of mime types keyed by the file extension regex corresponding to those types.
	 */
	public static function getMimeTypes()
	{
		// Accepted MIME types are set here as PCRE unless provided.
		return array(
			// Image formats
			'jpg|jpeg|jpe'                 => 'image/jpeg',
			'gif'                          => 'image/gif',
			'png'                          => 'image/png',
			'bmp'                          => 'image/bmp',
			'tif|tiff'                     => 'image/tiff',
			'ico'                          => 'image/x-icon',
			// Video formats
			'asf|asx'                      => 'video/x-ms-asf',
			'wmv'                          => 'video/x-ms-wmv',
			'wmx'                          => 'video/x-ms-wmx',
			'wm'                           => 'video/x-ms-wm',
			'avi'                          => 'video/avi',
			'divx'                         => 'video/divx',
			'flv'                          => 'video/x-flv',
			'mov|qt'                       => 'video/quicktime',
			'mpeg|mpg|mpe'                 => 'video/mpeg',
			'mp4|m4v'                      => 'video/mp4',
			'ogv'                          => 'video/ogg',
			'webm'                         => 'video/webm',
			'mkv'                          => 'video/x-matroska',
			// Text formats
			'txt|asc|c|cc|h'               => 'text/plain',
			'csv'                          => 'text/csv',
			'tsv'                          => 'text/tab-separated-values',
			'ics'                          => 'text/calendar',
			'rtx'                          => 'text/richtext',
			'css'                          => 'text/css',
			'htm|html'                     => 'text/html',
			// Audio formats
			'mp3|m4a|m4b'                  => 'audio/mpeg',
			'ra|ram'                       => 'audio/x-realaudio',
			'wav'                          => 'audio/wav',
			'ogg|oga'                      => 'audio/ogg',
			'mid|midi'                     => 'audio/midi',
			'wma'                          => 'audio/x-ms-wma',
			'wax'                          => 'audio/x-ms-wax',
			'mka'                          => 'audio/x-matroska',
			// Misc application formats
			'rtf'                          => 'application/rtf',
			'js'                           => 'application/javascript',
			'pdf'                          => 'application/pdf',
			'swf'                          => 'application/x-shockwave-flash',
			'class'                        => 'application/java',
			'tar'                          => 'application/x-tar',
			'zip'                          => 'application/zip',
			'gz|gzip'                      => 'application/x-gzip',
			'rar'                          => 'application/rar',
			'7z'                           => 'application/x-7z-compressed',
			'exe'                          => 'application/x-msdownload',
			// MS Office formats
			'doc'                          => 'application/msword',
			'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
			'wri'                          => 'application/vnd.ms-write',
			'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
			'mdb'                          => 'application/vnd.ms-access',
			'mpp'                          => 'application/vnd.ms-project',
			'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
			'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
			'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
			'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
			'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
			'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
			'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
			'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
			'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
			'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
			'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
			'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
			'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
			'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
			'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
			'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
			'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
			'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
			// OpenOffice formats
			'odt'                          => 'application/vnd.oasis.opendocument.text',
			'odp'                          => 'application/vnd.oasis.opendocument.presentation',
			'ods'                          => 'application/vnd.oasis.opendocument.spreadsheet',
			'odg'                          => 'application/vnd.oasis.opendocument.graphics',
			'odc'                          => 'application/vnd.oasis.opendocument.chart',
			'odb'                          => 'application/vnd.oasis.opendocument.database',
			'odf'                          => 'application/vnd.oasis.opendocument.formula',
			// WordPerfect formats
			'wp|wpd'                       => 'application/wordperfect',
			// iWork formats
			'key'                          => 'application/vnd.apple.keynote',
			'numbers'                      => 'application/vnd.apple.numbers',
			'pages'                        => 'application/vnd.apple.pages',
		);
	}

	/**
	 * Function trim words using limit length
	 *
	 * @param string $text
	 * @param number $num_words
	 * @param string $more
	 *
	 * @return string
	 */
	public static function pbTrimWords($text, $num_words = 55, $more = null)
	{
		if (null === $more)
			$more = JText::_('&hellip;');
		$original_text = $text;
		$words_array   = preg_split("/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY);
		$sep           = ' ';
		if (count($words_array) > $num_words)
		{
			array_pop($words_array);
			$text = implode($sep, $words_array);
			$text = $text . $more;
		}
		else
		{
			$text = implode($sep, $words_array);
		}

		return $text;
	}

	/**
	 * Trim content to : $limit count $limit type(5 words, 20 characters...)
	 *
	 * @param   type $content
	 * @param   type $limit_count
	 * @param   type $limit_type
	 */
	static function pbTrimContent($content, $limit_count, $limit_type)
	{
		if (empty($limit_count))
		{
			return $content;
		}
		$content = trim($content);
		$content = strip_tags($content);
		if ($limit_type == 'words')
		{
			$content = implode(' ', array_slice(explode(' ', $content), 0, intval($limit_count)));
		}
		else
		{
			if ($limit_type == 'characters')
			{
				$content = substr($content, 0, intval($limit_count));
			}
		}

		return $content;
	}

	/**
	 * Get all Additional ShortCode plugins from database
	 *
	 * @return array An array of ShortCode plugins
	 */
	public static function getAdditionalShortCodePlugins()
	{
		$db         = JFactory::getDBO();
		$query      = $db->getQuery(true);
		$shortcodes = array();
		$query->clear();
		$query->select('*');
		$query->from($db->quoteName('#__extensions'));
		$query->where($db->quoteName('type') . ' = ' . $db->quote('plugin') . ' AND ' . $db->quoteName('folder') . ' = ' . $db->quote('pagebuilder') . ' AND ' . $db->quoteName('enabled') . ' = ' . $db->quote('1'));
		$db->setQuery($query);
		$items = $db->loadObjectList();

		if (count($items))
		{
			foreach ($items as $item)
			{
				$shortcodes [JSNPB_SHORTCODE_PREFIX . $item->element] = $item->element;
			}
		}

		return $shortcodes;
	}

	/**
	 * Get shortcode plugins from cache
	 *
	 * @return array An array of ShortCode plugins
	 */
	public static function getShortCodePluginsFromCache()
	{
		$plugin      = JPluginHelper::getPlugin('system', 'pagebuilder');
		$rshortcodes = array();

		if ($plugin)
		{
			$params     = new JRegistry($plugin->params);
			$shortcodes = $params->get('shortcodes', '');
			if ($shortcodes != '')
			{
				$shortcodes = explode('|', $shortcodes);

				if (count($shortcodes))
				{
					foreach ($shortcodes as $shortcode)
					{
						$tmpExplodeShortCode                   = explode(':', $shortcode);
						$rshortcodes [$tmpExplodeShortCode[0]] = $tmpExplodeShortCode[1];
					}
				}
			}
		}

		return $rshortcodes;
	}

	/**
	 * If the cache is empty, then add all shortcodes to cache
	 *
	 * @param array An array of shortcodes need to add to cache
	 *
	 * @return bool true/false
	 */
	public static function setShortCodePluginsToCache($shortcodes)
	{
		if (!count($shortcodes)) return false;

		$tmpShortCodes = array();
		foreach ($shortcodes as $key => $shortcode)
		{
			$tmpShortCodes [] = $key . ':' . $shortcode;
		}

		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->clear();
		$query->select('*');
		$query->from($db->quoteName('#__extensions'));
		$query->where($db->quoteName('type') . ' = ' . $db->quote('plugin') . ' AND ' . $db->quoteName('folder') . ' = ' . $db->quote('system') . ' AND ' . $db->quoteName('element') . ' = ' . $db->quote('pagebuilder'));
		$db->setQuery($query);
		$item = $db->loadObject();

		if ($item != null)
		{
			if ($item->params != '')
			{
				$params                = json_decode($item->params, true);
				$params ['shortcodes'] = implode('|', $tmpShortCodes);
			}
			else
			{
				$params                = array();
				$params ['shortcodes'] = implode('|', $tmpShortCodes);
			}

			$defaults = json_encode($params);

			$query = $db->getQuery(true);
			$query->clear();
			$query->update($db->quoteName('#__extensions'));
			$query->set($db->quoteName('params') . ' = ' . $db->quote($defaults));
			$query->where($db->quoteName('type') . ' = ' . $db->quote('plugin') . ' AND ' . $db->quoteName('folder') . ' = ' . $db->quote('system') . ' AND ' . $db->quoteName('element') . ' = ' . $db->quote('pagebuilder'));
			$db->setQuery($query);

			return $db->execute();
		}

		return false;
	}

	/**
	 *
	 * @param string $dir the folder path need to scan
	 *
	 * @return array An array of shortcodes
	 */
	public static function getShortCodePluginsFromFolder($dir)
	{
		$shortcodes = array();
		$dirs       = array();
		while ($d = glob($dir . '/*', GLOB_ONLYDIR))
		{
			$dir .= '/*';
			$dirs[substr_count($dir, '/*')][] = $d;
		}

		if (count($dirs))
		{
			foreach ($dirs as $level => $dir)
			{
				foreach ($dir[0] as $dr)
				{
					$append = str_repeat("_item", intval($level) - 1);

					foreach (glob($dr . '/*.php') as $file)
					{
						$p = pathinfo($file);
						if ($p['filename'] == 'item')
						{
							$type    = 'element';
							$element = basename($p['dirname']) . '_item';
						}
						else
						{
							$type    = basename($p['dirname']);
							$element = str_replace("-", "_", $p['filename']);
						}

						$shortcodes[JSNPB_SHORTCODE_PREFIX . $element . $append] = $type;
					}
				}
			}
		}

		return $shortcodes;
	}

}
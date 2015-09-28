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

defined('_JEXEC') or die('Restricted access');
if (file_exists(JPATH_ROOT . '/plugins/search/k2/k2.php') && JPluginHelper::getPlugin('search','k2'))
{
	include_once JPATH_ROOT . '/plugins/search/k2/k2.php';
	include_once JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/shortcode.php';

	class plgSearchJsnPagebuilderK2Search extends plgSearchK2
	{
		/**
		 * Search content (articles).
		 * The SQL must return the following fields that are used in a common display
		 * routine: href, title, section, created, text, browsernav.
		 *
		 * @param   string  $text      Target search string.
		 * @param   string  $phrase    Matching option (possible values: exact|any|all).  Default is "any".
		 * @param   string  $ordering  Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
		 * @param   mixed   $areas     An array if the search it to be restricted to areas or null to search all areas.
		 *
		 * @return  array  Search results.
		 *
		 * @since   1.6
		 */
		public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
		{
		
			$shortCodeRegex = JSNPagebuilderHelpersShortcode::getShortcodeRegex();
			$results = @parent::onContentSearch($text, $phrase, $ordering, $areas);
			
			foreach ($results as $_result) {
			
				$_result->text = self::removeShortCode($_result->text, $shortCodeRegex);
			}

			return $results;
		}

		private static function removeShortCode($text, $shortCodeRegex) {
			if (is_array($text)) {
				$removeShortCodeText = "";
				foreach ($text as $_text) {
					preg_match_all("/" . $shortCodeRegex . "/s", $_text, $tmp_params, PREG_PATTERN_ORDER);
					if (count($tmp_params[5]) > 0) {
						$removeShortCodeText .= self::removeShortCode($tmp_params[5], $shortCodeRegex);
					} else {
						$removeShortCodeText .= $_text;
					}
				}
				return $removeShortCodeText;
			} else {
				preg_match_all("/" . $shortCodeRegex . "/s", $text, $tmp_params, PREG_PATTERN_ORDER);
				if (count($tmp_params[5]) > 0) {
					return self::removeShortCode($tmp_params[5], $shortCodeRegex);
				} else {
					return $text;
				}
			}
		}
	}
}
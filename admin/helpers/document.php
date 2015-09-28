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
 * Helper to proceed page document works
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPagebuilderHelpersDocument
{
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $url
	 */
	public static function addStyleSheet($url)
	{
		global $JSNPbElements;
		$JSNPbElements->addStyleSheet($url);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $url
	 */
	public static function addScript($url)
	{
		global $JSNPbElements;
		$JSNPbElements->addScript($url);
	}
}
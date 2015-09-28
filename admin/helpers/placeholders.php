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

// defined array of placeholder php
global $placeholders;
$placeholders = array();

class JSNPagebuilderHelpersPlaceholders{
	static function add_palceholder($string, $placeholder, $expression = ''){
		global $placeholders;
		if(!isset($placeholders[$placeholder]))
			return null;
		if(empty($expression))
			return sprintf($string, $placeholders[$placeholder]);
		else
			return sprintf($string, sprintf($expression, $placeholders[$placeholder]));
	}
}

<?php
/**
 * @version    $Id$
 * @package    JSN_Framework
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if ( ! class_exists('JSNBaseModel'))
{
	// Get Joomla version
	$joomlaVersion = new JVersion;

	if (strpos($joomlaVersion->getShortVersion(), '3.') === 0 AND class_exists('JViewLegacy'))
	{
		return require_once dirname(__FILE__) . '/compat/model_j30.php';
	}

	if (strpos($joomlaVersion->getShortVersion(), '2.5') === 0)
	{
		return require_once dirname(__FILE__) . '/compat/model_j25.php';
	}
}

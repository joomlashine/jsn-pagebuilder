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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Disable notice and warning by default for our products.
// The reason for doing this is if any notice or warning appeared then handling JSON string will fail in our code.

/**
 * Subinstall script for JSN PageBuilder button 
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class PlgEditorsXTDPageBuilderInstallerScript
{
	/**
	 * Implement preflight hook.
	 *
	 * This step will be verify permission for install/update process.
	 *
	 * @param   string  $mode    Install or update?
	 * @param   object  $parent  JInstaller object.
	 *
	 * @return  boolean
	 */
	public function preflight($mode, $parent)
	{
	}

	/**
	 * Enable JSN PageBuilder xtd button plugin.
	 *
	 * @param   string  $route  Route type: install, update or uninstall.
	 * @param   object  $_this  The installer object.
	 *
	 * @return  boolean
	 */
	public function postflight($route, $_this)
	{
		// Get a database connector object
		$db = JFactory::getDbo();
		try
		{
			// Enable plugin by default
			$q = $db->getQuery(true);

			$q->update('#__extensions');
			$q->set(array('enabled = 1'));
			$q->where("element = 'pagebuilder'");
			$q->where("type = 'plugin'", 'AND');
			$q->where("folder = 'editors-xtd'", 'AND');
			$db->setQuery($q);
			$db->execute();
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
}

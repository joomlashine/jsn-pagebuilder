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

// Check if JoomlaShine extension framework is disabled?
$framework = JTable::getInstance('Extension');
$framework->load(
	array(
		'element' => 'jsnframework',
		'type'    => 'plugin',
		'folder'  => 'system'
	)
);

if ($framework->extension_id AND !$framework->enabled)
{
	try
	{
		// Enable our extension framework
		$framework->enabled = 1;
		$framework->store();

		// Execute handler for system event bypassed while our extension framework is disabled
		require_once JPATH_ROOT . '/plugins/system/jsnframework/jsnframework.php';

		$dispatcher = class_exists('JEventDispatcher', false) ? JEventDispatcher::getInstance() : JDispatcher::getInstance();
		$framework  = new PlgSystemJSNFramework($dispatcher);

		$framework->onAfterInitialise();
	}
	catch (Exception $e)
	{
		JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
	}
}

// Get admin component directory
$path = dirname(__FILE__);

// Load constant definition file
require_once "{$path}/defines.pagebuilder.php";

// Import JoomlaShine base MVC classes
require_once "{$path}/libraries/joomlashine/base/model.php";
require_once "{$path}/libraries/joomlashine/base/view.php";
require_once "{$path}/libraries/joomlashine/base/controller.php";

// Setup necessary include paths
JTable::addIncludePath("{$path}/tables");

JSNBaseModel::addIncludePath("{$path}/models");
JSNBaseModel::addTablePath("{$path}/tables");

JHtml::addIncludePath("{$path}/elements/html");

// Load default language
!class_exists('JSNUtilsLanguage') OR !method_exists('JSNUtilsLanguage', 'loadDefault') OR JSNUtilsLanguage::loadDefault();

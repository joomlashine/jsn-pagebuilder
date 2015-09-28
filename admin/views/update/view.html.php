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

jimport('joomla.application.component.view');

/**
 * View Update JSN Pagebuider
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
include_once (JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/extensions.php');
class JSNPagebuilderViewUpdate extends JSNUpdateView
{
	public function display ($tpl = null)
	{
		// Get config parameters
		$config = JSNConfigHelper::get();

		// Set the toolbar
		JToolBarHelper::title(JText::_('JSN_PAGEBUILDER_BUILDER_UPDATE_TITLE'));

		// Add assets
 		JSNHtmlAsset::addStyle(JSN_URL_ASSETS.'/joomlashine/css/jsn-gui.css');

		
		$redirAfterFinish = 'index.php?option=com_pagebuilder&view=about';
		$this->assign('redirAfterFinish', $redirAfterFinish);
		// Display the template
		parent::display($tpl);
	}

	
}
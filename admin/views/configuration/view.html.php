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
 * Configuration view of JSN PageBuilder component
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
include_once(JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/extensions.php');

class JSNPagebuilderViewConfiguration extends JSNConfigView
{
	/**
	 * Display method
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return    void
	 */
	function display($tpl = null)
	{

		// Get config parameters
		$config = JSNConfigHelper::get();

		// Set the toolbar
		JToolbarHelper::title(JText::_('JSN_PAGEBUILDER_BUILDER_SETTING_TITLE'));

		// Assign variables for rendering
		$this->assignRef('msgs', $msgs);
		JSNHtmlAsset::addStyle(JSNPB_PLG_SYSTEM_ASSETS_URL . '/css/pagebuilder.css');
		JSNHtmlAsset::addStyle(JSNPB_ADMIN_URL . '/assets/css/configurations.css');
		JSNHtmlAsset::addScript(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery/jquery.min.js');
		JSNHtmlAsset::addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/jquery-ui/js/jquery-ui-1.10.3.custom.js');
		JSNHtmlAsset::addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/jquery-livequery/jquery.livequery.min.js');
		JSNHtmlAsset::addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . 'js/joomlashine.noconflict.js');
		JSNHtmlAsset::addScript(JSNPB_ASSETS_URL . 'js/config.js');
		// Display the template
		parent::display($tpl);
	}
}

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

// Import Joomla view library
jimport('joomla.application.component.view');

/**
 * View class of JSN Installer library.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNInstallerView extends JSNBaseView
{
	/**
	 * Constructor
	 *
	 * @param   array  $config  A named configuration array for object construction.
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// Load language manually
		$lang = JFactory::getLanguage();
		$lang->load('jsn_installer', JPATH_COMPONENT_ADMINISTRATOR . '/libraries/joomlashine/installer');

		// Get input object
		$this->input = JFactory::getApplication()->input;

		// Get model object
		$this->model = $this->getModel();

		// Get document object
		$this->doc = JFactory::getDocument();
	}

	/**
	 * Method for display page.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Exception object.
	 */
	public function display($tpl = null)
	{
		// Get component name
		$component = substr($this->input->getCmd('option'), 4);

		// Get extension info
		$this->xml = simplexml_load_file(JPATH_COMPONENT_ADMINISTRATOR . '/' . $component . '.xml');

		// Hide main menu
		$this->input->set('hidemainmenu', true);

		// Set toolbar title
		JToolbarHelper::title(JText::sprintf('JSN_EXTFW_INSTALLER_TITLE', JText::_((string) $this->xml->name)));

		// Get dependency
		$this->dependencies = $this->xml->xpath('subinstall/extension');

		// Check dependency
		$this->missingDependency = true;

		if (($result = $this->model->check($this->dependencies)) == -1)
		{
			// No missing dependency found
			$this->missingDependency = false;
		}
		elseif (is_array($result))
		{
			$this->errors = $result;
		}
		elseif ($result === true)
		{
			$this->authentication = true;
		}

		// Load assets
		$JVersion = new JVersion;

		if (version_compare($JVersion->RELEASE, '3.0', '<'))
		{
			// Load Mootools compatible script
			JHtml::_('behavior.mootools');
			$this->script = JUri::root(true) . '/administrator/components/com_' . $component . '/assets/js/installer/mootools_compat.js';

			// Load bootstrap
			$this->doc->addStyleSheet(JUri::root(true) . '/administrator/components/com_' . $component . '/assets/css/bootstrap.min.css');
		}
		elseif (strpos($JVersion->RELEASE, '3.') === 0)
		{
			// Use jQuery compatible script
			JHtml::_('behavior.framework');
			$this->script = JUri::root(true) . '/administrator/components/com_' . $component . '/assets/js/installer/jquery_compat.js';
		}
		else
		{
			jexit(JText::_('JSN_EXTFW_INSTALLER_OBSOLETE_JOOMLA_VERSION'));
		}

		$this->doc->addStyleSheet(JUri::root(true) . '/administrator/components/com_' . $component . '/assets/css/installer.css');

		// Set layout path
		$this->addTemplatePath(dirname(__FILE__) . '/tmpl');

		// Display the template
		parent::display($tpl);
	}
}

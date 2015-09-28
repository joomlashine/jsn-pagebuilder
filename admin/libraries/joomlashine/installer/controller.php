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

// Disable notice and warning by default for our products.
// The reason for doing this is if any notice or warning appeared then handling JSON string will fail in our code.
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

// Import necessary Joomla libraries
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.file');
jimport('joomla.installer.installer');

/**
 * Controller class of JSN Installer library.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNInstallerController extends JSNBaseController
{
	/**
	 * Constructor
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
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
		$this->model = $this->getModel($this->input->getCmd('controller', $this->input->getCmd('view')));
	}

	/**
	 * Download dependency package.
	 *
	 * @return  void
	 */
	public function download()
	{
		if ($this->input->getVar('identified_name'))
		{
			try
			{
				$result = $this->model->download();
			}
			catch (Exception $e)
			{
				$result = $e->getMessage();
			}

			jexit($result);
		}

		jexit(JText::_('JSN_EXTFW_INSTALLER_MISSING_INDENTIFIED_NAME'));
	}

	/**
	 * Install dependency package.
	 *
	 * @return  void
	 */
	public function install()
	{
		if ($this->input->getString('package') OR isset($_FILES['package']))
		{
			try
			{
				$result = $this->model->install();
			}
			catch (Exception $e)
			{
				$result = $e->getMessage();
			}

			jexit($result);
		}

		jexit(JText::_('JSN_EXTFW_INSTALLER_MISSING_PACKAGE_NAME'));
	}

	/**
	 * Finalize dependency installation.
	 *
	 * @return  void
	 */
	public function finalize()
	{
		try
		{
			$result = $this->model->finalize();
		}
		catch (Exception $e)
		{
			// Do nothing as this is a background process
		}

		$this->setRedirect('index.php?option=' . $this->input->getCmd('option'));
	}
}

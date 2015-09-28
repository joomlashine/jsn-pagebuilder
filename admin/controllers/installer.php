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

// Import JSN Installer library
require_once JPATH_COMPONENT_ADMINISTRATOR . '/libraries/joomlashine/installer/controller.php';

/**
 * Installer controller
 *
 * @package     JSN_PageBuilder
 * @since       1.0.0
 */
class JSNPageBuilderControllerInstaller extends JSNInstallerController
{
	public function installPbExtension()
	{
		$this->model 	= $this->getModel('installer');
		$canDo			= JHelperContent::getActions('com_installer');
		
		if ($canDo->get('core.manage'))
		{
			try
			{
			$extResult = $this->model->download();
			$this->input->set('package', $extResult);

			// Set extension parameters
			$_GET['package'] 	= $extResult;
			$_GET['type']   	= 'plugin';
			$_GET['folder'] 	= 'jsnpagebuilder';
			$_GET['publish'] 	= 1;
			$_GET['client'] 	= 'site';
			$_GET['name']   	= str_ireplace(JSN_PAGEBUILDER_EXT_IDENTIFIED_NAME_PREFIX, '', $_GET['identified_name']);

			if($this->model->install($extResult))
			{
				require_once JSNPB_ADMIN_PATH . '/helpers/extensions.php';
				// Enable extension support
				$identifiedName = str_ireplace(JSN_PAGEBUILDER_EXT_IDENTIFIED_NAME_PREFIX, '', $_GET['identified_name']);
				try
				{
					JSNPagebuilderHelpersExtensions::enableExt($identifiedName);
				}
				catch (Exception $ex)
				{
					exit('notenabled');
				}
			}
		}
		catch (Exception $ex)
		{
			exit($ex->getMessage());
		}
		exit('success');
	}

	}
}

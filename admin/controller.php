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

// Import Joomla controller library.
jimport('joomla.application.component.controller');

/**
 * General controller of JSN Framework Sample component
 *
 * Controller (Controllers are where you put all the actual code.) Provides basic
 * functionality, such as rendering views (aka displaying templates).
 *
 * @package    Joomla.Platform
 * @subpackage com_pagebuilder
 * @since      11.1
 */
/**
 * General controller of JSN Pagebuilder component
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPagebuilderController extends JSNBaseController
{

    /**
     * Typical view method for MVC based architecture
     *
     * This function is provide as a default implementation, in most cases
     * you will need to override it in your own controllers.
     *
     * @param   boolean  $cachable   If true, the view output will be cached
     * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return  JController  A JController object to support chaining.
     *
     * @since   11.1
	 */
	function display($cachable = false, $urlparams = false)
	{		
		// Get input object
		$input = JFactory::getApplication()->input;

		// Set default view if not set
		$input->set('view', $input->getCmd('view', 'about'));
		$vName	= $input->getCmd('view', 'about');
		//Add submenus
		if (JRequest::getVar('tmpl') != 'component'
				&&  JRequest::getVar('tmpl') != 'ajax'
				&& !JRequest::getVar('ajax')
				&& $vName  != 'installer' )
		{
			

			JSNMenuHelper::addEntry(
					'pb-submenu-manager',
					'COM_PAGEBUILDER_PAGE_MANAGER',
					'index.php?option=' . JRequest::getCmd('option', 'com_pagebuilder') . '&view=manager',
					$vName == 'manager',
					'administrator/components/com_pagebuilder/assets/images/icons-16/icon-monitor.png',
					'pb-submenu'
			);

            JSNMenuHelper::addEntry(
                'pb-submenu-configuration',
                'COM_PAGEBUILDER_CONFIGURATION',
                'index.php?option=' . JRequest::getCmd('option', 'com_pagebuilder') . '&view=configuration',
                $vName == 'configuration',
                'administrator/components/com_pagebuilder/assets/images/icons-16/icon-cog.png',
                'pb-submenu'
            );

			JSNMenuHelper::addEntry(
					'pb-submenu-about',
					'COM_PAGEBUILDER_ABOUT',
					'index.php?option=' . JRequest::getCmd('option', 'com_pagebuilder') . '&view=about',
					$vName == 'about',
					'administrator/components/com_pagebuilder/assets/images/icons-16/icon-star.png',
					'pb-submenu'
			);

			// Render menu
			JSNMenuHelper::render('pb-submenu');

		}

		// Call parent method
		parent::display($cachable, $urlparams);
	}
	
	/**
	 * Method for hiding a message
	 *
	 * @return	void
	 */
	function hideMsg()
	{
		jexit(JSNUtilsMessage::hideMessage(JFactory::getApplication()->input->getInt('msgId')));
	}

}

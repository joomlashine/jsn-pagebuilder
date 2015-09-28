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

// Import Joomla view library
jimport('joomla.application.component.view');

/**
 * About view of JSN PageBuilder component
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
include_once (JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/extensions.php');
class JSNPagebuilderViewAbout extends JViewLegacy
{

	/**
	 * Display method
	 *
	 * @return	void
	 */
	function display($tpl = null)
	{
		// Get config parameters
		$config = JSNConfigHelper::get();
		$this->_document = JFactory::getDocument();		

		JToolBarHelper::title(JText::_('JSN_PAGEBUILDER_ABOUT'), 'about');
		// Assign variables for rendering
		$this->assignRef('msgs', $msgs);			
		// Display the template
		parent::display($tpl);
	}
	
}

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

//No direct access
defined('_JEXEC') or die('Restricted access');

//Import Joomla view library
jimport('joomla.application.component.view');
class JSNPagebuilderViewUpgrade extends JSNUpgradeView{

    public function display($tpl = null){

        // Set the toolbar
        JToolbarHelper::title(JText::_('JSN_SAMPLE_UPGRADE_PRODUCT'));

        JSNHtmlAsset::addStyle(JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-gui.css');

        // Add assets
        $this->_document = JFactory::getDocument();

        $this->addToolbar();

        // Display the template
        parent::display($tpl);

    }

    /**
     * Add the page title and toolbar
     *
     * @return void
    **/

    protected function addToolbar(){
        jimport('joomla.html.toolbar');
        JToolbarHelper::title(JText::_('COM_PAGEBUILDER'). ': '. JText::_('JSN_PAGEBUILDER_BUILDER_UPGRADE_TITLE'));
    }

}
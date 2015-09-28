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
include_once (JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/extensions.php');
class JSNPagebuilderViewManager extends JViewLegacy{

    public function display($tpl = null){
        $this->articles = $this->get('Items');
        $this->modules = $this->get('ItemModules');
        $this->k2 = $this->get('ItemK2');
        $this->easyBlog = $this->get('ItemEasyblog');
        // Set the toolbar
        JToolbarHelper::title(JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_TITLE'));

        // Assign variables for rendering
        $this->assignRef('msgs', $msgs);
        JSNHtmlAsset::addStyle(JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-pages.css');
        JSNHtmlAsset::addStyle(JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-general.css');
        JSNHtmlAsset::addStyle(JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/pagebuilder.css');
        // Display the template
        parent::display($tpl);
    }

    private function getExtSupports(){
        $supports = array();
        $extensionSupport = JSNPagebuilderHelpersPagebuilder::getExtensionSupports();
        foreach($extensionSupport as $supports){
            if($supports == 'adminmenus')
                continue;

            $supports = array(
                'value' => $supports,
                'text'  => JText::_('JSN_PAGEBUILDER_SUPPORT_'. str_ireplace(JSN_PAGEBUILDER_3RD_EXTENSION_STRING. '-', '', strtoupper($supports)), true)
            );
        }

        return $supports;
    }

    private function getConfiguration($supports){
        $configuration = array(
            'articles' => array(
                'language'  => 'com_content',
                'modelfile' => 'components/com_content/models/articles.php',

            )
        );

        $supportedExtConfigs = JSNPagebuilderHelpersExtensions::getExtConfigurations(str_ireplace(JSN_PAGEBUILDER_3RD_EXTENSION_STRING . '-', '', $supports));
        if(count($supportedExtConfigs)){
            foreach($supportedExtConfigs as $key=>$config){
                $configuration[JSN_PAGEBUILDER_3RD_EXTENSION_STRING . '-'.strtolower($key)] = $config;
            }
        }
    }
}
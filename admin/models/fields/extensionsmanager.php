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

defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');

class JFormFieldExtensionsManager extends JFormField{

    protected $type = 'ExtensionsManager';

    protected function getLabel(){
        return '';
    }

    protected function getInput(){
        require_once JSNPB_ADMIN_PATH . '/helpers/extensions.php';
        require_once JSNPB_ADMIN_PATH . '/helpers/pagebuilder.php';
        $supportedExtList = JSNPagebuilderHelpersExtensions::getSupportedExtList();
        JSNHtmlAsset::addStyle(JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/pagebuilder.css');
        JSNHtmlAsset::addScript('http://code.jquery.com/jquery-2.1.0.min.js');
        JSNHtmlAsset::addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . '/3rd-party/jquery-ui/js/jquery-ui-1.10.3.custom.js');
        JSNHtmlAsset::addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . '/3rd-party/jquery-livequery/jquery.livequery.min.js');
        JSNHtmlAsset::addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . 'js/joomlashine.noconflict.js');
        JSNHtmlAsset::addScript(JURI::root(true). '/plugins/system/jsnframework/assets/3rd-party/jquery-tipsy/jquery.tipsy.js');
        JSNHtmlAsset::addScript(JSNPB_ASSETS_URL . 'js/configuration/extmanager.js');
        $customScript = "var baseUrl = '".JURI::root() . "';";
        JSNHtmlAsset::addInlineScript($customScript);

        if(count($supportedExtList)){
            $installedComponents = JSNPagebuilderHelpersPagebuilder::getInstalledComponents();


            foreach($supportedExtList as $key=>$ext){
                $_shortName = str_ireplace("com_", "", $key);
                $ext->name = $_shortName;
                $ext->comInstalled = in_array($key, $installedComponents) ? true : false;
                $extStatus = JSNPagebuilderHelpersExtensions::checkInstalledPlugin($_shortName);
                if($extStatus['isInstalled']){
                    $ext->plgInstalled = true;
                    $ext->enabled = $extStatus['isEnabled'];
                }else{
                    $ext->plgInstalled = false;
                    $ext->enabled = false;
                }
                $list[$_shortName] = $ext;

            }
        }
        $html[] = '<div class="jsn-supported-ext-list">
                    <input type="hidden" id="label-disable" value="' . JText::_('JSN_PAGEBUILDER_EXTPAGE_DISABLE') . '">
					<input type="hidden" id="label-enable" value="' . JText::_('JSN_PAGEBUILDER_EXTPAGE_ENABLE') . '">
					<input type="hidden" id="label-install" value="' . JText::_('JSN_PAGEBUILDER_EXTPAGE_INSTALL') . '">
                ';
        $html[] = '<ul class="thumbnails">';
        foreach($list as $ext){
            $_className = '';
            $_alt = '';
            $posibleAct = '';
            $_id = JSN_PAGEBUILDER_EXT_IDENTIFIED_NAME_PREFIX . $ext->name;
            if(!$ext->plgInstalled){
                if(!$ext->comInstalled) {
                    $_className = 'item-locked';
                    $_alt = JText::_('JSN_PAGEBUILDER_EXTPAGE_COM_NOT_INSTALLED_EXPLAIN');
                    $posibleAct = '<a class="btn btn-primary disabled"' . JText::_('JSN_PAGEBUILDER_EXTPAGE_COM_NOT_INSTALLED_EXPLAIN') . '">' . JText::_('JSN_PAGEBUILDER_EXTPAGE_INSTALL') . '</a>';
                }else{
                    $_className = 'item-locked';
                    $_alt = JText::_('JSN_PAGEBUILDER_EXTPAGE_COM_NOT_INSTALLED_EXPLAIN');
                    $posibleAct = '<a class="btn btn-primary" id="' . $_id . '" act="install" href="#">' . JText::_('JSN_PAGEBUILDER_EXTPAGE_INSTALL') . '</a>';
                }
            }else if($ext->plgInstalled){
                if(!$ext->enabled){
                    $_className = 'item-installed item-disabled';
                    $_alt = JText::_('JSN_PAGEBUILDER_EXTPAGE_CLICK_TO_ENABLED');
                    $posibleAct = '<a class="btn btn-primary" id="' . $_id  . '" act="enable" href="#">' . JText::_('JSN_PAGEBUILDER_EXTPAGE_ENABLE') . '</a>';
                }else{
                    $_className = 'item-installed item-enabled';
                    $_alt = JText::_('JSN_PAGEBUILDER_EXTPAGE_CLICK_TO_DISABLE');
                    $posibleAct = '<a class="btn btn-primary" id="' . $_id  . '" act="disable" href="#">' . JText::_('JSN_PAGEBUILDER_EXTPAGE_DISABLE') . '</a>';
                }
            }else{
                $_className	= 'item-notinstalled';
                $_alt		= JText::_('JSN_PAGEBUILDER_EXTPAGE_CLICK_TO_INSTALL');
                $posibleAct = '<a class="btn btn-primary" id="' . $_id . '" act="install" href="#">' . JText::_('JSN_PAGEBUILDER_EXTPAGE_INSTALL') . '</a>';
            }

            $html[] = '<li class="span4">
                            <div class="thumbnail">
                                <img src="'. $ext->thumbnail .'" alt="">
                                <div class="caption">
                                    <h2>'. ucfirst($ext->name).' </h2>
                                    <p>'. $posibleAct.'</p>
                                </div>
                            </div>
                        </li>';
        }
        $html[] = '</ul>';
        $html[] = '</div>';
        return implode($html);
    }
}
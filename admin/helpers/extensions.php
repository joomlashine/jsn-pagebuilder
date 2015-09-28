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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class JSNPagebuilderHelpersExtensions{
    public static function getSupportedExtList(){
        $supportedList = json_decode(JSNPB_SUPPORTED_EXT_LIST);
        return $supportedList;
    }

    public static function getExtConfigurations($extName = ''){
        $installedComponents = JSNPagebuilderHelpersPagebuilder::getInstalledComponents();
        $configurations = array();
        if(!$extName){
            $supportedList = JPluginHelper::getPlugin('jsnpagebuilder');


            if(count($supportedList)){
                foreach($supportedList as $key=>$ext){
                    if(in_array('com_' . $ext->name, $installedComponents)){
                        $config = self::executeExtMethod($ext->name, 'addConfiguration');
                        if(count($config)){
                            $configurations[$ext->name] = $config;
                        }
                    }
                }
            }
        }else{
            if(in_array('com_' . $extName, $installedComponents)){
                $config = self::executeExtMethod($extName, 'addConfiguration');
                if(count($config)){
                    $configurations[$extName] = $config;
                }
            }
        }
        return $configurations;
    }

    public static function executeExtMethod($extName, $method, $params = null){
        JPluginHelper::importPlugin('jsnpagebuilder', $extName);
        $plgClassName = 'plgJsnpagebuilder' . ucfirst($extName);
        $result = call_user_func(array($plgClassName, $method), $params);
        return $result;
    }

    public static function checkInstalledPlugin($name, $type= 'jsnpagebuilder'){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('enabled');
        $query->from('#__extensions');
        $query->where("type = 'plugin' AND folder=". $db->quote($type) . " AND element= ". $db->quote($name));
        $db->setQuery($query);
        $extResult = $db->loadResult();
        $_isInstalled = false;
        $_isEnabled = false;

        if(!isset($extResult)){
            return array('isInstalled'=>false, 'isEnabled'=>false);
        }else{
            $result['isInstalled'] = true;
            $result['isEnabled'] = $extResult ? true : false;

            return $result;
        }
    }

    public static function enableExt($name, $type = 'jsnpagebuilder', $isEnabled = true){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear();
        $query->update('#__extensions');
        $query->set('enabled=' . (int)$isEnabled);
        $query->where("type = 'plugin' AND folder=" . $db->quote($type) . " AND element=" . $db->quote($name));
        $db->setQuery($query);
        return $db->query();
    }

    public static function getPbExtensions(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('*');
        $query->from('#__extensions');
        $query->where("type = 'plugin' AND folder = 'jsnpagebuilder'");
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    public static function getDependentExtensions(){
        $indentifiedNames = array();
        $indentifiedNames[JSNUtilsText::getConstant('IDENTIFIED_NAME', 'framework')] = JSNUtilsText::getConstant('VERSION', 'framework');
        $indentifiedNames[JSN_PAGEBUILDER_IDENTIFIED_NAME] = JSN_PAGEBUILDER_VERSION;

        $exts = self::getPbExtensions();
       
        if(count($exts)){
            foreach($exts as $ext){
                $manifest = json_decode($ext->manifest_cache);
                $indentifiedNames[JSN_PAGEBUILDER_EXT_IDENTIFIED_NAME_PREFIX . $ext->element] = $manifest->version;
            }
        }
        return $indentifiedNames;
    }
}
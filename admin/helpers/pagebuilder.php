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
 * Helper for JSN Pagebuilder
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPagebuilderHelpersPagebuilder
{

    private static  $_installedComponents;
    private static $import;

    public static function getExtensionSupports($includeExtPlgs = true ){
        $config = JSNConfigHelper::get('com_pagebuilder');
        $extSupportOrder = json_decode($config->extension_support);
        if (is_dir(JPATH_ADMINISTRATOR . '/components/com_advancedmodules'))
        {
            $com_advancedmodules = 'com_advancedmodules';
        }else{
            $com_advancedmodules = '';
        }
        $extensions = array();
        if(count($extSupportOrder) > 0 ){
            $configExtensions = json_decode($config->extension_support);
            $configExtensionsOrder = explode(",", $config->extension_support_order);
            if(count($configExtensionsOrder) > 0){
                foreach($configExtensionsOrder as $_exts){
                    if(in_array($_exts, $configExtensions)){
                        array_push($extensions, $_exts);

                    }
                }
            }else{
                $extensions = $configExtensions;

            }
        }else{

            $extensions = array(
                'com_content',
                'com_modules',
                $com_advancedmodules,
            );

            if ($includeExtPlgs)
            {	
	            include_once (JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/extensions.php');
	            $installedComponents = self::getInstalledComponents();
	
	            $supportList = JSNPagebuilderHelpersExtensions::getSupportedExtList();
	
	            if(count($supportList)){
	                foreach($supportList as $extName=>$value){
	                    if(in_array($extName, $installedComponents)){
	                        $extensions[] = $value->extension;
	                    }
	                }
	            }
            }
        }
        return $extensions;
    }

    public static function getInstalledComponents(){
        if(self::$_installedComponents == null){
            $document = JFactory::getDbo();
            $document->setQuery("SELECT element FROM #__extensions WHERE type='component'");
            self::$_installedComponents = $document->loadColumn();
        }

        return self::$_installedComponents;
    }

    public static function localimport($package, $client = 'admin')
    {
        $package = strtolower($package);

        $segments = explode('.', $package);
        $path = ($client == 'site') ? JPATH_ROOT.'/components/com_pagebuilder/' : JPATH_ROOT.'/administrator/components/com_pagebuilder/';
        $path.= implode('/', $segments);

        if (is_file($path . '.php')) {
            require_once $path . '.php';
        }

        if (is_dir($path)) {
            $lastSegment = end($segments);
            $filePath = $path . '/' . $lastSegment . '.php';

            if (is_file($filePath)) {
                require_once $filePath;
            }
        }
    }

    public static function import($args, $client = 'admin'){
        $args = JString::strtolower($args);
        $filePath = implode(DS, explode('.', $args)).'.php';
        switch($client){
            case 'site':
                if(!isset(self::$import[$filePath.$client]) && file_exists(JPATH_ROOT.DS.$filePath)){
                    self::$import[$filePath.$client] = $args;
                    require_once(JPATH_ROOT.DS.$filePath);
                }
                break;
            case 'admin':
                if(!isset(self::$import[$filePath.$client]) && file_exists(JPATH_ROOT.DS.$filePath)){
                    self::$import[$filePath.$client] = $args;
                    require_once(JPATH_ROOT.DS.$filePath);
                }
                break;
        }
    }

    public static function checkComponentEnabled($option)
    {
        $version = new JVersion();
        $isInstalled = version_compare($version->getShortVersion(), "3.4", "<") ? in_array($option, self::getInstalledComponents()) : JComponentHelper::isInstalled($option);
        return $isInstalled && JComponentHelper::isEnabled($option) === '1';
    }
}
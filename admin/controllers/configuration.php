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

/**
 * Configuration controller.
 *
 * @package  JSN_Pagebuilder
 * @since    1.0.0
 */
class JSNPagebuilderControllerConfiguration extends JSNConfigController
{
	public function changeExtStatus()
	{
		include_once (JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/extensions.php');
		
		$status	= (int) JRequest::getInt('status');
		$idName	= str_ireplace(JSN_PAGEBUILDER_EXT_IDENTIFIED_NAME_PREFIX, "", JRequest::getVar('identified_name')) ;
		$config = JSNConfigHelper::get('pagebuilder');

		if (isset($config->extension_support) && (string) $config->extension_support != '')
		{
			$extension_support = @json_decode($config->extension_support);
		}
		else
		{
			$extension_support = array();
		}
        // When disable status, update to configuration page
        if($status == 0){
            $extensionName = 'com_'. $idName;
            if(($valueRemove = array_search($extensionName, $extension_support)) !== false){
                unset($extension_support[$valueRemove]);
            }
            $db = JFactory::getDbo();
            $query = $db->getQuery();
            $query->clear();
            $query->update('#__jsn_pagebuilder_config');
            $query->set('value =' . $db->quote(json_encode($extension_support)));
            $query->where('name =' . $db->quote('extension_support'));
            $db->setQuery($query);
            $db->query();
        }
        if (JSNPagebuilderHelpersExtensions::enableExt($idName, 'jsnpagebuilder', $status))
        {
            exit('success');
        }
	}	
}

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


// No direct access 
defined('_JEXEC') or die('Restricted access');
class JSNPagebuilderControllerSelectmodule extends ModulesControllerModule{
	public function setModuleType(){
		//Initialize variable
		$app = JFactory::getApplication();
		//get the result of the parent method. If an error, just return it
		$result = parent::add();
		if(JError::isError($result)){
			return $result;
		}
		//look for extension id
		$extensionId =JRequest::getInt('eid');
		$position = JRequest::getVar('position');
		if(empty($extensionId)){
			$this->setRedirect(JRoute::_('index.php?option=com_pagebuilder&view=selectmodule&tmpl=component&position='.$position,false));
		}else {
			$this->setRedirect(JRoute::_('index.php?option=com_pagebuilder&view=module&layout=edit&tmpl=component&id=0&position=' .$position, false));

		}
		$app->setUserState('com_pagebuilder.add.module.extension_id', $extensionId);
	}
	
}
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

class PlgJsnPagebuilderDefaultElementsInstallerScript
{

	/**
	 * Enable plugin after installation completed.
	 *
	 * @param   string  $route      Route type: install, update or uninstall.
	 * @param   object  $installer  The installer object.
	 *
	 * @return  boolean
	 */
    public function postflight($route, $installer)
    {
        $db = JFactory::getDbo();
        try 
        {
            $query = $db->getQuery(true);
            $query->update('#__extensions');
            $query->set(array('enabled = 1', 'protected = 1'));
            $query->where("element = 'defaultelemets'");
            $query->where("type = 'plugin'", 'AND');
            $query->where("folder = 'jsnpagebuilder'", 'AND');
            $db->setQuery($query);
            $db->execute();
        } 
        catch (Exception $e) 
        {
            throw $e;
        }
    }
}
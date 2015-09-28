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
 * Helper class for JSN Image Show element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbImageShowHelper
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public static function getAllShowList()
    {
        $allShowList = array();
        if (JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_easyslider")) {
            $db = JFactory::getDBO();
            $query = 'SELECT a.showlist_title AS title, a.showlist_id AS id'
                . ' FROM #__imageshow_showlist AS a'
                . ' WHERE a.published = 1'
                . ' ORDER BY id';
            $db->setQuery($query);
            $allShowList = $db->loadAssocList('id', 'title');
        }
        if (count($allShowList) == 0) {
            $allShowList[0] = JText::_('PLG_EDITOR_DO_NOT_HAVE_ANY_SHOWLIST');
        }

        return $allShowList;
    }

    /**
     *
     */
    public static function getAllShowCase()
    {
        $allShowCase = array();
        if (JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_easyslider")) {
            $db = JFactory::getDBO();
            $query = 'SELECT a.showcase_title AS title, a.showcase_id AS id'
                . ' FROM #__imageshow_showcase AS a'
                . ' WHERE a.published = 1'
                . ' ORDER BY id';
            $db->setQuery($query);
            $allShowCase = $db->loadAssocList('id', 'title');
        }
        if (count($allShowCase) == 0) {
            $allShowCase[0] = JText::_('PLG_EDITOR_DO_NOT_HAVE_ANY_SHOWCASE');
        }

        return $allShowCase;
    }
}
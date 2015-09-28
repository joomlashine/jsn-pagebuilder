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
if (!JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_k2")) {
    return;
}
include_once JPATH_ROOT . '/administrator/components/com_k2/models/categories.php';

/**
 * Model class for categories
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbK2CategoriesModel extends K2ModelCategories
{
    function getData()
    {
        $mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');
        $db = JFactory::getDBO();
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
        $search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
        $search = JString::strtolower($search);
        $filter_order = $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', 'c.ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', '', 'word');
        $filter_trash = $mainframe->getUserStateFromRequest($option.$view.'filter_trash', 'filter_trash', 0, 'int');
        $language = $mainframe->getUserStateFromRequest($option.$view.'language', 'language', '', 'string');
        $filter_category = $mainframe->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', 0, 'int');

        // Publish categories only
        $filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', 1, 'int');

        $query = "SELECT c.*, g.name AS groupname, exfg.name as extra_fields_group FROM #__k2_categories as c LEFT JOIN #__groups AS g ON g.id = c.access LEFT JOIN #__k2_extra_fields_groups AS exfg ON exfg.id = c.extraFieldsGroup WHERE c.id>0";

        if (!$filter_trash)
        {
            $query .= " AND c.trash=0";
        }

        if ($search)
        {
            $escaped = K2_JVERSION == '15' ? $db->getEscaped($search, true) : $db->escape($search, true);
            $query .= " AND LOWER( c.name ) LIKE ".$db->Quote('%'.$escaped.'%', false);
        }

        if ($filter_state > -1)
        {
            $query .= " AND c.published={$filter_state}";
        }
        if ($language)
        {
            $query .= " AND (c.language = ".$db->Quote($language)." OR c.language = '*')";
        }

        if ($filter_category)
        {
            K2Model::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'models');
            $ItemlistModel = K2Model::getInstance('Itemlist', 'K2Model');
            $tree = $ItemlistModel->getCategoryTree($filter_category);
            $query .= " AND c.id IN (".implode(',', $tree).")";
        }

        $query .= " ORDER BY {$filter_order} {$filter_order_Dir}";

        if (K2_JVERSION != '15')
        {
            $query = JString::str_ireplace('#__groups', '#__viewlevels', $query);
            $query = JString::str_ireplace('g.name AS groupname', 'g.title AS groupname', $query);
        }

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if (K2_JVERSION != '15')
        {
            foreach ($rows as $row)
            {
                $row->parent_id = $row->parent;
                $row->title = $row->name;
            }
        }
        $categories = array();

        if ($search)
        {
            foreach ($rows as $row)
            {
                $row->treename = $row->name;
                $categories[] = $row;
            }

        }
        else
        {
            if ($filter_category)
            {
                $db->setQuery('SELECT parent FROM #__k2_categories WHERE id = '.$filter_category);
                $root = $db->loadResult();
            }
            else
            {
                $root = 0;
            }
            $categories = $this->indentRows($rows, $root);
        }
        if (isset($categories))
        {
            $total = count($categories);
        }
        else
        {
            $total = 0;
        }
        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);
        $categories = @array_slice($categories, $pageNav->limitstart, $pageNav->limit);
        foreach ($categories as $category)
        {
            $category->parameters = class_exists('JParameter') ? new JParameter($category->params) : new JRegistry($category->params);
            if ($category->parameters->get('inheritFrom'))
            {
                $db->setQuery("SELECT name FROM #__k2_categories WHERE id = ".(int)$category->parameters->get('inheritFrom'));
                $category->inheritFrom = $db->loadResult();
            }
            else
            {
                $category->inheritFrom = '';
            }
        }
        return $categories;
    }
}
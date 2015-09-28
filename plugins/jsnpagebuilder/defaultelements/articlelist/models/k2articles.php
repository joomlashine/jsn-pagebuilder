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
include_once JPATH_ROOT . '/administrator/components/com_k2/models/items.php';

/**
 * Model class for K2 items
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbK2ArticlesModel extends K2ModelItems
{
    public function jsnGetData($attributes)
    {
        $mainframe = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_k2');
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');
        $db = JFactory::getDBO();

        $filter_trash = $mainframe->getUserStateFromRequest($option.$view.'filter_trash', 'filter_trash', 0, 'int');
        $filter_featured = $mainframe->getUserStateFromRequest($option.$view.'filter_featured', 'filter_featured', -1, 'int');
        $search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
        $search = JString::strtolower($search);
        $tag = $mainframe->getUserStateFromRequest($option.$view.'tag', 'tag', 0, 'int');
        $language = $mainframe->getUserStateFromRequest($option.$view.'language', 'language', '', 'string');

        // JSNPagebuilder Custom
        // Published items only
        $filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', 1, 'int');
        $limit = isset($attributes['articlelist_amount']) ? $attributes['articlelist_amount'] : 5;
        $limitStart = 0;
        $filter_order = $attributes['articlelist_sort_by'] != JSNPbArticleListHelper::PB_ARTICLE_SORT_BY_FP_ORDERING ? $attributes['articlelist_sort_by'] : JSNPbArticleListHelper::PB_ARTICLE_SORT_BY_ID;
        $filter_order_Dir = $attributes['articlelist_sort_order'];
        $filter_category = isset($attributes['articlelist_filter_k2_categories']) ? explode(",", $attributes['articlelist_filter_k2_categories']) : false;
        $filter_author = isset($attributes['articlelist_filter_k2_authors']) ? explode(",", $attributes['articlelist_filter_k2_authors']) : false;
        $dateFiltering = $attributes['articlelist_filter_date'];
        $dateField = $attributes['articlelist_date_field'];
        $startDateRange = isset($attributes['articlelist_range_date_start']) ? JFactory::getDate($attributes['articlelist_range_date_start'])->toSql() : '';
        $endDateRange = isset($attributes['articlelist_range_date_end']) ? JFactory::getDate($attributes['articlelist_range_date_end'])->toSql() : '';
        $relativeDate = isset($attributes['articlelist_relative_date']) ? (int) $attributes['articlelist_relative_date'] : 30;

        $query = "SELECT a.*, g.name AS groupname, c.name AS category, v.name AS author, w.name as moderator, u.name AS editor FROM #__k2_items as a";

        $query .= " LEFT JOIN #__groups AS g ON g.id = a.access"." LEFT JOIN #__users AS u ON u.id = a.checked_out"." LEFT JOIN #__users AS v ON v.id = a.created_by"." LEFT JOIN #__users AS w ON w.id = a.modified_by";

        if ($params->get('showTagFilter') && $tag)
        {
            $query .= " LEFT JOIN #__k2_tags_xref AS tags_xref ON tags_xref.itemID = a.id";
        }

        $query .= " JOIN #__k2_categories AS c ON c.id = a.catid AND c.published = 1";

        $query .= " WHERE a.trash={$filter_trash}";

        if ($search)
        {
            $escaped = K2_JVERSION == '15' ? $db->getEscaped($search, true) : $db->escape($search, true);
            $quoted = $db->Quote('%'.$escaped.'%', false);

            if ($params->get('adminSearch') == 'full')
            {
                $query .= " AND ( LOWER(a.title) LIKE ".$quoted." OR LOWER(a.introtext) LIKE ".$quoted." OR LOWER(a.`fulltext`) LIKE ".$quoted." OR LOWER(a.extra_fields_search) LIKE ".$quoted." OR LOWER(a.image_caption) LIKE ".$quoted." OR LOWER(a.image_credits) LIKE ".$quoted." OR LOWER(a.video_caption) LIKE ".$quoted." OR LOWER(a.video_credits) LIKE ".$quoted." OR LOWER(a.metadesc) LIKE ".$quoted." OR LOWER(a.metakey) LIKE ".$quoted.") ";
            }
            else
            {
                $query .= " AND LOWER(a.title) LIKE ".$quoted;
            }
        }

        if ($filter_state > -1)
        {
            $query .= " AND a.published={$filter_state}";
        }

        if ($filter_featured > -1)
        {
            $query .= " AND a.featured={$filter_featured}";
        }

        if ($filter_category)
        {
            if ($params->get('showChildCatItems')) {
                K2Model::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'models');
                $itemListModel = K2Model::getInstance('Itemlist', 'K2Model');
                $categories = $itemListModel->getCategoryTree($filter_category);
                $sql = @implode(',', $categories);
                $query .= " AND a.catid IN ({$sql})";
            } else {
                $query .= " AND a.catid={$filter_category}";
            }
        } else {
            $query .= " AND a.catid = '' ";
        }

        if ($filter_author) {
            $query .= " AND a.created_by IN (" . implode(',', $filter_author) . ")";
        }

        if ($params->get('showTagFilter') && $tag)
        {
            $query .= " AND tags_xref.tagID = {$tag}";
        }

        if ($language)
        {
            $query .= " AND (a.language = ".$db->Quote($language)." OR a.language = '*')";
        }

        $nullDate	= $db->quote($db->getNullDate());
        $nowDate	= $db->quote(JFactory::getDate()->toSql());
        switch ($dateFiltering)
        {
            case 'range':
                $startDateRange = $startDateRange ? $db->quote($startDateRange) : $nullDate;
                $endDateRange = $endDateRange ? $db->quote($endDateRange) : $nowDate;
                $query .= ' AND (' . $dateField . ' >= DATE(' . $startDateRange . ') AND DATE(' . $dateField .
                    ') <= DATE(' . $endDateRange . '))';
                break;
            case 'relative':
                $query .= ' AND ' . $dateField . ' >= DATE(DATE_SUB(' . $nowDate . ', INTERVAL ' .
                    $relativeDate . ' DAY))';
                break;
            case 'off':
            default:
                break;
        }

        $query .= " ORDER BY {$filter_order} {$filter_order_Dir} ";

        if (K2_JVERSION != '15')
        {
            $query = JString::str_ireplace('#__groups', '#__viewlevels', $query);
            $query = JString::str_ireplace('g.name', 'g.title', $query);
        }
        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('k2');
        $dispatcher->trigger('onK2BeforeSetQuery', array(&$query));
        $db->setQuery($query, $limitStart, $limit);

        return $db->loadAssocList('id');
    }
}
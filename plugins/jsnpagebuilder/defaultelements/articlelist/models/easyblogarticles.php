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
if (!JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_easyblog")) {
    return;
}

if (JSNPbArticleListHelper::checkOldVersionEasyBlog()) {
    include_once JPATH_ROOT . '/components/com_easyblog/models/blogs.php';
} else {
    include_once JPATH_ROOT . '/administrator/components/com_easyblog/models/blogs.php';
}

include_once JPATH_ROOT . '/plugins/jsnpagebuilder/defaultelements/articlelist/models/easyblogcategories.php';

/**
 * Model class for Easy blog posts
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbEasyblogArticlesModel extends EasyBlogModelBlogs {
    public $attributes = array();

    public function jsnGetData($attributes) {
        $this->attributes = $attributes;
        // Get the db
        $db = JFactory::getDBO();
        $query = $this->_buildDataQuery();

        // JSNPagebuilder custom
        $limit = isset($attributes['articlelist_amount']) ? $attributes['articlelist_amount'] : 5;
        $limitStart = 0;
        $db->setQuery($query, $limitStart, $limit);

        return $db->loadAssocList('id');
    }

    /**
     * Builds the query for the blogs listing
     *
     * @since    5.0
     * @access    public
     * @return string
     * @internal param $string
     */
    public function _buildDataQuery() {
        // Get the WHERE and ORDER BY clauses for the query
        $where = $this->_buildDataQueryWhere();

        // Get the db
        $db = JFactory::getDBO();

        $query = 'SELECT DISTINCT a.*, c.title as category_title, au.name as author';
        $query .= ' FROM ' . $db->quoteName('#__easyblog_post') . ' AS a ';

        if (!$this->attributes['is_old_version']) {
            // Always join with the category table
            $query .= ' LEFT JOIN ' . $db->quoteName('#__easyblog_post_category') . ' AS cat';
            $query .= ' ON a.' . $db->quoteName('id') . ' = cat.' . $db->quoteName('post_id');
        }

        $query .= ' LEFT JOIN ' . $db->quoteName('#__easyblog_featured') . ' AS f ';
        $query .= ' ON a.`id` = f.`content_id` AND f.`type`="post"';

        $query .= ' LEFT JOIN ' . $db->quoteName('#__easyblog_category') . ' AS c ';
        $query .= ' ON a.`category_id` = c.`id` ';

        $query .= ' LEFT JOIN ' . $db->quoteName('#__users') . ' AS au ';
        $query .= ' ON a.`created_by` = au.`id` ';

        $query .= $where;

        // JSNPagebuilder custom
        $ordering = $this->attributes['articlelist_sort_by'] != JSNPbArticleListHelper::PB_ARTICLE_SORT_BY_FP_ORDERING ? $this->attributes['articlelist_sort_by'] : 'a.id';
        $direction = isset($this->attributes['articlelist_sort_order']) ? $this->attributes['articlelist_sort_order'] : 'DESC';

        $query .= ' ORDER BY ' . $ordering . ' ' . $direction . ', ordering';

        return $query;
    }

    /**
     * Builds the where statement
     *
     * @since    5.0
     * @access    public
     * @return array
     * @internal param $string
     */
    public function _buildDataQueryWhere() {
        $db = JFactory::getDBO();

        // JSNPagebuilder custom
        $filter_category = isset($this->attributes['articlelist_filter_easy_categories']) ? explode(",", $this->attributes['articlelist_filter_easy_categories']) : '';
        $filter_blogger = isset($this->attributes['articlelist_filter_easy_authors']) ? explode(",", $this->attributes['articlelist_filter_easy_authors']) : '';
        $dateFiltering = isset($this->attributes['articlelist_filter_date']) ? $this->attributes['articlelist_filter_date'] : 'off';
        $dateField = isset($this->attributes['articlelist_date_field']) ? $this->attributes['articlelist_date_field'] : 'a.created';

        $where = array();
        // Published posts only
        $where[] = 'a.`published` = ' . $db->quote(1);
        $categoryAlias = 'c.`id`';
        if (!$this->attributes['is_old_version']) {
            $where[] = 'a.' . $db->qn('state') . '=' . $db->quote(0);
            $categoryAlias = 'cat.`category_id`';
        }

        if ($filter_category != '') {
            $categories = $filter_category;
            $jsnEasyCategoriesModel = new JSNPbEasyblogCategoriesModel();
            foreach ($filter_category as $_category_id) {
                $children = $jsnEasyCategoriesModel->getChildCategories($_category_id, true);
                foreach ($children as $_child) {
                    $categories[] = $_child->id;
                }
            }
            $where[] = ' ' . $categoryAlias . ' IN (' . implode(',', $categories) . ')';
        } else {
            $where[] = ' ' . $categoryAlias . ' = ""';
        }

        if ($filter_blogger != '') {
            $where[] = ' a.`created_by` IN (' . implode(',', $filter_blogger) . ')';
        }

        $nullDate	= $db->quote($db->getNullDate());
        $nowDate	= $db->quote(JFactory::getDate()->toSql());
        switch ($dateFiltering)
        {
            case 'range':
                $startDateRange = isset($this->attributes['articlelist_range_date_start']) ? $db->quote(JFactory::getDate($this->attributes['articlelist_range_date_start'])->toSql()) : $nullDate;
                $endDateRange = isset($this->attributes['articlelist_range_date_end']) ? $db->quote(JFactory::getDate($this->attributes['articlelist_range_date_end'])->toSql()) : $nowDate;
                $where[] = ' (' . $dateField . ' >= DATE(' . $startDateRange . ') AND DATE(' . $dateField .
                    ') <= DATE(' . $endDateRange . ')) ';
                break;
            case 'relative':
                $relativeDate = isset($this->attributes['articlelist_relative_date']) ? (int) $this->attributes['articlelist_relative_date'] : '30';
                $where[] = ' ' . $dateField . ' >= DATE(DATE_SUB(' . $nowDate . ', INTERVAL ' .
                    $relativeDate . ' DAY)) ';
                break;
            case 'off':
            default:
                break;
        }

        $where = count($where) ? ' WHERE ' . implode(' AND ', $where) : '';

        return $where;
    }
}
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
    include_once JPATH_ROOT . '/components/com_easyblog/models/categories.php';
} else {
    include_once JPATH_ROOT . '/administrator/components/com_easyblog/models/categories.php';
}
include_once JPATH_ROOT . '/administrator/components/com_easyblog/tables/category.php';

/**
 * Model class for categories
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbEasyblogCategoriesModel extends EasyBlogModelCategories
{
    function _buildQueryWhere()
    {
        $mainframe			= JFactory::getApplication();
        $db					= JFactory::getDBO();

        $filter_state 		= $mainframe->getUserStateFromRequest( 'com_easyblog.categories.filter_state', 'filter_state', 'P', 'word' );
        $search 			= $mainframe->getUserStateFromRequest( 'com_easyblog.categories.search', 'search', '', 'string' );
        $search 			= $db->escape( trim(JString::strtolower( $search ) ) );

        $where = array();

        if ( $filter_state )
        {
            if ( $filter_state == 'P' )
            {
                $where[] = $db->quoteName( 'published' ) . '=' . $db->Quote( '1' );
            }
            else if ($filter_state == 'U' )
            {
                $where[] = $db->quoteName( 'published' ) . '=' . $db->Quote( '0' );
            }
        }

        if ($search)
        {
            $where[] = ' LOWER( title ) LIKE \'%' . $search . '%\' ';
        }

        $where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

        return $where;
    }

    function getChildCategories($parentId , $isPublishedOnly = false, $isFrontendWrite = false , $exclusion = array() )
    {
        $db 	= JFactory::getDBO();

        $category = new EasyBlogTableCategory($db);
        $category->load($parentId);

        $query = 'select a.`id`, a.`title`, a.`alias`, a.`private`, a.`parent_id`';
        $query .= ' from `#__easyblog_category` as a';
        $query .= ' WHERE a.`lft` > ' . $category->lft;
        $query .= ' AND a.`lft` < ' . $category->rgt;

        if ($isPublishedOnly) {
            $query	.=  ' and a.`published` = ' . $db->Quote('1');
        }

        $acl = $isFrontendWrite ? '2' : '1';

        $catAccess = self::genCatAccessSQL( 'a.`private`', 'a.`id`', $acl);
        $query .= ' AND (' . $catAccess . ')';

        // @task: Process exclusion list.
        if (!empty($exclusion)) {
            $excludeQuery	= 'AND a.`id` NOT IN (';
            for ($i = 0 ; $i < count( $exclusion ); $i++) {
                $id		= $exclusion[ $i ];

                $excludeQuery	.= $db->Quote( $id );

                if (next($exclusion) !== false) {
                    $excludeQuery	.= ',';
                }
            }

            $excludeQuery	.= ')';
            $query			.= $excludeQuery;
        }

        // echo $query;
        // echo '<br /><br />';

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
    }

    public static function genCatAccessSQL($column, $columnId, $acl = '1')
    {
        $my = JFactory::getUser();

        if ($my->id == 0) {
            $gid	= JAccess::getGroupsByUser(0, false);
        } else {
            $gid	= JAccess::getGroupsByUser($my->id, false);
        }

        $gids = '';
        if( count( $gid ) > 0 )
        {
            foreach( $gid as $id)
            {
                $gids   .= ( empty($gids) ) ? $id : ',' . $id;
            }
        }

        $sql = "(";
        $sql .= " ( $column = 0) OR";
        $sql .= " ( $column = 1 and 1 > 0) OR";
        $sql .= " ( $column = 2 and (select count(1) from `#__easyblog_category_acl` where `category_id` = $columnId and `acl_id` = $acl and `content_id` in ($gids)) > 0)";
        $sql .= ")";

        return $sql;
    }
}
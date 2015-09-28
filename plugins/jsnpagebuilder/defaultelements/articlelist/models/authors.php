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
 * Model class for authors
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbAuthorsModel extends JModelList
{
    /**
     * @param string $source
     *
     * @return array
     */
    public function getAuthorsHaveArticle($source = JSNPbArticleListHelper::PB_ARTICLE_SOURCE_JOOMLA)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id', 'name', 'username')));
        $query->from($db->quoteName('#__users'));

        switch ($source) {
            case JSNPbArticleListHelper::PB_ARTICLE_SOURCE_K2 :
                $table = '#__k2_items';
                break;
            case JSNPbArticleListHelper::PB_ARTICLE_SOURCE_EASY :
                $table = '#__easyblog_post';
                break;
            default:
                $table = '#__content';
                break;
        }

        $subQuery = $db->getQuery(true)
            ->select('distinct(created_by)')
            ->from($db->quoteName($table));
        $query->where($db->quoteName('id') . ' IN (' . $subQuery . ')');

        $query->order('name ASC');

        $db->setQuery($query);

        return $db->loadAssocList('id');
    }
}
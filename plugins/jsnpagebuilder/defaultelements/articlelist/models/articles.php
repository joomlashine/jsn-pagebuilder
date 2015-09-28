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
 * Model class for categories
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbArticlesModel extends JModelList
{
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @since   1.6
     * @see     JController
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'title', 'a.title',
                'alias', 'a.alias',
                'checked_out', 'a.checked_out',
                'checked_out_time', 'a.checked_out_time',
                'catid', 'a.catid', 'category_title',
                'state', 'a.state',
                'access', 'a.access', 'access_level',
                'created', 'a.created',
                'created_by', 'a.created_by',
                'created_by_alias', 'a.created_by_alias',
                'ordering', 'a.ordering',
                'featured', 'a.featured',
                'language', 'a.language',
                'hits', 'a.hits',
                'publish_up', 'a.publish_up',
                'publish_down', 'a.publish_down',
                'published', 'a.published',
                'author_id',
                'category_id',
                'level',
                'tag'
            );

            if (JLanguageAssociations::isEnabled())
            {
                $config['filter_fields'][] = 'association';
            }
        }

        parent::__construct($config);
    }

    /**
     * Get the master query for retrieving a list of articles subject to the model state.
     *
     * @return  JDatabaseQuery
     *
     * @since   1.6
     */
    protected function getListQuery()
    {
        // Get the current user for authorisation checks
        $user	= JFactory::getUser();

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Arrayhelper
        $version = new JVersion();
        $before34 = version_compare($version->getShortVersion(), "3.4", "<") ? true : false;
        $jArrayHelper = $before34 ? "JArrayHelper" : "Joomla\Utilities\ArrayHelper";
        $jString = $before34 ? "JString" : "\Joomla\String\String";

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.id, a.title, a.alias, a.introtext, a.fulltext, ' .
                'a.checked_out, a.checked_out_time, ' .
                'a.catid, a.created, a.created_by, a.created_by_alias, ' .
                // Use created if modified is 0
                'CASE WHEN a.modified = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified, ' .
                'a.modified_by, uam.name as modified_by_name,' .
                // Use created if publish_up is 0
                'CASE WHEN a.publish_up = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.publish_up END as publish_up,' .
                'a.publish_down, a.images, a.urls, a.attribs, a.metadata, a.metakey, a.metadesc, a.access, ' .
                'a.hits, a.xreference, a.featured, a.language, ' . ' ' . $query->length('a.fulltext') . ' AS readmore'
            )
        );

        // Process an Archived Article layout
        if ($this->getState('filter.published') == 2)
        {
            // If badcats is not null, this means that the article is inside an archived category
            // In this case, the state is set to 2 to indicate Archived (even if the article state is Published)
            $query->select($this->getState('list.select', 'CASE WHEN badcats.id is null THEN a.state ELSE 2 END AS state'));
        }
        else
        {
            /*
            Process non-archived layout
            If badcats is not null, this means that the article is inside an unpublished category
            In this case, the state is set to 0 to indicate Unpublished (even if the article state is Published)
            */
            $query->select($this->getState('list.select', 'CASE WHEN badcats.id is not null THEN 0 ELSE a.state END AS state'));
        }

        $query->from('#__content AS a');

        // Join over the frontpage articles.
        if ($this->context != 'com_content.featured')
        {
            $query->join('LEFT', '#__content_frontpage AS fp ON fp.content_id = a.id');
        }

        // Join over the categories.
        $query->select('c.title AS category_title, c.path AS category_route, c.access AS category_access, c.alias AS category_alias')
            ->join('LEFT', '#__categories AS c ON c.id = a.catid');

        // Join over the users for the author and modified_by names.
        $query->select("CASE WHEN a.created_by_alias > ' ' THEN a.created_by_alias ELSE ua.name END AS author")
            ->select("ua.email AS author_email")

            ->join('LEFT', '#__users AS ua ON ua.id = a.created_by')
            ->join('LEFT', '#__users AS uam ON uam.id = a.modified_by');

        // Join over the categories to get parent category titles
        $query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias')
            ->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');

        // Join on voting table
        $query->select('ROUND(v.rating_sum / v.rating_count, 0) AS rating, v.rating_count as rating_count')
            ->join('LEFT', '#__content_rating AS v ON a.id = v.content_id');

        // Join to check for category published state in parent categories up the tree
        $query->select('c.published, CASE WHEN badcats.id is null THEN c.published ELSE 0 END AS parents_published');
        $subquery = 'SELECT cat.id as id FROM #__categories AS cat JOIN #__categories AS parent ';
        $subquery .= 'ON cat.lft BETWEEN parent.lft AND parent.rgt ';
        $subquery .= 'WHERE parent.extension = ' . $db->quote('com_content');

        if ($this->getState('filter.published') == 2)
        {
            // Find any up-path categories that are archived
            // If any up-path categories are archived, include all children in archived layout
            $subquery .= ' AND parent.published = 2 GROUP BY cat.id ';

            // Set effective state to archived if up-path category is archived
            $publishedWhere = 'CASE WHEN badcats.id is null THEN a.state ELSE 2 END';
        }
        else
        {
            // Find any up-path categories that are not published
            // If all categories are published, badcats.id will be null, and we just use the article state
            $subquery .= ' AND parent.published != 1 GROUP BY cat.id ';

            // Select state to unpublished if up-path category is unpublished
            $publishedWhere = 'CASE WHEN badcats.id is null THEN a.state ELSE 0 END';
        }

        $query->join('LEFT OUTER', '(' . $subquery . ') AS badcats ON badcats.id = c.id');

        // Filter by access level.
        if ($access = $this->getState('filter.access'))
        {
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $query->where('a.access IN (' . $groups . ')')
                ->where('c.access IN (' . $groups . ')');
        }

        // Filter by published state
        $published = $this->getState('filter.published');

        if (is_numeric($published))
        {
            // Use article state if badcats.id is null, otherwise, force 0 for unpublished
            $query->where($publishedWhere . ' = ' . (int) $published);
        }
        elseif (is_array($published))
        {
            $jArrayHelper::toInteger($published);
            $published = implode(',', $published);

            // Use article state if badcats.id is null, otherwise, force 0 for unpublished
            $query->where($publishedWhere . ' IN (' . $published . ')');
        }

        // Filter by featured state
        $featured = $this->getState('filter.featured');

        switch ($featured)
        {
            case 'hide':
                $query->where('a.featured = 0');
                break;

            case 'only':
                $query->where('a.featured = 1');
                break;

            case 'show':
            default:
                // Normally we do not discriminate
                // between featured/unfeatured items.
                break;
        }

        // Filter by a single or group of articles.
        $articleId = $this->getState('filter.article_id');

        if (is_numeric($articleId))
        {
            $type = $this->getState('filter.article_id.include', true) ? '= ' : '<> ';
            $query->where('a.id ' . $type . (int) $articleId);
        }
        elseif (is_array($articleId))
        {
            $jArrayHelper::toInteger($articleId);
            $articleId = implode(',', $articleId);
            $type = $this->getState('filter.article_id.include', true) ? 'IN' : 'NOT IN';
            $query->where('a.id ' . $type . ' (' . $articleId . ')');
        }

        // Filter by a single or group of categories
        $categoryId = $this->getState('filter.category_id');
        $includeSubcategories = $this->getState('filter.subcategories', false);

        if (is_numeric($categoryId))
        {
            $type = $this->getState('filter.category_id.include', true) ? '= ' : '<> ';

            $categoryEquals = 'a.catid ' . $type . (int) $categoryId;

            // Add subcategory check
            if ($includeSubcategories)
            {
                $levels = (int) $this->getState('filter.max_category_levels', '1');

                // Create a subquery for the subcategory list
                $subQuery = $db->getQuery(true)
                    ->select('sub.id')
                    ->from('#__categories as sub')
                    ->join('INNER', '#__categories as this ON sub.lft > this.lft AND sub.rgt < this.rgt')
                    ->where('this.id = ' . (int) $categoryId);

                if ($levels >= 0)
                {
                    $subQuery->where('sub.level <= this.level + ' . $levels);
                }

                // Add the subquery to the main query
                $query->where('(' . $categoryEquals . ' OR a.catid IN (' . $subQuery->__toString() . '))');
            }
            else
            {
                $query->where($categoryEquals);
            }
        }
        elseif (is_array($categoryId) && (count($categoryId) > 0))
        {
            $jArrayHelper::toInteger($categoryId);
            $categoryIds = implode(',', $categoryId);
            $type = $this->getState('filter.category_id.include', true) ? 'IN' : 'NOT IN';
            $categoryEquals = 'a.catid ' . $type . ' (' . $categoryIds . ')';

            if (!empty($categoryIds))
            {
                // Add subcategory check
                if ($includeSubcategories)
                {
                    $levels = (int) $this->getState('filter.max_category_levels', '1');
                    // Create a subquery for the subcategory list
                    $subQuery = $db->getQuery(true)
                        ->select('sub.id')
                        ->from('#__categories as sub')
                        ->join('INNER', '#__categories as this ON sub.lft > this.lft AND sub.rgt < this.rgt')
                        ->where('this.id IN (' . $categoryIds . ')');

                    if ($levels >= 0)
                    {
                        $subQuery->where('sub.level <= this.level + ' . $levels);
                    }

                    // Add the sub query to the main query
                    $query->where('(' . $categoryEquals . ' OR a.catid IN (' . $subQuery->__toString() . '))');
                }
                else
                {
                    $query->where($categoryEquals);
                }
            }
        }
        else
        {
            $query->where(" a.catid = '' ");
        }

        // Filter by author
        $authorId = $this->getState('filter.author_id');
        $authorWhere = '';

        if (is_numeric($authorId))
        {
            $type = $this->getState('filter.author_id.include', true) ? '= ' : '<> ';
            $authorWhere = 'a.created_by ' . $type . (int) $authorId;
        }
        elseif (is_array($authorId))
        {
            $jArrayHelper::toInteger($authorId);
            $authorId = implode(',', $authorId);

            if ($authorId)
            {
                $type = $this->getState('filter.author_id.include', true) ? 'IN' : 'NOT IN';
                $authorWhere = 'a.created_by ' . $type . ' (' . $authorId . ')';
            }
        }

        // Filter by author alias
        $authorAlias = $this->getState('filter.author_alias');
        $authorAliasWhere = '';

        if (is_string($authorAlias))
        {
            $type = $this->getState('filter.author_alias.include', true) ? '= ' : '<> ';
            $authorAliasWhere = 'a.created_by_alias ' . $type . $db->quote($authorAlias);
        }
        elseif (is_array($authorAlias))
        {
            $first = current($authorAlias);

            if (!empty($first))
            {
                $jArrayHelper::toString($authorAlias);

                foreach ($authorAlias as $key => $alias)
                {
                    $authorAlias[$key] = $db->quote($alias);
                }

                $authorAlias = implode(',', $authorAlias);

                if ($authorAlias)
                {
                    $type = $this->getState('filter.author_alias.include', true) ? 'IN' : 'NOT IN';
                    $authorAliasWhere = 'a.created_by_alias ' . $type . ' (' . $authorAlias .
                        ')';
                }
            }
        }

        if (!empty($authorWhere) && !empty($authorAliasWhere))
        {
            $query->where('(' . $authorWhere . ' OR ' . $authorAliasWhere . ')');
        }
        elseif (empty($authorWhere) && empty($authorAliasWhere))
        {
            // If both are empty we don't want to add to the query
        }
        else
        {
            // One of these is empty, the other is not so we just add both
            $query->where($authorWhere . $authorAliasWhere);
        }

        // Define null and now dates
        $nullDate	= $db->quote($db->getNullDate());
        $nowDate	= $db->quote(JFactory::getDate()->toSql());

        // Filter by start and end dates.
        if ((!$user->authorise('core.edit.state', 'com_content')) && (!$user->authorise('core.edit', 'com_content')))
        {
            $query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')')
                ->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
        }

        // Filter by Date Range or Relative Date
        $dateFiltering = $this->getState('filter.date_filtering', 'off');
        $dateField = $this->getState('filter.date_field', 'a.created');

        switch ($dateFiltering)
        {
            case 'range':
                $startDateRange = is_null($this->getState('filter.start_date_range')) ? $nullDate : $db->quote($this->getState('filter.start_date_range'));
                $endDateRange = is_null($this->getState('filter.end_date_range')) ? $nowDate : $db->quote($this->getState('filter.end_date_range'));
                $query->where(
                    '(' . $dateField . ' >= DATE(' . $startDateRange . ') AND ' . $dateField .
                    ' <= DATE(' . $endDateRange . '))'
                );
                break;

            case 'relative':
                $relativeDate = (int) $this->getState('filter.relative_date', 0);
                $query->where(
                    $dateField . ' >= DATE(DATE_SUB(' . $nowDate . ', INTERVAL ' .
                    $relativeDate . ' DAY))'
                );
                break;

            case 'off':
            default:
                break;
        }

        // Process the filter for list views with user-entered filters
        $params = $this->getState('params');

        if ((is_object($params)) && ($params->get('filter_field') != 'hide') && ($filter = $this->getState('list.filter')))
        {
            // Clean filter variable
            $filter = $jString::strtolower($filter);
            $hitsFilter = (int) $filter;
            $filter = $db->quote('%' . $db->escape($filter, true) . '%', false);

            switch ($params->get('filter_field'))
            {
                case 'author':
                    $query->where(
                        'LOWER( CASE WHEN a.created_by_alias > ' . $db->quote(' ') .
                        ' THEN a.created_by_alias ELSE ua.name END ) LIKE ' . $filter . ' '
                    );
                    break;

                case 'hits':
                    $query->where('a.hits >= ' . $hitsFilter . ' ');
                    break;

                case 'title':
                default:
                    // Default to 'title' if parameter is not valid
                    $query->where('LOWER( a.title ) LIKE ' . $filter);
                    break;
            }
        }

        // Filter by language
        if ($this->getState('filter.language'))
        {
            $query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
        }

        // Add the list ordering clause.
        // $query->order($this->getState('list.ordering', 'a.ordering') . ' ' . $this->getState('list.direction', 'ASC'));

        return $query;
    }

    /**
     * @param $attributes
     *
     * @return array
     */
    public function getArticlesByAttributes($attributes)
    {
        $db = JFactory::getDbo();
        $db->getQuery(true);

        // Publish article only
        $this->setState('filter.published', 1);

        // Set show all sub category
        $this->setState('filter.subcategories', true);
        $this->setState('filter.max_category_levels', '-1');

        // Set filter by category id
        $categories = isset($attributes['articlelist_filter_categories']) ? explode(",", $attributes['articlelist_filter_categories']) : false;
        $this->setState('filter.category_id', $categories);

        // Set filter by author id
        $authors = isset($attributes['articlelist_filter_authors']) ? explode(",", $attributes['articlelist_filter_authors']) : false;
        $this->setState('filter.author_id', $authors);

        // Set filter by date
        $this->setState('filter.date_filtering', $attributes['articlelist_filter_date']);
        $this->setState('filter.date_field', $attributes['articlelist_date_field']);
        if (isset($attributes['articlelist_range_date_start'])) {
            $this->setState('filter.start_date_range', JFactory::getDate($attributes['articlelist_range_date_start'])->toSql());
        }
        if (isset($attributes['articlelist_range_date_end'])) {
            $this->setState('filter.end_date_range', JFactory::getDate($attributes['articlelist_range_date_end'])->toSql());
        }
        if (isset($attributes['articlelist_relative_date'])) {
            $this->setState('filter.relative_date', (int) $attributes['articlelist_relative_date']);
        }

        $query = $this->getListQuery();

        // Set order
        $query->order($attributes['articlelist_sort_by'] . " " .$attributes['articlelist_sort_order']);

        $db->setQuery($query, 0, (int) $attributes['articlelist_amount']);

        return $db->loadAssocList('id');
    }
}

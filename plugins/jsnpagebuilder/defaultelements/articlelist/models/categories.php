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

include_once JPATH_ROOT . '/administrator/components/com_categories/models/categories.php';

/**
 * Model class for categories
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbCategoriesModel extends CategoriesModelCategories
{
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @see     JController
     * @since   1.6
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    /**
     * Method to get a database query to list categories.
     *
     * @return array
     *
     * @since   1.6
     */
    public function getCategories()
    {
        $db = JFactory::getDbo();
        $db->getQuery(true);
        $db->setQuery($this->getListQuery());

        return $db->loadAssocList('id');
    }
}

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
 * Helper class for JSN Uniform element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbUniformHelper {
    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     *
     */
    public static function getAllUniform() {
        $allForm = array();
        if (JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_uniform")) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            //build the list of categories
            $query->select('uf.form_title AS title, uf.form_id AS id')
                ->from('#__jsn_uniform_forms AS uf')
                ->where('uf.form_state = 1');
            $db->setQuery($query);
            $allForm = $db->loadAssocList('id', 'title');
        }
        if (count($allForm) == 0) {
            $allForm[0] = JText::_('PLG_EDITOR_DO_NOT_HAVE_ANY_FORM');
        }

        return $allForm;
    }
}
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
 * Helper class for JSN Easy Slider element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbEasySliderHelper {
    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     *
     */
    public static function getAllSlider() {
        $allSlider = array();
        if (JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_easyslider")) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            //build the list of categories
            $query->select('es.slider_title AS title, es.slider_id AS id')
                ->from('#__jsn_easyslider_sliders AS es')
                ->where('es.published = 1');
            $db->setQuery($query);
            $allSlider = $db->loadAssocList('id', 'title');
        }
        if (count($allSlider) == 0) {
            $allSlider[0] = JText::_('PLG_EDITOR_DO_NOT_HAVE_ANY_SLIDER');
        }

        return $allSlider;
    }
}
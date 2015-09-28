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
 * Helper class for weather element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbImageHelper
{
    /**
     * Link type options
     *
     * @return array
     */
    public static function getClickActionType()
    {
        return array(
            'no_link' => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGE_HELPER_NO_ACTION'),
            'image'   => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGE_HELPER_SHOW_ORIGINAL_IMAGE'),
            'url'     => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGE_HELPER_OPEN_IMAGE_LINK'),
        );
    }

    /**
     * "Open in" option for anchor
     *
     * @return array
     */
    static function getOpenInOptions() {
        return array(
            'current_browser' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_IMAGE_HELPER_CURRENT_BROWSER' ),
            'new_browser' 	  => JText::_( 'JSN_PAGEBUILDER_ELEMENT_IMAGE_HELPER_NEW_BROWSER' ),
        );
    }
}
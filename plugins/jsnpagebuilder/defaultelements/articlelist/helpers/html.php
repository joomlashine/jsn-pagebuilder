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
 * Class ArticlelistHelperHtml
 */
class ArticlelistHelperHtml extends IG_Pb_Helper_Html
{
    /**
     * @param array $element
     *
     * @return string
     */
    static function article_sort($element)
    {
        $element = self::get_extra_info($element);
        $label = self::get_label($element);
        $output = '';

        foreach ($element['extended_ids'] as $_extendedId) {
            $_extended = array(
                "id"    => "param-" . $_extendedId,
                "type"  => "select",
                "class" => "input-medium combo-item",
                "bound" => '0'
            );

            if (isset($element[$_extendedId])) {
                $_extended = array_merge($_extended, $element[$_extendedId]);
            }

            $output .= self::select($_extended);
        }

        return self::final_element($element, $output, $label);
    }

    /**
     * @param $element
     *
     * @return string
     */
    static function field_set_label($element)
    {
        $element = self::get_extra_info($element);
        $label = "";

        $output = "<div class='field-set-label-break'><div class='field-set-label'>" . $element['name'] . "</div></div>";
        return self::final_element($element, $output, $label);
    }
}
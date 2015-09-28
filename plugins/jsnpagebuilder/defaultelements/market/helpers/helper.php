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
 * Helper class for market element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbMarketHelper
{
    const PB_MARKET_DATA_TYPE_NAME = 'name';
    const PB_MARKET_DATA_TYPE_SYMBOL = 'symbol';
    const PB_MARKET_DATA_TYPE_PRICE = 'LastTradePriceOnly';
    const PB_MARKET_DATA_TYPE_CHANGE = 'Change';
    const PB_MARKET_DATA_TYPE_PERCENT_CHANGE = 'ChangeinPercent';
    const PB_MARKET_DATA_TYPE_VOLUME = 'Volume';
    const PB_MARKET_DATA_TYPE_CHART = 'chart';

    const PB_MARKET_SLIDE_DIMENSION_HORIZONTAL = "horizontal";
    const PB_MARKET_SLIDE_DIMENSION_VERTICAL = "vertical";

    public static function getMarketSlideDimensions() {
        return array(
            self::PB_MARKET_SLIDE_DIMENSION_VERTICAL => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_HELPER_VERTICAL'),
            self::PB_MARKET_SLIDE_DIMENSION_HORIZONTAL => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_HELPER_HORIZONTAL'),
        );
    }

    public static function getMarketDataType() {
        return array(
            self::PB_MARKET_DATA_TYPE_NAME           => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_HELPER_NAME'),
            self::PB_MARKET_DATA_TYPE_SYMBOL         => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_HELPER_SYMBOL'),
            self::PB_MARKET_DATA_TYPE_PRICE          => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_HELPER_PRICE'),
            self::PB_MARKET_DATA_TYPE_CHANGE         => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_HELPER_CHANGE'),
            self::PB_MARKET_DATA_TYPE_PERCENT_CHANGE => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_HELPER_PERCEN_CHANGE'),
            self::PB_MARKET_DATA_TYPE_VOLUME         => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_HELPER_VOLUME'),
//            self::PB_MARKET_DATA_TYPE_CHART          => JText::_('Chart'),
        );
    }

    public static function getMarketDefaultDataType() {
        return implode("__#__", array(
            self::PB_MARKET_DATA_TYPE_SYMBOL,
            self::PB_MARKET_DATA_TYPE_PRICE,
            self::PB_MARKET_DATA_TYPE_CHANGE,
            self::PB_MARKET_DATA_TYPE_PERCENT_CHANGE,
        ));
    }
}
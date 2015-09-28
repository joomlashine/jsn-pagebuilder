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

include_once 'helpers/helper.php';

/**
 * Market shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeMarket extends IG_Pb_Element
{
    const PB_MARKET_CAROUSEL_LAYOUT = "carousel";
    const PB_MARKET_TABLE_LAYOUT = "table";

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Include admin scripts
     *
     * @return mixed
     */
    public function backend_element_assets()
    {
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/market/assets/js/3rd-party/slick/slick.min.js', 'js');
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/market/assets/js/3rd-party/slick/slick.css', 'css');
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/market/assets/css/market.css', 'css');
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/market/assets/js/market.js', 'js');
    }

    /**
     * DEFINE configuration information of shortcode
     *
     * @return mixed
     */
    function element_config()
    {
        $this->config['shortcode'] = 'pb_market';
        $this->config['name'] = JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET');
        $this->config['cat'] = JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_EXTRA');
        $this->config['icon'] = "icon-market";
        $this->config['has_subshortcode'] = __CLASS__ . 'Item';
        $this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_MARKET_DES");

        $this->config['exception'] = array(
            'default_content' => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET')
        );
    }

    /**
     * DEFINE setting options of shortcode in backend
     */
    public function backend_element_items()
    {
        $this->frontend_element_items();
    }

    /**
     * DEFINE setting options of shortcode in frontend
     */
    public function frontend_element_items()
    {
        $this->items = array(
            'content' => array(
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE"),
                    "id"      => "el_title",
                    "type"    => "text_field",
                    "class"   => "jsn-input-xxlarge-fluid",
                    "std"     => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_ELEMENT_TITLE_STD'),
                    "role"    => "title",
                    "tooltip" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES")
                ),
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_TITLE"),
                    "id"      => "market_title",
                    "type"    => "text_field",
                    "class"   => "jsn-input-xxlarge-fluid",
                    "std"     => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_YAHOO'),
                ),
                array(
                    'id'            => 'market_items',
                    'name'          => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_ITEMS'),
                    'type'          => 'group',
                    'shortcode'     => $this->config['shortcode'],
                    'sub_item_type' => 'JSNPBShortcodeMarketItem',
                    'sub_items'     => array(
                        array('std' => '[pb_market_item market_item_text="EUR/USD" market_item_symbol_code="EURUSD=X"]'),
                        array('std' => '[pb_market_item market_item_text="USD/JPY" market_item_symbol_code="USDJPY=X"]'),
                        array('std' => '[pb_market_item market_item_text="GBP/USD" market_item_symbol_code="GBPUSD=X"]'),
                    ),
                    'label_item'    => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_ITEMS_LABEL'),
                ),
            ),
            'styling' => array(
                array(
                    'type' => 'preview'
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_CHOOSE_LAYOUT'),
                    'id'         => 'market_layout',
                    'type'       => 'radio',
                    'std'        => self::PB_MARKET_TABLE_LAYOUT,
                    'options'    => array(
                        self::PB_MARKET_TABLE_LAYOUT    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_LAYOUT_TABLE'),
                        self::PB_MARKET_CAROUSEL_LAYOUT => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_LAYOUT_CAROUSEL'),
                    ),
                    'has_depend' => '1'
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_SLIDE_DIMENSION'),
                    'id'         => 'market_slide_dimension',
                    'type'       => 'select',
                    'std'        => JSNPagebuilderHelpersType::getFirstOption(JSNPbMarketHelper::getMarketSlideDimensions()),
                    'options'    => JSNPbMarketHelper::getMarketSlideDimensions(),
                    'dependency' => array('market_layout', '=', 'carousel'),
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_SHOW_CAROUSEL_CONTROL'),
                    'id'         => 'market_show_carousel_control',
                    'type'       => 'radio',
                    'std'        => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options'    => JSNPagebuilderHelpersType::getYesNoQuestion(),
                    'dependency' => array('market_layout', '=', 'carousel'),
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_NUMBER_TO_SHOW'),
                    'id'         => 'market_number_to_show',
                    'type'       => 'text_number',
                    'std'        => 3,
                    'validate'   => 'number',
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_CAROUSEL_AUTO_PLAY'),
                    'id'         => 'market_auto_play_carousel',
                    'type'       => 'radio',
                    'std'        => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options'    => JSNPagebuilderHelpersType::getYesNoQuestion(),
                    'dependency' => array('market_layout', '=', 'carousel'),
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_AUTOPLAY_SPEED'),
                    'id'         => 'market_auto_play_speed',
                    'type'       => 'text_number',
                    'std'        => 3,
                    'validate'   => 'number',
                ),
                array(
                    'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_SHOW_TIME_UPDATE'),
                    'id'      => 'market_show_time_update',
                    'type'    => 'radio',
                    'std'     => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options' => JSNPagebuilderHelpersType::getYesNoQuestion(),
                ),
                array(
                    'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_SHOW_MARKET_DATA'),
                    'id'      => 'market_show_data',
                    'type'    => 'checkbox',
                    "class"   => "checkbox inline",
                    'std'     => JSNPbMarketHelper::getMarketDefaultDataType(),
                    'options' => JSNPbMarketHelper::getMarketDataType(),
                ),
            )
        );

    }

    /**
     * DEFINE shortcode content
     *
     * @param array $attributes
     * @param mixed $content
     *
     * @return string
     */
    function element_shortcode($attributes = null, $content = null)
    {
        $document = JFactory::getDocument();
        $document->addScript(JSNPB_ELEMENT_URL . '/market/assets/js/3rd-party/slick/slick.min.js', 'text/javascript');
        $document->addStyleSheet(JSNPB_ELEMENT_URL . '/market/assets/js/3rd-party/slick/slick.css', 'text/css');
        $document->addStyleSheet(JSNPB_ELEMENT_URL . '/market/assets/css/market.css', 'text/css');

        $arrParams = JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $attributes);
        $htmlElement = '';

        $itemShortcode = empty($content) ? JSNPagebuilderHelpersShortcode::removeAutop($content) : JSNPagebuilderHelpersBuilder::generateShortCode($content, false, 'frontend', true);

        $itemsData = explode('<!--separate-->', $itemShortcode);
        // remove empty element
        $itemsData = array_filter($itemsData);
        $dataMarket = array();
        // decode
        foreach ($itemsData as $_key => $_value) {
            $dataMarket[] = json_decode($_value, true);
        }

        $randomMarketId = "market-" . JSNPagebuilderHelpersShortcode::generateRandomString();
        $marketLayoutClass = $attributes['market_layout'] == self::PB_MARKET_CAROUSEL_LAYOUT ? 'pb-market-carousel' : 'pb-market-table';
        $htmlElement .= "<div id='{$randomMarketId}' class='pb-market-wrapper {$marketLayoutClass}'><div class='pb-market-header'><div class='pb-market-title'>{$attributes['market_title']}</div>";
        if (count($dataMarket) <= 0) {

        } else {
            if ($attributes['market_show_time_update'] == 'yes') {
                $timeStamp = new DateTime($dataMarket[0]['time_stamp']);
                $timeStamp->setTimezone(new DateTimeZone('UTC'));
                $htmlElement .= "<div class='pb-market-update-time'>{$timeStamp->format('D, M d, Y, g:iA e')}</div>";
            }

            $headerList = explode("__#__", $attributes['market_show_data']);
            foreach ($headerList as $_key => $_value) {
                if ($_value == null || $_value == " ") {
                    unset($headerList[$_key]);
                }
            }

            $marketDataType = JSNPbMarketHelper::getMarketDataType();
            if ($attributes['market_layout'] == self::PB_MARKET_CAROUSEL_LAYOUT) {
                if ($attributes['market_show_carousel_control'] == JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES) {
                    $htmlElement .= "<div class='pb-market-carousel-control'>";
                    if ($attributes['market_slide_dimension'] == JSNPbMarketHelper::PB_MARKET_SLIDE_DIMENSION_HORIZONTAL) {
                        $htmlElement .= "<div class='button pb-market-carousel-up'><i class='fa fa-chevron-up'></i></div>";
                        $htmlElement .= "<div class='button pb-market-carousel-down'><i class='fa fa-chevron-down'></i></div>";
                    } else {
                        $htmlElement .= "<div class='button pb-market-carousel-back'><i class='fa fa-chevron-left'></i></div>";
                        $htmlElement .= "<div class='button pb-market-carousel-next'><i class='fa fa-chevron-right'></i></div>";
                    }
                    $htmlElement .= "</div>";
                }
                // End of header
                $htmlElement .= "</div>";
                $htmlElement .= "<div class='pb-market-content pb-market-layout-{$attributes['market_slide_dimension']}'>";
                // Show carousel layout
                foreach ($dataMarket as $_key => $_item) {
                    if (strpos($_item['data'][JSNPbMarketHelper::PB_MARKET_DATA_TYPE_CHANGE], "+") !== false) {
                        $htmlElement .= "<div class='pb-market-carousel-item change-up'>";
                    } else {
                        $htmlElement .= "<div class='pb-market-carousel-item change-down'>";
                    }
                    $style = "";
                    if ($attributes['market_slide_dimension'] == JSNPbMarketHelper::PB_MARKET_SLIDE_DIMENSION_HORIZONTAL) {
                        $style .= "style='width: " . (100/count($headerList)) . "%'";
                    }
                    if ($_item['data'][JSNPbMarketHelper::PB_MARKET_DATA_TYPE_CHANGE] == null) {
                        $index = 0;
                        foreach ($headerList as $_value) {
                            if ($_value == JSNPbMarketHelper::PB_MARKET_DATA_TYPE_NAME
                                || $_value == JSNPbMarketHelper::PB_MARKET_DATA_TYPE_SYMBOL) {
                                $htmlElement .= "<div {$style} class='market-" . strtolower(str_replace("% ", "percent-", $marketDataType[$_value])) . "'>{$_item['data'][$_value]}</div>";
                                $index++;
                            }
                        }
                        if ((count($headerList) - $index) > 0) {
                            if (isset($_item['error'])) {
                                $htmlElement .= "<div class='error'>" . $_item['error'] . "</div>";
                            } else {
                                $htmlElement .= "<div class='error'>" . JText::_('JSN_PAGEBUILDER_MARKET_ERROR_SYMBOL') . "</div>";
                            }
                        }
                    } else {
                        foreach ($headerList as $_value) {
                            if (isset($_item['data'][$_value])) {
                                $htmlElement .= "<div {$style} class='market-" . strtolower(str_replace("% ", "percent-", $marketDataType[$_value])) . "'>{$_item['data'][$_value]}</div>";
                            }
                        }
                    }

                    $htmlElement .= "</div>";
                }
            } else {
                // End of header
                $htmlElement .= "</div>";
                $htmlElement .= "<div class='pb-market-content'>";
                // Show table layout
                $htmlElement .= "<table class='table'>";

                $htmlElement .= "<thead><tr>";
                foreach ($headerList as $_key => $_value) {
                    if (isset($marketDataType[$_value]) && $_value != JSNPbMarketHelper::PB_MARKET_DATA_TYPE_CHART) {
                        $htmlElement .= "<th class='market-" . strtolower(str_replace("% ", "percent-", $marketDataType[$_value])) . "'>{$marketDataType[$_value]}</th>";
                    }
                }
                $htmlElement .= "</tr></thead>";
                $htmlElement .= "<tbody>";
                foreach ($dataMarket as $_key => $_item) {
                    if ($_item['data'][JSNPbMarketHelper::PB_MARKET_DATA_TYPE_CHANGE] == null) {
                        $htmlElement .= "<tr class='error'>";
                        $index = 0;
                        foreach ($headerList as $_value) {
                            if ($_value == JSNPbMarketHelper::PB_MARKET_DATA_TYPE_NAME
                                || $_value == JSNPbMarketHelper::PB_MARKET_DATA_TYPE_SYMBOL) {
                                $htmlElement .= "<td class='market-" . strtolower(str_replace("% ", "percent-", $marketDataType[$_value])) . "'>{$_item['data'][$_value]}</td>";
                                $index++;
                            }
                        }
                        if ((count($headerList) - $index) > 0) {
                            $htmlElement .= "<td colspan='" . (count($headerList) - 1) . "'>" . JText::_('JSN_PAGEBUILDER_MARKET_ERROR_SYMBOL') . "</td>";
                        }
                    } else {
                        if (strpos($_item['data'][JSNPbMarketHelper::PB_MARKET_DATA_TYPE_CHANGE], "+") !== false) {
                            $htmlElement .= "<tr class='change-up'>";
                        } else {
                            $htmlElement .= "<tr class='change-down'>";
                        }
                        foreach ($headerList as $_value) {
                            if (isset($_item['data'][$_value])) {
                                $htmlElement .= "<td class='market-" . strtolower(str_replace("% ", "percent-", $marketDataType[$_value])) . "'>{$_item['data'][$_value]}</td>";
                            }
                        }
                    }
                    $htmlElement .= "</tr>";
                }
                $htmlElement .= "</tbody>";
                $htmlElement .= "</table>";
            }
        }
        $htmlElement .= "</div></div>";

        if ($attributes['market_layout'] == self::PB_MARKET_CAROUSEL_LAYOUT) {
            $scriptCarousel = "<script type='text/javascript'>(function ($) {
					$(document).ready(function ()
					{
                        $('#{$randomMarketId} .pb-market-content').slick({
                            infinite: true";
            if ($attributes['market_slide_dimension'] == JSNPbMarketHelper::PB_MARKET_SLIDE_DIMENSION_HORIZONTAL) {
                $scriptCarousel .= ", vertical: true, slidesToShow: " . (int) $attributes['market_number_to_show'];
            } else {
                $scriptCarousel .= ", variableWidth: true";
            }
            if ($attributes['market_auto_play_carousel'] == JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES) {
                $scriptCarousel .= ", autoplay: true";
                if (is_numeric($attributes['market_auto_play_speed']) && (int) $attributes['market_auto_play_speed'] > 0) {
                    $scriptCarousel .= ", autoplaySpeed: {$attributes['market_auto_play_speed']}000";
                } else {
                    $scriptCarousel .= ", autoplaySpeed: 3000";
                }
            }
            if ($attributes['market_show_carousel_control'] == JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES) {
                if ($attributes['market_slide_dimension'] == JSNPbMarketHelper::PB_MARKET_SLIDE_DIMENSION_HORIZONTAL) {
                    $scriptCarousel .= ", prevArrow: $('#{$randomMarketId}').find('.pb-market-carousel-up'), nextArrow: $('#{$randomMarketId}').find('.pb-market-carousel-down')";
                } else {
                    $scriptCarousel .= ", prevArrow: $('#{$randomMarketId}').find('.pb-market-carousel-back'), nextArrow: $('#{$randomMarketId}').find('.pb-market-carousel-next')";
                }
            } else {
                $scriptCarousel .= ", arrows: false";
            }
            $scriptCarousel .= ", swipe: false
                        });
					});
				})(JoomlaShine.jQuery);</script>";

            $htmlElement .= $scriptCarousel;
        }

        return $this->element_wrapper($htmlElement, $arrParams);
    }
}
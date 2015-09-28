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
 * Market Item shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeMarketItem extends IG_Pb_Child
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Include admin scripts
     */
    public function backend_element_assets()
    {
    }

    /**
     * DEFINE configuration information of shortcode
     *
     * @return mixed
     */
    public function element_config()
    {
        $this->config['shortcode'] = 'pb_market_item';
        $this->config['exception'] = array(
            'item_text'        => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET'),
            'data-modal-title' => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_ITEM')
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
            'Notab' => array(
                array(
                    'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_TEXT'),
                    'id'      => 'market_item_text',
                    'type'    => 'text_field',
                    'class'   => 'jsn-input-xxlarge-fluid',
                    'std'     => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_ITEM_STD'),
                    'role'    => 'title',
                    'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_TEXT_DES')
                ),
                array(
                    'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_ITEM_SYMBOL_CODE'),
                    'id'      => 'market_item_symbol_code',
                    'type'    => 'text_field',
                    'class'   => 'jsn-input-xxlarge-fluid',
                    'std'     => 'EURUSD=X',
                    'tooltip' => JText::_('JSN_PAGEBUILDER_ELEMENT_MARKET_ITEM_SYMBOL_CODE_DES')
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
    public function element_shortcode($attributes = null, $content = null)
    {
        extract(JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $attributes));

        $queryString = "http://query.yahooapis.com/v1/public/yql?env=store://datatables.org/alltableswithkeys&format=json&q=select * from yahoo.finance.quotes where symbol in ('" . $attributes['market_item_symbol_code'] . "')";

        try {
            $result = JSNUtilsHttp::get($queryString);
            $dataRaw = json_decode($result['body'], true);

            if (isset($dataRaw['error'])) {
                return "<!--separate-->";
            } else {
                $dataItem['time_stamp'] = $dataRaw['query']['created'];
                $dataItem['data'] = $dataRaw['query']['results']['quote'];
                $dataItem['data']['name'] = $attributes['market_item_text'];
            }

            return json_encode($dataItem) . '<!--separate-->' ;
        }
        catch (Exception $e) {
            $dataItem['error'] = $e->getMessage();
            return json_encode($dataItem) . "<!--separate-->";
        }
    }

}

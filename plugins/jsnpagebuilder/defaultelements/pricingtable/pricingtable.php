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

include_once (dirname(__FILE__) . '/pricingtableattr/pricingtableattr.php');
include_once (dirname(__FILE__) . '/pricingtable/item.php');


/**
 * Pricing table element for WR PageBuilder.
 *
 * @since  1.0.0
 */
class JSNPBShortcodePricingtable extends IG_Pb_Parent
{
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	// Predefined Attributes
	static $attributes = array(
		'max_domains' => array(
			'title' => 'Max Domains',
			'value' => array('1', '5', '20'),
			'type'  => 'text',
		),
		'storage'     => array(
			'title' => 'Storage',
			'value' => array('100 MB', '500 MB', '2 TB'),
			'type'  => 'text',
		),
		'ssl_support' => array(
			'title' => 'SSL Support',
			'value' => array('no', 'yes', 'yes'),
			'type'  => 'checkbox',
		),
	);

	// Store index of pricing option/ pricing attribute
	static $index = 0;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Include admin scripts
	 *
	 * @return type
	 */
	public function backend_element_assets() {
		$document = JFactory::getDocument();
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ELEMENT_URL.'/pricingtable/assets/js/pricingtable.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ELEMENT_URL.'/pricingtable/assets/css/pricingtable.css', 'css' );
	}


	/**
	 * Configure shortcode.
	 *
	 * @return  void
	 */
	public function element_config()
	{
		$this->config['shortcode']        = 'pb_pricingtable';
		$this->config['name']             = JText::_('JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE');
		$this->config['cat']              = JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_EXTRA');
		$this->config['icon']             = 'icon-pricing-table';
		$this->config['description']      = JText::_('JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_DES');
		$this->config['has_subshortcode'] = (__CLASS__) . 'Item';

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'default_content'  => JText::_('JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE'),
			'data-modal-title' => JText::_('JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE'),


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
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE' ),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_ELEMENT_TITLE_STD' ),
					'role'    => 'title',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES' )
				),
				array(
					'name'                     => JText::_('JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_ATTRIBUTES'),
					'id'                       => 'prtbl_attr',
					'type'                     => 'group',
					'shortcode'                => $this->config['shortcode'] ,
					'sub_shortcode'            => 'pb_pricingtableattr_item',
					'sub_item_type'            => (__CLASS__) . 'attrItem',
					'sub_items'                => array(
						JSNPBShortcodePricingTable::get_option('max_domains'),
						JSNPBShortcodePricingTable::get_option('storage'),
						JSNPBShortcodePricingTable::get_option('ssl_support'),
					),
//					'shortcode_name'           => $this->config['shortcode'] . 'attr_item',
					'tooltip'                  => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_ATTRIBUTES_DES' ),
					'overwrite_shortcode_data' => false,
				),
				array(
					'name'                     => JText::_('JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_OPTIONS'),
					'id'                       => 'prtbl_items',
					'type'                     => 'group',
					'no_title'                 => JText::_('JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_OPTIONS_NO_TITLE'),
					'shortcode'                => $this->config['shortcode'],
					'sub_shortcode'            => 'pb_pricingtable_item',
					'sub_item_type'            => $this->config['has_subshortcode'],
					'sub_items'                => array(
						array('std' => '', 'prtbl_item_title' => 'Free', 'prtbl_item_desc' => 'Free', 'prtbl_item_currency' => '$', 'prtbl_item_price' => '0', 'prtbl_item_time' => ' / month'),
						array('std' => '', 'prtbl_item_title' => 'Standard', 'prtbl_item_desc' => 'Standard', 'prtbl_item_currency' => '$', 'prtbl_item_price' => '69', 'prtbl_item_feature' => 'yes', 'prtbl_item_time' => ' / month'),
						array('std' => '', 'prtbl_item_title' => 'Premium', 'prtbl_item_desc' => 'Premium', 'prtbl_item_currency' => '$', 'prtbl_item_price' => '99', 'prtbl_item_time' => ' / month'),
					),
					'overwrite_shortcode_data' => false,
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENTS'),
					'id'              => 'prtbl_elements',
					'type'            => 'checkbox',
					'class'           => 'jsn-column-item checkbox',
					'container_class' => 'jsn-columns-container jsn-columns-count-two',
					'std'             => 'title__#__button__#__attributes',
					'options'         => array(
						'title'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_TITLE'),
						'description' => JText::_('JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_DESCRIPTION'),
						'image'       => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_IMAGE'),
						'attributes'  => JText::_('JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_ATTRIBUTES'),
						'price'       => JText::_('JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_PRICE'),
						'button'      => JText::_('JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_BUTTON')
					),
					'tooltip'         => JText::_('JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_ELEMENT_DES')
				),

			)
		);



	}

	/**
	 * Function to sync sub-shortcode content become sub-shortcode array
	 *
	 * @param array $arr_shortcodes
	 */
	private function sync_sub_content($sub_shortcode = '')
	{
		$document = JFactory::getDocument();
		JSNPagebuilderHelpersFunctions::loadFancyboxJS();
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-tipsy/jquery.tipsy.js', 'text/javascript' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-tipsy/tipsy.css', 'text/css' );
		$document->addStyleSheet( JSNPB_ELEMENT_URL.'/pricingtable/assets/css/pricingtable_frontend.css', 'text/css' );
		$document->addScript( JSNPB_ELEMENT_URL.'/pricingtable/assets/js/pricingtable_frontend.js', 'text/javascript' );

		$arr_shortcodes = array();
		if (!$sub_shortcode){
			return;
		}

		// Convert to sub-shortcode array
		$arr_sub_shortcode = $arr_values = array();
		$pattern           = '\\[(\\[?)(pb_pricingtableattr_item)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
		preg_match_all("/$pattern/s", $sub_shortcode, $matches);
		$arr_sub_shortcode['pb_pricingtableattr_item'] = $matches[0];

		if (isset($arr_sub_shortcode['pb_pricingtableattr_item']) && is_array($arr_sub_shortcode['pb_pricingtableattr_item']))
		{
			$arr_shortcodes['pb_pricingtableattr_item'] = implode('', $arr_sub_shortcode['pb_pricingtableattr_item']);
		}

		$pattern = '\\[(\\[?)(pb_pricingtable_item)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
		preg_match_all("/$pattern/s", $sub_shortcode, $matches);
		$arr_sub_shortcode['pb_pricingtable_item'] = $matches[0];

		if (isset($arr_sub_shortcode['pb_pricingtable_item']) && is_array($arr_sub_shortcode['pb_pricingtable_item']))
		{
			foreach ($arr_sub_shortcode['pb_pricingtable_item'] as $i => $item)
			{
				$pattern = '\\[(\\[?)(pb_pricingtable_item_item)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';

				preg_match_all("/$pattern/s", $item, $matches);
				$arr_values['pb_pricingtable_item_item'] = $matches[0];
				$count                                          = count($arr_values['pb_pricingtable_item_item']);
				$_item                                          = preg_replace("/$pattern/s", '<!--pb-replace-flag-->', $item);

				// Simulate mechanism process sub-shortcode in modal template
				$sub_sc_data = JSNPBShortcodePricingTableItem::_sub_items_filter($arr_values, 'pb_pricingtable_item', $arr_sub_shortcode['pb_pricingtableattr_item']);

				if (isset($sub_sc_data['pb_pricingtable_item_item']) && is_array($sub_sc_data['pb_pricingtable_item_item']))
				{
					$str_pr_tbl_shortcode = str_replace(str_repeat('<!--pb-replace-flag-->', $count), implode('', $sub_sc_data['pb_pricingtable_item_item']), $_item);
				}

				$str_pr_tbl_shortcode                      = str_replace('"prtbl_item_attr_value', '" prtbl_item_attr_value', $str_pr_tbl_shortcode);

				$arr_shortcodes['pb_pricingtable_item'][] = $str_pr_tbl_shortcode;
			}
		}

		return $arr_shortcodes;
	}

	private function check_field_allow($allow = '', $pattern_scan = '', $arr_allows, $pr_tbl_col_value_html = '')
	{
		if (!$allow || !$pattern_scan || !is_array($arr_allows) || !$pr_tbl_col_value_html)
			return $pr_tbl_col_value_html;

		if (in_array($allow, $arr_allows))
		{
			$pr_tbl_col_value_html = str_replace("[$pattern_scan]", '', $pr_tbl_col_value_html);
			$pr_tbl_col_value_html = str_replace("[/$pattern_scan]", '', $pr_tbl_col_value_html);
		}
		else
		{
			$pattern               = '\\[(\\[?)(' . $pattern_scan . ')(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$pr_tbl_col_value_html = preg_replace("/$pattern/s", '', $pr_tbl_col_value_html);
		}

		return $pr_tbl_col_value_html;
	}

	/**
	 * Generate HTML code from shortcode content.
	 *
	 * @param   array  $atts    Shortcode attributes.
	 * @param   string $content Current content.
	 *
	 * @return  string
	 */
	public function element_shortcode( $atts = null, $content = null ) {


		$html_element       = '';
		$arr_sub_shortcodes = self::sync_sub_content($content);

		$arr_params = (JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts));
		extract($arr_params);

		$arr_elements = explode('__#__', $prtbl_elements);

		// Build html for cols label.
		$pr_tbl_label_html = '<div class="pb-prtbl-cols first">';

		// Append blank header
		$header_ = '<div class="pb-prtbl-title">';
		if (in_array('image', $arr_elements))
		{
			$header_ .= '<div class="pb-prtbl-image"></div>';
		}
		$header_ .= '<h3>&nbsp;</h3></div>';

		if (in_array('price', $arr_elements) || in_array('description', $arr_elements))
		{
			$header_ .= '<div class="pb-prtbl-meta">';

			// append blank price
			if (in_array('price', $arr_elements))
			{
				$header_ .= '<div class="pb-prtbl-price">&nbsp;</div>';
			}

			// append blank price
			if (in_array('description', $arr_elements))
			{
				$header_ .= '<p class="pb-prtbl-desc">&nbsp;</p>';
			}
			$header_ .= '</div>';
		}

		$pr_tbl_label_html .= sprintf('<div class="pb-prtbl-header">%s</div>', $header_);
		if(in_array('attributes', $arr_elements))
		{
			if (isset($arr_sub_shortcodes['pb_pricingtableattr_item']) && !empty($arr_sub_shortcodes['pb_pricingtableattr_item']))
			{
				$pr_tbl_label_html .= '<ul class="pb-prtbl-features">';
				$pr_tbl_label_html .= JSNPagebuilderHelpersBuilder::generateShortCode($arr_sub_shortcodes['pb_pricingtableattr_item'], false, 'frontend', true);
				$pr_tbl_label_html .= '</ul>';
			}
		}

		if(in_array('button', $arr_elements)){
			$pr_tbl_label_html .= '<div class="pb-prtbl-footer"></div>';
		}
		$pr_tbl_label_html .= '</div>';

		// Build html for cols value.
		$pr_tbl_col_value_html = '';
		if (isset($arr_sub_shortcodes['pb_pricingtable_item']) && !empty($arr_sub_shortcodes['pb_pricingtable_item']))
		{

			$pr_tbl_col_value_html = JSNPagebuilderHelpersBuilder::generateShortCode(implode('', $arr_sub_shortcodes['pb_pricingtable_item']), false, 'frontend', true);
			$pr_tbl_col_value_html = $this->check_field_allow('title', 'prtbl_item_title', $arr_elements, $pr_tbl_col_value_html);
			$pr_tbl_col_value_html = $this->check_field_allow('description', 'prtbl_item_desc', $arr_elements, $pr_tbl_col_value_html);
			$pr_tbl_col_value_html = $this->check_field_allow('image', 'prtbl_item_image', $arr_elements, $pr_tbl_col_value_html);
			$pr_tbl_col_value_html = $this->check_field_allow('button', 'prtbl_item_button', $arr_elements, $pr_tbl_col_value_html);
			$pr_tbl_col_value_html = $this->check_field_allow('price', 'prtbl_item_price', $arr_elements, $pr_tbl_col_value_html);
			$pr_tbl_col_value_html = $this->check_field_allow('attributes', 'prtbl_item_attributes', $arr_elements, $pr_tbl_col_value_html);

			if (!in_array('price', $arr_elements) && !in_array('description', $arr_elements))
			{
				$pattern               = '\\[(\\[?)(prtbl_item_meta)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
				$pr_tbl_col_value_html = preg_replace("/$pattern/s", '', $pr_tbl_col_value_html);
			}
			else
			{
				$pr_tbl_col_value_html = str_replace("[prtbl_item_meta]", '', $pr_tbl_col_value_html);
				$pr_tbl_col_value_html = str_replace("[/prtbl_item_meta]", '', $pr_tbl_col_value_html);
			}
		}

		$count_columns = isset($arr_sub_shortcodes['pb_pricingtable_item']) ? count($arr_sub_shortcodes['pb_pricingtable_item']) + 1 : 1;

		$html_element = $pr_tbl_label_html . $pr_tbl_col_value_html;

		return $this->element_wrapper($html_element, $arr_params, "table-$count_columns-col");
	}

	/**
	 * Get shortcode parameters for Pricing Option
	 *
	 * @param string $attribute     The ID of attribute
	 * @param bool   $include_value Whether or not including Value parameter (true if call for WR_Item_Pricing_Table_Attr_Value)
	 \
	 * @return string
	 */
	static function get_option($attribute, $include_value = false)
	{
		// get all Predefined Attributes
		$attributes = JSNPBShortcodePricingTable::$attributes;

		// get index of current Option/Attribute
		$idx = JSNPBShortcodePricingTable::$index = JSNPBShortcodePricingTable::$index % 3;

		$title = isset ($attributes[$attribute]) ? (isset ($attributes[$attribute]['title']) ? $attributes[$attribute]['title'] : '') : '';
		$type  = isset ($attributes[$attribute]) ? (isset ($attributes[$attribute]['type']) ? $attributes[$attribute]['type'] : '') : '';
		if ($include_value)
		{
			$value = isset ($attributes[$attribute]) ? (isset ($attributes[$attribute]['value'][$idx]) ? $attributes[$attribute]['value'][$idx] : '') : '';
		}

		$result = array(
			'std' => '', 'prtbl_item_attr_id' => $attribute, 'prtbl_item_attr_title' => $title, 'prtbl_item_attr_type' => $type,
		);
		if (!$include_value)
		{
			$result['prtbl_item_attr_desc'] = $title;
		}
		else
		{
			$result['prtbl_item_attr_value'] = $result['prtbl_item_attr_desc'] = $value;
		}

		return $result;
	}

	/**
	 * Get params & structure of shortcode
	 * OVERWRIGE parent function
	 *
	 * @return type
	 */
	public function shortcode_data() {
		parent::shortcode_data();
	}

}

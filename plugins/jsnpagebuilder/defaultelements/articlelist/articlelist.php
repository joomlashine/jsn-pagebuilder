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

include_once JPATH_ROOT . '/components/com_content/helpers/route.php';
include_once 'helpers/helper.php';

/**
 * Market shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeArticleList extends IG_Pb_Element
{
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
        JSNPagebuilderHelpersFunctions::print_asset_tag(JURI::root(true) . '/media/system/js/calendar.js', 'js');
        JSNPagebuilderHelpersFunctions::print_asset_tag(JURI::root(true) . '/media/system/js/calendar-setup.js', 'js');
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/articlelist/assets/css/articlelist.css', 'css');
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/articlelist/assets/js/articlelist.js', 'js');
    }

    /**
     * DEFINE configuration information of shortcode
     *
     * @return mixed
     */
    function element_config()
    {
        $this->config['shortcode'] = 'pb_articlelist';
        $this->config['name'] = JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST');
        $this->config['cat'] = JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_EXTRA');
        $this->config['icon'] = "icon-article-list";
        $this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_DESCRIPTION");

        $this->config['exception'] = array(
            'default_content'  => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST'),
            'data-modal-title' => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST'),
        );
    }

    /**
     * DEFINE setting options of shortcode
     *
     * @return mixed
     */
    function backend_element_items()
    {
        $articleListSource = JSNPbArticleListHelper::getArticleListSource();

        $articleListCategories = JSNPbArticleListHelper::getArticleListCategories();
        $articleListK2Categories = JSNPbArticleListHelper::getArticleListK2Categories();
        $articleListEasyCategories = JSNPbArticleListHelper::getArticleListEasyCategories();

        $articleListAuthors = JSNPbArticleListHelper::getArticleListAuthors();
        $articleListK2Authors = JSNPbArticleListHelper::getArticleListK2Authors();
        $articleListEasyAuthors = JSNPbArticleListHelper::getArticleListEasyAuthors();

        $this->items = array(
            "content" => array(
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE"),
                    "id"      => "el_title",
                    "type"    => "text_field",
                    "class"   => "jsn-input-large-fluid",
                    "std"     => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_ELEMENT_TITLE_STD'),
                    "role"    => "title",
                    "tooltip" => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_TOOLTIP")
                ),
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_TITLE"),
                    "id"      => "articlelist_title",
                    "type"    => "text_field",
                    "class"   => "jsn-input-large-fluid",
                    "std"     => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_TITLE_STD'),
                ),
            	array(
            		"name"    => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_SHOW_TITLE"),
            		"id"      => "articlelist_show_title",
            		"type"    => "radio",
            		"std"     => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
            		"options" => JSNPagebuilderHelpersType::getYesNoQuestion(),
            		),            		
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_CONTENT_SOURCE"),
                    "id"         => "articlelist_source",
                    "type"       => "select",
                    "std"        => JSNPagebuilderHelpersType::getFirstOption($articleListSource),
                    "options"    => $articleListSource,
                    "has_depend" => "1",
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SELECT_CATEGORY"),
                    "id"         => "articlelist_filter_categories",
                    "type"       => "select",
                    "class"      => "jsn-input-large-fluid jsn-articlelist-multiple-select",
                    "multiple"   => "multiple",
                    "std"        => JSNPagebuilderHelpersType::getFirstOption($articleListCategories),
                    "options"    => $articleListCategories,
                    'dependency' => array('articlelist_source', '=', 'joomla_article'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SELECT_CATEGORY"),
                    "id"         => "articlelist_filter_k2_categories",
                    "type"       => "select",
                    "class"      => "jsn-input-large-fluid jsn-articlelist-multiple-select",
                    "multiple"   => "multiple",
                    "std"        => JSNPagebuilderHelpersType::getFirstOption($articleListK2Categories),
                    "options"    => $articleListK2Categories,
                    'dependency' => array('articlelist_source', '=', 'k2_article'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SELECT_CATEGORY"),
                    "id"         => "articlelist_filter_easy_categories",
                    "type"       => "select",
                    "class"      => "jsn-input-large-fluid jsn-articlelist-multiple-select",
                    "multiple"   => "multiple",
                    "std"        => JSNPagebuilderHelpersType::getFirstOption($articleListEasyCategories),
                    "options"    => $articleListEasyCategories,
                    'dependency' => array('articlelist_source', '=', 'easy_article'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SELECT_AUTHOR"),
                    "id"         => "articlelist_filter_authors",
                    "type"       => "select",
                    "class"      => "jsn-input-large-fluid jsn-articlelist-multiple-select",
                    "multiple"   => "multiple",
                    "std"        => '',
                    "options"    => $articleListAuthors,
                    'dependency' => array('articlelist_source', '=', 'joomla_article'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SELECT_AUTHOR"),
                    "id"         => "articlelist_filter_k2_authors",
                    "type"       => "select",
                    "class"      => "jsn-input-large-fluid jsn-articlelist-multiple-select",
                    "multiple"   => "multiple",
                    "std"        => '',
                    "options"    => $articleListK2Authors,
                    'dependency' => array('articlelist_source', '=', 'k2_article'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SELECT_AUTHOR"),
                    "id"         => "articlelist_filter_easy_authors",
                    "type"       => "select",
                    "class"      => "jsn-input-large-fluid jsn-articlelist-multiple-select",
                    "multiple"   => "multiple",
                    "std"        => '',
                    "options"    => $articleListEasyAuthors,
                    'dependency' => array('articlelist_source', '=', 'easy_article'),
                ),
                array(
                    "name" => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_NUMBER_OF_DISPLAY_ITEMS"),
                    "id"   => "articlelist_amount",
                    "type" => "text_number",
                    "std"  => 5,
                ),
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_ARTICLE_FIELD_TO_ORDER_BY"),
                    "id"      => "articlelist_sort_by",
                    "type"    => "select",
                    "std"     => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getArticleListSortBy()),
                    "options" => JSNPbArticleListHelper::getArticleListSortBy(),
                ),
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_ORDERING_DIRECTION"),
                    "id"      => "articlelist_sort_order",
                    "type"    => "select",
                    "std"     => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getArticleListSortOrder()),
                    "options" => JSNPbArticleListHelper::getArticleListSortOrder(),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_DATE_FILTERING"),
                    "id"         => "articlelist_filter_date",
                    "type"       => "select",
                    "std"        => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getDateFilterType()),
                    "options"    => JSNPbArticleListHelper::getDateFilterType(),
                    "has_depend" => "1",
                ),
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_DATE_FIELD"),
                    "id"      => "articlelist_date_field",
                    "type"    => "select",
                    "std"     => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getDateFieldType()),
                    "options" => JSNPbArticleListHelper::getDateFieldType(),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_START_DATE_RANGE"),
                    "id"         => "articlelist_range_date_start",
                    "type"       => "text_field",
                    "dependency" => array('articlelist_filter_date', '=', 'range'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_TO_DATE"),
                    "id"         => "articlelist_range_date_end",
                    "type"       => "text_field",
                    "dependency" => array('articlelist_filter_date', '=', 'range'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_RELATOVE_DATE"),
                    "id"         => "articlelist_relative_date",
                    "type"       => "text_number",
                    "std"        => 30,
                    "dependency" => array('articlelist_filter_date', '=', 'relative'),
                ),
            ),
            'styling' => array(
                array(
                    'type' => 'preview',
                ),
                array(
                    "name"       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_CHOOSE_LAYOUT'),
                    "id"         => 'articlelist_layout',
                    "type"       => 'radio',
                    "label_type" => 'image',
                    "dimension"  => array(40, 40),
                    "std"        => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getArticleListLayout()),
                    "options"    => JSNPbArticleListHelper::getArticleListLayout(),
                    "has_depend" => '1'
                ),
                // First item
                array(
                    "name"            => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_FIRST_ITEM'),
                    "container_class" => "articlelist-style-label",
                    "type"            => "field_set_label",
                    "dependency"      => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_DESCRIPTION'),
                    'id'         => 'articlelist_show_first_description',
                    'type'       => 'radio',
                    'std'        => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options'    => JSNPagebuilderHelpersType::getYesNoQuestion(),
                    "dependency" => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_DESCRIPTION_WORD_LIMITS'),
                    'id'         => 'articlelist_first_description_limit',
                    'type'       => 'text_number',
                    'std'        => 300,
                    "dependency" => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_THUMBNAIL'),
                    'id'         => 'articlelist_show_first_thumbnail',
                    'type'       => 'radio',
                    'std'        => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options'    => JSNPagebuilderHelpersType::getYesNoQuestion(),
                    'dependency' => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_THUMBNAIL_TYPE'),
                    'id'         => 'articlelist_first_thumbnail_type',
                    'type'       => 'select',
                    "std"        => 'custom',
                    "options"    => array(
                        'custom'   => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_CUSTOM'),
                        'original' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ORIGINAL'),
                    ),
                    'dependency' => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                array(
                    'name'            => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_THUMBNAIL_DIMENSION'),
                    'id'              => 'articlelist_first_thumbnail_dimension',
                    'container_class' => 'combo-group',
                    'type'            => 'dimension',
                    'extended_ids'    => array('articlelist_first_thumbnail_dimension_width', 'articlelist_first_thumbnail_dimension_height'),
                    'articlelist_first_thumbnail_dimension_width'  => array('std' => '320'),
                    'articlelist_first_thumbnail_dimension_height' => array('std' => '180'),
                ),
                array(
                    "name"       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_META_DATA'),
                    "id"         => 'articlelist_show_first_meta_data',
                    "type"       => "checkbox",
                    "class"      => "checkbox inline",
                    'std'        => JSNPbArticleListHelper::getArticleDefaultMetaDataType(),
                    'options'    => JSNPbArticleListHelper::getArticleMetaDataType(),
                    'dependency' => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                array(
                    'name'       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_READMORE"),
                    'id'         => 'articlelist_show_read_more_first',
                    'type'       => 'radio',
                    'std'        => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options'    => JSNPagebuilderHelpersType::getYesNoQuestion(),
                    'dependency' => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                // List item
                array(
                    "name"            => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_ITEM_LIST'),
                    "container_class" => "articlelist-style-label",
                    "type"            => "field_set_label",
                ),
                array(
                    "name"       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_LIST_STYLE'),
                    "id"         => "articlelist_list_style",
                    "type"       => "select",
                    "std"        => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getArticleListStyle()),
                    "options"    => JSNPbArticleListHelper::getArticleListStyle(),
                    "has_depend" => '1',
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_THUMBNAIL_TYPE'),
                    'id'         => 'articlelist_thumbnail_type',
                    'type'       => 'select',
                    "std"        => 'custom',
                    "options"    => array(
                        'custom'   => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_CUSTOM'),
                        'original' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ORIGINAL'),
                    ),
                    'dependency' => array('articlelist_list_style', '=', JSNPbArticleListHelper::PB_ARTICLE_LIST_STYLE_THUMBNAIL),
                ),
                array(
                    'name'            => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_THUMBNAIL_DIMENSION'),
                    'id'              => 'articlelist_thumbnail_dimension',
                    'container_class' => 'combo-group',
                    'type'            => 'dimension',
                    'extended_ids'    => array('articlelist_thumbnail_dimension_width', 'articlelist_thumbnail_dimension_height'),
                    'articlelist_thumbnail_dimension_width'  => array('std' => '120'),
                    'articlelist_thumbnail_dimension_height' => array('std' => '90'),
                ),
                array(
                    "name"       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_UPLOAD_IMAGE'),
                    "id"         => "articlelist_list_image",
                    "type"       => "select_media",
                    'std'        => '',
                    'class'      => 'jsn-input-large-fluid',
                    "dependency" => array('articlelist_list_style', '=', JSNPbArticleListHelper::PB_ARTICLE_LIST_STYLE_IMAGE),
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_DESCRIPTION'),
                    'id'         => 'articlelist_show_description',
                    'type'       => 'radio',
                    'std'        => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options'    => JSNPagebuilderHelpersType::getYesNoQuestion(),
                    "has_depend" => '1'
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_DESCRIPTION_WORD_LIMITS'),
                    'id'         => 'articlelist_description_limit',
                    'type'       => 'text_number',
                    'std'        => 100,
                    "dependency" => array('articlelist_show_description', '=', JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES),
                ),
                array(
                    "name"    => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_META_DATA'),
                    "id"      => 'articlelist_show_meta_data',
                    "type"    => "checkbox",
                    'std'     => JSNPbArticleListHelper::getArticleDefaultMetaDataType(),
                    'options' => JSNPbArticleListHelper::getArticleMetaDataType(),
                    "class"   => "checkbox inline",
                ),
                array(
                    'name'    => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_READMORE"),
                    'id'      => 'articlelist_show_read_more',
                    'type'    => 'radio',
                    'std'     => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options' => JSNPagebuilderHelpersType::getYesNoQuestion(),
                ),
            ),
        );
    }

    /**
     * DEFINE setting options of shortcode
     *
     * @return mixed
     */
    function frontend_element_items()
    {
        $articleListSource = JSNPbArticleListHelper::getArticleListSource();
        $this->items = array(
            "content" => array(
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE"),
                    "id"      => "el_title",
                    "type"    => "text_field",
                    "class"   => "jsn-input-large-fluid",
                    "std"     => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_ELEMENT_TITLE_STD'),
                    "role"    => "title",
                    "tooltip" => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_ELEMENT_TITLE_TOOLTIP")
                ),
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_TITLE"),
                    "id"      => "articlelist_title",
                    "type"    => "text_field",
                    "class"   => "jsn-input-large-fluid",
                    "std"     => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_TITLE_TOOLTIP'),
                ),
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_TITLE"),
                    "id"      => "articlelist_show_title",
                    "type"    => "radio",
                    "std"     => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    "options" => JSNPagebuilderHelpersType::getYesNoQuestion(),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_CONTENT_SOURCE"),
                    "id"         => "articlelist_source",
                    "type"       => "select",
                    "std"        => JSNPagebuilderHelpersType::getFirstOption($articleListSource),
                    "options"    => $articleListSource,
                    "has_depend" => "1",
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SELECT_CATEGORY"),
                    "id"         => "articlelist_filter_categories",
                    "type"       => "select",
                    "class"      => "jsn-input-large-fluid jsn-articlelist-multiple-select",
                    "multiple"   => "multiple",
//                    "std"        => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getArticleListCategories()),
//                    "options"    => JSNPbArticleListHelper::getArticleListCategories(),
                    'dependency' => array('articlelist_source', '=', 'joomla_article'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SELECT_CATEGORY"),
                    "id"         => "articlelist_filter_k2_categories",
                    "type"       => "select",
                    "class"      => "jsn-input-large-fluid jsn-articlelist-multiple-select",
                    "multiple"   => "multiple",
//                    "std"        => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getArticleListK2Categories()),
//                    "options"    => JSNPbArticleListHelper::getArticleListK2Categories(),
                    'dependency' => array('articlelist_source', '=', 'k2_article'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SELECT_CATEGORY"),
                    "id"         => "articlelist_filter_easy_categories",
                    "type"       => "select",
                    "class"      => "jsn-input-large-fluid jsn-articlelist-multiple-select",
                    "multiple"   => "multiple",
//                    "std"        => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getArticleListEasyCategories()),
//                    "options"    => JSNPbArticleListHelper::getArticleListEasyCategories(),
                    'dependency' => array('articlelist_source', '=', 'easy_article'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SELECT_AUTHOR"),
                    "id"         => "articlelist_filter_authors",
                    "type"       => "select",
                    "class"      => "jsn-input-large-fluid jsn-articlelist-multiple-select",
                    "multiple"   => "multiple",
                    "std"        => '',
//                    "options"    => JSNPbArticleListHelper::getArticleListAuthors(),
                    'dependency' => array('articlelist_source', '=', 'joomla_article'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SELECT_AUTHOR"),
                    "id"         => "articlelist_filter_k2_authors",
                    "type"       => "select",
                    "class"      => "jsn-input-large-fluid jsn-articlelist-multiple-select",
                    "multiple"   => "multiple",
                    "std"        => '',
//                    "options"    => JSNPbArticleListHelper::getArticleListK2Authors(),
                    'dependency' => array('articlelist_source', '=', 'k2_article'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SELECT_AUTHOR"),
                    "id"         => "articlelist_filter_easy_authors",
                    "type"       => "select",
                    "class"      => "jsn-input-large-fluid jsn-articlelist-multiple-select",
                    "multiple"   => "multiple",
                    "std"        => '',
//                    "options"    => JSNPbArticleListHelper::getArticleListEasyAuthors(),
                    'dependency' => array('articlelist_source', '=', 'easy_article'),
                ),
                array(
                    "name" => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_NUMBER_OF_DISPLAY_ITEMS"),
                    "id"   => "articlelist_amount",
                    "type" => "text_number",
                    "std"  => 5,
                ),
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_ARTICLE_FIELD_TO_ORDER_BY"),
                    "id"      => "articlelist_sort_by",
                    "type"    => "select",
                    "std"     => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getArticleListSortBy()),
                    "options" => JSNPbArticleListHelper::getArticleListSortBy(),
                ),
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_ORDERING_DIRECTION"),
                    "id"      => "articlelist_sort_order",
                    "type"    => "select",
                    "std"     => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getArticleListSortOrder()),
                    "options" => JSNPbArticleListHelper::getArticleListSortOrder(),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_DATE_FILTERING"),
                    "id"         => "articlelist_filter_date",
                    "type"       => "select",
                    "std"        => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getDateFilterType()),
                    "options"    => JSNPbArticleListHelper::getDateFilterType(),
                    "has_depend" => "1",
                ),
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_DATE_FIELD"),
                    "id"      => "articlelist_date_field",
                    "type"    => "select",
                    "std"     => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getDateFieldType()),
                    "options" => JSNPbArticleListHelper::getDateFieldType(),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_START_DATE_RANGE"),
                    "id"         => "articlelist_range_date_start",
                    "type"       => "text_field",
                    "dependency" => array('articlelist_filter_date', '=', 'range'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_TO_DATE"),
                    "id"         => "articlelist_range_date_end",
                    "type"       => "text_field",
                    "dependency" => array('articlelist_filter_date', '=', 'range'),
                ),
                array(
                    "name"       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_RELATOVE_DATE"),
                    "id"         => "articlelist_relative_date",
                    "type"       => "text_number",
                    "std"        => 30,
                    "dependency" => array('articlelist_filter_date', '=', 'relative'),
                ),
            ),
            'styling' => array(
                array(
                    'type' => 'preview',
                ),
                array(
                    "name"       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_CHOOSE_LAYOUT'),
                    "id"         => 'articlelist_layout',
                    "type"       => 'radio',
                    "label_type" => 'image',
                    "dimension"  => array(40, 40),
                    "std"        => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getArticleListLayout()),
                    "options"    => JSNPbArticleListHelper::getArticleListLayout(),
                    "has_depend" => '1'
                ),
                // First item
                array(
                    "name"            => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_FIRST_ITEM'),
                    "container_class" => "articlelist-style-label",
                    "type"            => "field_set_label",
                    "dependency"      => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_DESCRIPTION'),
                    'id'         => 'articlelist_show_first_description',
                    'type'       => 'radio',
                    'std'        => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options'    => JSNPagebuilderHelpersType::getYesNoQuestion(),
                    "dependency" => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_DESCRIPTION_WORD_LIMITS'),
                    'id'         => 'articlelist_first_description_limit',
                    'type'       => 'text_number',
                    'std'        => 300,
                    "dependency" => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_THUMBNAIL'),
                    'id'         => 'articlelist_show_first_thumbnail',
                    'type'       => 'radio',
                    'std'        => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options'    => JSNPagebuilderHelpersType::getYesNoQuestion(),
                    'dependency' => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_THUMBNAIL_TYPE'),
                    'id'         => 'articlelist_first_thumbnail_type',
                    'type'       => 'select',
                    "std"        => 'custom',
                    "options"    => array(
                        'custom'   => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_CUSTOM'),
                        'original' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ORIGINAL'),
                    ),
                    'dependency' => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                array(
                    'name'            => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_THUMBNAIL_DIMENSION'),
                    'id'              => 'articlelist_first_thumbnail_dimension',
                    'container_class' => 'combo-group',
                    'type'            => 'dimension',
                    'extended_ids'    => array('articlelist_first_thumbnail_dimension_width', 'articlelist_first_thumbnail_dimension_height'),
                    'articlelist_first_thumbnail_dimension_width'  => array('std' => '320'),
                    'articlelist_first_thumbnail_dimension_height' => array('std' => '180'),
                ),
                array(
                    "name"       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_META_DATA'),
                    "id"         => 'articlelist_show_first_meta_data',
                    "type"       => "checkbox",
                    "class"      => "checkbox inline",
                    'std'        => JSNPbArticleListHelper::getArticleDefaultMetaDataType(),
                    'options'    => JSNPbArticleListHelper::getArticleMetaDataType(),
                    'dependency' => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                array(
                    'name'       => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_READMORE"),
                    'id'         => 'articlelist_show_read_more_first',
                    'type'       => 'radio',
                    'std'        => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options'    => JSNPagebuilderHelpersType::getYesNoQuestion(),
                    'dependency' => array('articlelist_layout', '!=', JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT),
                ),
                // List item
                array(
                    "name"            => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_ITEM_LIST'),
                    "container_class" => "articlelist-style-label",
                    "type"            => "field_set_label",
                ),
                array(
                    "name"       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_LIST_STYLE'),
                    "id"         => "articlelist_list_style",
                    "type"       => "select",
                    "std"        => JSNPagebuilderHelpersType::getFirstOption(JSNPbArticleListHelper::getArticleListStyle()),
                    "options"    => JSNPbArticleListHelper::getArticleListStyle(),
                    "has_depend" => '1',
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_THUMBNAIL_TYPE'),
                    'id'         => 'articlelist_thumbnail_type',
                    'type'       => 'select',
                    "std"        => 'custom',
                    "options"    => array(
                        'custom'   => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_CUSTOM'),
                        'original' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ORIGINAL'),
                    ),
                    'dependency' => array('articlelist_list_style', '=', JSNPbArticleListHelper::PB_ARTICLE_LIST_STYLE_THUMBNAIL),
                ),
                array(
                    'name'            => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_THUMBNAIL_DIMENSION'),
                    'id'              => 'articlelist_thumbnail_dimension',
                    'container_class' => 'combo-group',
                    'type'            => 'dimension',
                    'extended_ids'    => array('articlelist_thumbnail_dimension_width', 'articlelist_thumbnail_dimension_height'),
                    'articlelist_thumbnail_dimension_width'  => array('std' => '120'),
                    'articlelist_thumbnail_dimension_height' => array('std' => '90'),
                ),
                array(
                    "name"       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_UPLOAD_IMAGE'),
                    "id"         => "articlelist_list_image",
                    "type"       => "select_media",
                    'std'        => '',
                    'class'      => 'jsn-input-large-fluid',
                    "dependency" => array('articlelist_list_style', '=', JSNPbArticleListHelper::PB_ARTICLE_LIST_STYLE_IMAGE),
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_DESCRIPTION'),
                    'id'         => 'articlelist_show_description',
                    'type'       => 'radio',
                    'std'        => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options'    => JSNPagebuilderHelpersType::getYesNoQuestion(),
                    "has_depend" => '1'
                ),
                array(
                    'name'       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_DESCRIPTION_WORD_LIMITS'),
                    'id'         => 'articlelist_description_limit',
                    'type'       => 'text_number',
                    'std'        => 100,
                    "dependency" => array('articlelist_show_description', '=', JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES),
                ),
                array(
                    "name"    => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_META_DATA'),
                    "id"      => 'articlelist_show_meta_data',
                    "type"    => "checkbox",
                    'std'     => JSNPbArticleListHelper::getArticleDefaultMetaDataType(),
                    'options' => JSNPbArticleListHelper::getArticleMetaDataType(),
                    "class"   => "checkbox inline",
                ),
                array(
                    'name'    => JText::_("JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_SHOW_READMORE"),
                    'id'      => 'articlelist_show_read_more',
                    'type'    => 'radio',
                    'std'     => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options' => JSNPagebuilderHelpersType::getYesNoQuestion(),
                ),
            ),
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
        $document->addStyleSheet(JSNPB_ELEMENT_URL . '/articlelist/assets/css/articlelist.css', 'text/css');

        $arr_params = JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $attributes);
        extract($arr_params);
        $html_element = '';
        $articles = JSNPbArticleListHelper::getArticles($attributes);
        if (count($articles) == 0) {
            $html_element = "<div class='alert alert-warning' style='width: 100%;'>" . JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_FILTER_HAS_NO_RESULT') . "</div>";
            return $this->element_wrapper($html_element, $arr_params);
        }
        $app = JFactory::getApplication();
        $isAdmin = $app->isAdmin() ? true : false;
        $adminPreviewClass =  $isAdmin ? "in-admin" : "";
        $url_pattern = '/^(http|https)/';

        $html_element .= "<div class='pb-articlelist-wrapper " . str_replace('_', '-', $arr_params['articlelist_layout']) . " " . $adminPreviewClass . "'>";

        if ($arr_params['articlelist_show_title'] == JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES)
        {	
		$html_element .= "<div class='pb-articlelist-title'><h3 class='pb-articlelist-head'>{$arr_params['articlelist_title']}</h3></div>";
        }
        

        $html_element .= "<div class='row'>";
        if ($arr_params['articlelist_layout'] != JSNPbArticleListHelper::PB_ARTICLE_LIST_LAYOUT) {
            if ($arr_params['articlelist_layout'] == JSNPbArticleListHelper::PB_ARTICLE_HORIZONTAL_LAYOUT) {
                $html_element .= "<div class='col-md-6 col-sm-6 col-xs-6 pb-articlelist-first'>";
            } else if ($arr_params['articlelist_layout'] == JSNPbArticleListHelper::PB_ARTICLE_VERTICAL_LAYOUT) {
                $html_element .= "<div class='col-md-12 col-sm-12 col-xs-12 pb-articlelist-first'>";
            } else {
                $html_element .= "<div class='pb-articlelist-first'>";
            }
            // Display with first item
            $articleFirst = array_shift($articles);
            // Default thumbnail dimension
            $thumbnailFirstWidth = ($arr_params['articlelist_first_thumbnail_type'] == 'custom') ? ('width: ' . (int) $arr_params['articlelist_first_thumbnail_dimension_width'] . 'px;') : 'width: 100%; max-width: 100%;';
            $thumbnailFirstHeight = ($arr_params['articlelist_first_thumbnail_type'] == 'custom') ? ('height: ' . (int) $arr_params['articlelist_first_thumbnail_dimension_height'] . 'px;') : 'height: 100%; max-height: 100%;';
            $firstMetaData = explode("__#__", $arr_params['articlelist_show_first_meta_data']);
            $firstUrl = $isAdmin ? "#" : $articleFirst['direct_url'];

            if ($arr_params['articlelist_show_first_thumbnail'] == JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES) {
                $imageFirstUrl = '';
                $imageFirstAlt = "No image";
                if (isset($articleFirst['images'])) {
                    $articleFirstImages = json_decode($articleFirst['images'], true);
                    if (isset($articleFirstImages['image_intro']) && $articleFirstImages['image_intro'] != "") {
                        $imageFirstUrl = $articleFirstImages['image_intro'];
                        $imageFirstAlt = $articleFirstImages['image_intro_alt'];
                    } else if (isset($articleFirstImages['image_fulltext']) && $articleFirstImages['image_fulltext'] != "") {
                        $imageFirstUrl = $articleFirstImages['image_fulltext'];
                        $imageFirstAlt = $articleFirstImages['image_fulltext_alt'];
                    }
                }
				if ($imageFirstUrl != '')
				{
                    $imageFirstUrl = preg_match($url_pattern, $imageFirstUrl) !== 0 ? $imageFirstUrl : JUri::root() . $imageFirstUrl;
					$html_element .= "<div class='first-thumbnail'>";
                    $html_element .= "<a href='{$firstUrl}'><img src='{$imageFirstUrl}' alt='{$imageFirstAlt}' style='{$thumbnailFirstHeight} {$thumbnailFirstWidth}' /></a>";
					$html_element .= "</div>";
				}
                
            }
            $html_element .= "<div class='title'><a class='title' href='{$firstUrl}'>{$articleFirst['title']}</a></div>";
            if (count($firstMetaData) > 1) {
                $html_element .= "<div class='meta-data-wrapper muted'>";
                if (in_array(JSNPbArticleListHelper::PB_ARTICLE_META_DATA_AUTHOR, $firstMetaData)) {
                    $html_element .= "<div class='created-by'>" . JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_WRITTEN_BY') . ": <span>{$articleFirst['author']}</span></div>";
                }
                if (in_array(JSNPbArticleListHelper::PB_ARTICLE_META_DATA_DATE_PUBLISHED, $firstMetaData)) {
                    $_publishData = new DateTime($articleFirst['publish_up']);
                    $html_element .= "<div class='published'>" . JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_PUBLISHED') . ": " . $_publishData->format('d M Y') . "</div>";
                }
                if (in_array(JSNPbArticleListHelper::PB_ARTICLE_META_DATA_CATEGORY, $firstMetaData)) {
                    $urlCat = $isAdmin ? "#" : $articleFirst['category_direct_url'];
                    $html_element .= "<div class='category'>" . JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_CATEGORY') . ": <a href='{$urlCat}'>{$articleFirst['category_title']}</a></div>";
                }
                if (in_array(JSNPbArticleListHelper::PB_ARTICLE_META_DATA_HITS, $firstMetaData)) {
                    $html_element .= "<div class='hits'>" . JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HITS') . ": {$articleFirst['hits']}</div>";
                }
                $html_element .= "</div>";
            }

            if ($attributes['articlelist_show_first_description'] == JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES) {
                preg_match_all(JSNPbArticleListHelper::PB_SHORTCODE_SYNTAX, $articleFirst['introtext'], $out);
                if (!count($out[0])) {
                    $_firstIntroText = $articleFirst['introtext'];
                } else {
                    $shortCodeRegex = JSNPagebuilderHelpersShortcode::getShortcodeRegex();
                    $_firstIntroText = JSNPbArticleListHelper::removeShortCode($articleFirst['introtext'], $shortCodeRegex);
                }
                $html_element .= "<div class='articlelist-first-thumbnail'><p>" . JSNPbArticleListHelper::wordLimiter($_firstIntroText, (int) $attributes['articlelist_first_description_limit']) . "</p></div>";
            }

			if ($arr_params['articlelist_show_read_more_first'] == JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES) {
                $html_element .= "<div class='articlelist-item-read-more'><a href='{$firstUrl}'>" . JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_READMORE') . "</a></div>";
            }
			
            $html_element .= "</div>";
        }
        if ($arr_params['articlelist_layout'] == JSNPbArticleListHelper::PB_ARTICLE_HORIZONTAL_LAYOUT) {
            $html_element .= "<div class='col-md-6 col-sm-6 col-xs-6 pb-articlelist-list'>";
        } else {
            $html_element .= "<div class='col-md-12 col-sm-12 col-xs-12 pb-articlelist-list'>";
        } 
        // Display list

        if ($isOrderList = in_array($arr_params['articlelist_list_style'], JSNPbArticleListHelper::getArticleOlListStyle())) {
            $html_element .= "<ol type='{$arr_params['articlelist_list_style']}'>";
        } else {
            switch ($arr_params['articlelist_list_style']) {
                case JSNPbArticleListHelper::PB_ARTICLE_LIST_STYLE_IMAGE :
                    if (getimagesize(JUri::root() . $attributes['articlelist_list_image']) !== false) {
                        $styleUl = "list-style-image: url(\"" . JUri::root() . $attributes['articlelist_list_image'] . "\")";
                    } else {
                        $styleUl = "list-style-image: url(\"" . JUri::root() . "plugins/jsnpagebuilder/defaultelements/articlelist/assets/img/10x10.png" . "\")";
                    }
                    break;
                case JSNPbArticleListHelper::PB_ARTICLE_LIST_STYLE_THUMBNAIL :
                    $styleUl = "list-style-type: none";
                    break;
                default :
                    $styleUl = "list-style-type:{$arr_params['articlelist_list_style']}";
                    break;
            }
            $html_element .= "<ul class='list-style-type-{$arr_params['articlelist_list_style']}' style='{$styleUl}'>";
        }

        // Default thumbnail dimension
        $thumbnailWidth = ($arr_params['articlelist_thumbnail_type'] == 'custom') ? ('width: ' . (int) $arr_params['articlelist_thumbnail_dimension_width'] . 'px;') : 'width: 100%; max-width: 100%;';
        $thumbnailHeight = ($arr_params['articlelist_thumbnail_type'] == 'custom') ? ('height: ' . (int) $arr_params['articlelist_thumbnail_dimension_height'] . 'px;') : 'height: 100%; max-height: 100%;';

        $metaData = explode("__#__", $arr_params['articlelist_show_meta_data']);

        foreach ($articles as $_article) {
            $url = $isAdmin ? "#" : $_article['direct_url'];
            $html_element .= "<li class='articlelist-item'>";
            if ($arr_params['articlelist_list_style'] == JSNPbArticleListHelper::PB_ARTICLE_LIST_STYLE_THUMBNAIL) {
                $bgImageItemUrl = "";
                if (isset($_article['images'])) {
                    $articleImages = json_decode($_article['images'], true);
                    if (isset($articleImages['image_intro']) && $articleImages['image_intro'] != "") {
                        $bgImageItemUrl = $articleImages['image_intro'];
                    } else if (isset($articleImages['image_fulltext']) && $articleImages['image_fulltext'] != "") {
                        $bgImageItemUrl = $articleImages['image_fulltext'];
                    }
                }
                if ($bgImageItemUrl != '')
                {
                    $bgImageItemUrl = preg_match($url_pattern, $bgImageItemUrl) !== 0 ? $bgImageItemUrl : JUri::root() . $bgImageItemUrl;
                    $html_element .= "<div class='articlelist-item-thumbnail'><a href='{$url}'><img src='{$bgImageItemUrl}' style='{$thumbnailHeight} {$thumbnailWidth}'></a></div>";
                }
            }

            $html_element .= "<div class='articlelist-item-content'>";
            $html_element .= "<div class='title'><a class='title' href='{$url}'>{$_article['title']}</a></div>";

            if (count($metaData) > 1) {
                $html_element .= "<div class='meta-data-wrapper muted'>";
                if (in_array(JSNPbArticleListHelper::PB_ARTICLE_META_DATA_AUTHOR, $metaData)) {
                    $html_element .= "<div class='created-by'>" . JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_WRITTEN_BY') . ": <span>{$_article['author']}</span></div>";
                }
                if (in_array(JSNPbArticleListHelper::PB_ARTICLE_META_DATA_DATE_PUBLISHED, $metaData)) {
                    $_publishData = new DateTime($_article['publish_up']);
                    $html_element .= "<div class='published'>" . JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_PUBLISHED') . ": " . $_publishData->format('d M Y') . "</div>";
                }
                if (in_array(JSNPbArticleListHelper::PB_ARTICLE_META_DATA_CATEGORY, $metaData)) {
                    $urlCat = $isAdmin ? "#" : $_article['category_direct_url'];
                    $html_element .= "<div class='category'>" . JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_CATEGORY') . ": <a href='{$urlCat}'>{$_article['category_title']}</a></div>";
                }
                if (in_array(JSNPbArticleListHelper::PB_ARTICLE_META_DATA_HITS, $metaData)) {
                    $html_element .= "<div class='hits'>" . JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HITS') . ": {$_article['hits']}</div>";
                }
                $html_element .= "</div>";
            }

            if ($attributes['articlelist_show_description'] == JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES) {
                preg_match_all(JSNPbArticleListHelper::PB_SHORTCODE_SYNTAX, $_article['introtext'], $out);
                if (!count($out[0])) {
                    $_introText = $_article['introtext'];
                } else {
                    $shortCodeRegex = JSNPagebuilderHelpersShortcode::getShortcodeRegex();
                    $_introText = JSNPbArticleListHelper::removeShortCode($_article['introtext'], $shortCodeRegex);
                }
                $html_element .= "<div class='articlelist-item-description'><p>" . JSNPbArticleListHelper::wordLimiter($_introText, (int) $attributes['articlelist_description_limit']) . "</p></div>";
            }

			if ($arr_params['articlelist_show_read_more'] == JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES) {
                $html_element .= "<div class='articlelist-item-read-more'><a href='{$url}'>" . JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_READMORE') . "</a></div>";
            }
			
            $html_element .= "</div></li>";
        }
        $html_element .= $isOrderList ? "</ol>" : "</ul>";
        $html_element .= "</div></div>";
        $html_element .= "</div>";

        return $this->element_wrapper($html_element, $arr_params);
    }
}
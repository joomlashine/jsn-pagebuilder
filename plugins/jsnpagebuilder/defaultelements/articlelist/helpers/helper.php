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

include_once JPATH_PLUGINS . '/jsnpagebuilder/defaultelements/articlelist/models/categories.php';
include_once JPATH_PLUGINS . '/jsnpagebuilder/defaultelements/articlelist/models/articles.php';
include_once JPATH_PLUGINS . '/jsnpagebuilder/defaultelements/articlelist/models/authors.php';

/**
 * Helper class for article list element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbArticleListHelper
{
    const PB_ARTICLE_LIST_LAYOUT = "layout_list";
    const PB_ARTICLE_HORIZONTAL_LAYOUT = "layout_horizontal";
    const PB_ARTICLE_VERTICAL_LAYOUT = "layout_vertical";

    const PB_ARTICLE_LIST_STYLE_NONE = "none";
    const PB_ARTICLE_LIST_STYLE_DISC = "disc";
    const PB_ARTICLE_LIST_STYLE_CIRCLE = "circle";
    const PB_ARTICLE_LIST_STYLE_SQUARE = "square";
    const PB_ARTICLE_LIST_STYLE_NUMBER = "1";
    const PB_ARTICLE_LIST_STYLE_UP_LETTERS = "A";
    const PB_ARTICLE_LIST_STYLE_LOW_LETTERS = "a";
    const PB_ARTICLE_LIST_STYLE_UP_NUMBERS = "I";
    const PB_ARTICLE_LIST_STYLE_LOW_NUMBERS = "i";
    const PB_ARTICLE_LIST_STYLE_IMAGE = "image";
    const PB_ARTICLE_LIST_STYLE_THUMBNAIL = "thumbnail";

    const PB_ARTICLE_META_DATA_AUTHOR = "article_author";
    const PB_ARTICLE_META_DATA_DATE_PUBLISHED = "article_date_published";
    const PB_ARTICLE_META_DATA_CATEGORY = "article_category";
    const PB_ARTICLE_META_DATA_HITS = "article_hits";

    const PB_ARTICLE_SORT_BY_ORDERING = 'a.ordering';
    const PB_ARTICLE_SORT_BY_FP_ORDERING = 'fp.ordering';
    const PB_ARTICLE_SORT_BY_HITS = 'a.hits';
    const PB_ARTICLE_SORT_BY_TITLE = 'a.title';
    const PB_ARTICLE_SORT_BY_ID = 'a.id';
    const PB_ARTICLE_SORT_BY_ALIAS = 'a.alias';
    const PB_ARTICLE_SORT_BY_CREATED = 'a.created';
    const PB_ARTICLE_SORT_BY_MODIFIED = 'modified';
    const PB_ARTICLE_SORT_BY_PUBLISH_UP = 'publish_up';
    const PB_ARTICLE_SORT_BY_PUBLISH_DOWN = 'a.publish_down';

    const PB_ARTICLE_SORT_ORDER_ASC = "ASC";
    const PB_ARTICLE_SORT_ORDER_DESC = "DESC";

    const PB_ARTICLE_SOURCE_JOOMLA = "joomla_article";
    const PB_ARTICLE_SOURCE_K2 = "k2_article";
    const PB_ARTICLE_SOURCE_EASY = "easy_article";

    const PB_SHORTCODE_SYNTAX = '#\[(\[?)(pb_row)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)#';

    public static function loadK2Libraries()
    {
        include_once JPATH_PLUGINS . '/jsnpagebuilder/defaultelements/articlelist/models/k2categories.php';
        include_once JPATH_PLUGINS . '/jsnpagebuilder/defaultelements/articlelist/models/k2articles.php';
        include_once JPATH_ROOT . '/components/com_k2/helpers/route.php';
    }

    public static function loadEasyblogLibraries()
    {
        include_once JPATH_PLUGINS . '/jsnpagebuilder/defaultelements/articlelist/models/easyblogcategories.php';
        include_once JPATH_PLUGINS . '/jsnpagebuilder/defaultelements/articlelist/models/easyblogarticles.php';
    }

    /**
     * @return bool
     */
    public static function checkOldVersionEasyBlog()
    {
        if (file_exists(JPATH_ROOT . '/administrator/components/com_easyblog/includes/easyblog.php')) {
            include_once JPATH_ROOT . '/administrator/components/com_easyblog/includes/easyblog.php';
        }
		
        if (file_exists(JPATH_ROOT . "/components/com_easyblog/helpers/helper.php") &&  ! class_exists( 'EasyBlogHelper' )) {
            include_once JPATH_ROOT . "/components/com_easyblog/helpers/helper.php";
        }
        $local = EasyBlogHelper::getLocalVersion();
        $version = $local instanceof SimpleXMLElement ? $local->__toString() : $local;
        return version_compare($version, '5', '<');
    }

    /**
     * @return array
     */
    public static function getArticleListSource()
    {
        $articleListSource = array(
            self::PB_ARTICLE_SOURCE_JOOMLA => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_JOOMLA_ARTICLE'),
        );
        if (JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_k2")) {
            self::loadK2Libraries();
            $articleListSource[self::PB_ARTICLE_SOURCE_K2] = JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_K2');
        } else {
            $articleListSource[self::PB_ARTICLE_SOURCE_K2] = array('disable' => true, 'text' => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_K2_NOT_INSTALLED_OR_ENABLE_YET'));
        }
        if (JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_easyblog")) {
            self::loadEasyblogLibraries();
            $articleListSource[self::PB_ARTICLE_SOURCE_EASY] = JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_EASYBLOG');
        } else {
            $articleListSource[self::PB_ARTICLE_SOURCE_EASY] = array('disable' => true, 'text' => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_EASYBLOG_NOT_INSTALLED_OR_ENABLE_YET'));
        }

        return $articleListSource;
    }

    /**
     * @return array
     */
    public static function getArticleListSortBy()
    {
        return array(
            self::PB_ARTICLE_SORT_BY_ORDERING     => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_ARTICLE_MANAGER_ORDER'),
            self::PB_ARTICLE_SORT_BY_FP_ORDERING  => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_FEATURED_ARTICLES_ORDER'),
            self::PB_ARTICLE_SORT_BY_HITS         => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HITS'),
            self::PB_ARTICLE_SORT_BY_TITLE        => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_TITLE'),
            self::PB_ARTICLE_SORT_BY_ID           => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_ID'),
            self::PB_ARTICLE_SORT_BY_ALIAS        => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_ALIAS'),
            self::PB_ARTICLE_SORT_BY_CREATED      => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_CREATED_DATE'),
            self::PB_ARTICLE_SORT_BY_MODIFIED     => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_MODIFIED_DATE'),
            self::PB_ARTICLE_SORT_BY_PUBLISH_UP   => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_START_PUBLISHING_DATE'),
            self::PB_ARTICLE_SORT_BY_PUBLISH_DOWN => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_FINISH_PUBLISHING_DATE'),
        );
    }

    /**
     * @return array
     */
    public static function getArticleListSortOrder()
    {
        return array(
            self::PB_ARTICLE_SORT_ORDER_ASC => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_ASCENDING'),
            self::PB_ARTICLE_SORT_ORDER_DESC => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_DESCENDING'),
        );
    }


    /**
     * @return array
     */
    public static function getArticleListStyle()
    {
        return array(
            self::PB_ARTICLE_LIST_STYLE_NONE        => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_NONE'),
            self::PB_ARTICLE_LIST_STYLE_DISC        => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_DISC'),
            self::PB_ARTICLE_LIST_STYLE_CIRCLE      => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_CIRCLE'),
            self::PB_ARTICLE_LIST_STYLE_SQUARE      => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_SQUARE'),
            self::PB_ARTICLE_LIST_STYLE_NUMBER      => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_NUMBER'),
            self::PB_ARTICLE_LIST_STYLE_UP_LETTERS  => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_UPPERCASE_LETTERS'),
            self::PB_ARTICLE_LIST_STYLE_LOW_LETTERS => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_LOWERCASE_LETTERS'),
            self::PB_ARTICLE_LIST_STYLE_UP_NUMBERS  => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_UPPERCASE_NUMBERS'),
            self::PB_ARTICLE_LIST_STYLE_LOW_NUMBERS => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_LOWERCASE_NUMBERS'),
            self::PB_ARTICLE_LIST_STYLE_IMAGE       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_IMAGE_ICON'),
            self::PB_ARTICLE_LIST_STYLE_THUMBNAIL   => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_THUMBNAIL_ARTICLE'),
        );
    }

    public static function getArticleOlListStyle()
    {
        return array(
            self::PB_ARTICLE_LIST_STYLE_NUMBER,
            self::PB_ARTICLE_LIST_STYLE_UP_LETTERS,
            self::PB_ARTICLE_LIST_STYLE_LOW_LETTERS,
            self::PB_ARTICLE_LIST_STYLE_UP_NUMBERS,
            self::PB_ARTICLE_LIST_STYLE_LOW_NUMBERS,
        );
    }

    /**
     * @return array
     */
    public static function getArticleMetaDataType()
    {
        return array(
            self::PB_ARTICLE_META_DATA_AUTHOR         => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_AUTHOR'),
            self::PB_ARTICLE_META_DATA_DATE_PUBLISHED => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_PUBLISHED'),
            self::PB_ARTICLE_META_DATA_CATEGORY       => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_CATEGORY'),
            self::PB_ARTICLE_META_DATA_HITS           => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HITS'),
        );
    }

    /**
     * @return string
     */
    public static function getArticleDefaultMetaDataType()
    {
        return implode("__#__", array(
            self::PB_ARTICLE_META_DATA_DATE_PUBLISHED,
            self::PB_ARTICLE_META_DATA_CATEGORY,
        ));
    }

    /**
     * @return array
     */
    public static function getArticleListLayout()
    {
        return array(
            self::PB_ARTICLE_LIST_LAYOUT       => array(JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_LIST'), "class" => "jsn-icon-formfields jsn-icon-list-basic"),
            self::PB_ARTICLE_VERTICAL_LAYOUT => array(JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_PRIMARY_SECONDARY_VERTICAL'), "class" => "jsn-icon-formfields jsn-icon-list-special-1"),
            self::PB_ARTICLE_HORIZONTAL_LAYOUT   => array(JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_PRIMARY_SECONDARY_HORIZONTAL'), "class" => "jsn-icon-formfields jsn-icon-list-special-2"),
        );
    }

    /**
     * @return array
     */
    public static function getArticleListCategories()
    {
        $categoryModel = new JSNPbCategoriesModel();
        $categories = $categoryModel->getCategories();

        $options = array();
        foreach ($categories as $_categoryId => $_category) {
            $options[(int) $_categoryId] = $_category['title'];
        }

        return $options;
    }

    /**
     * @return array
     */
    public static function getArticleListK2Categories()
    {
        if (!JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_k2")) {
            return array();
        }
        self::loadK2Libraries();
        $k2CategoryModel = new JSNPbK2CategoriesModel();
        $k2Categories = $k2CategoryModel->getData();

        $options = array();
        foreach ($k2Categories as $_key => $_value) {
            $options[$_value->id] = $_value->name;
        }

        return $options;
    }

    /**
     * @return array
     */
    public static function getArticleListEasyCategories()
    {
        if (!JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_easyblog")) {
            return array();
        }
        self::loadEasyblogLibraries();
        $easyCategoryModel = new JSNPbEasyblogCategoriesModel();
        $easyCategoies = $easyCategoryModel->getData(false);

        $options = array();
        foreach ($easyCategoies as $_key => $_value) {
            $options[$_value->id] = $_value->title;
        }

        return $options;
    }

    /**
     * @return array
     */
    public static function getArticleListAuthors()
    {
        $authorModel = new JSNPbAuthorsModel();
        $result = $authorModel->getAuthorsHaveArticle();

        $listAuthor = array();
        foreach ($result as $_key => $_value) {
            $listAuthor[$_key] = $_value['name'];
        }

        return $listAuthor;
    }

    /**
     * @return array
     */
    public static function getArticleListK2Authors()
    {
        if (!JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_k2")) {
            return array();
        }
        self::loadK2Libraries();
        $authorModel = new JSNPbAuthorsModel();
        $result = $authorModel->getAuthorsHaveArticle(self::PB_ARTICLE_SOURCE_K2);

        $listAuthor = array();
        foreach ($result as $_key => $_value) {
            $listAuthor[$_key] = $_value['name'];
        }

        return $listAuthor;
    }

    /**
     * @return array
     */
    public static function getArticleListEasyAuthors()
    {
        if (!JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_easyblog")) {
            return array();
        }
        self::loadEasyblogLibraries();
        $authorModel = new JSNPbAuthorsModel();
        $result = $authorModel->getAuthorsHaveArticle('easy_article');

        $listAuthor = array();
        foreach ($result as $_key => $_value) {
            $listAuthor[$_key] = $_value['name'];
        }

        return $listAuthor;
    }

    public static function getDateFilterType()
    {
        return array(
            'off'      => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_OFF'),
            'range'    => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_DATE_RANGER'),
            'relative' => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_RELATOVE_DATE'),
        );
    }

    public static function getDateFieldType()
    {
        return array(
            'a.created'    => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_CREATED_DATE'),
            'a.modified'   => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_MODIFIED_DATE'),
            'a.publish_up' => JText::_('JSN_PAGEBUILDER_ELEMENT_ARTICLELIST_HELPER_START_PUBLISHING_DATE'),
        );
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    public static function getArticles($attributes)
    {
        $articles = array();
        switch ($attributes['articlelist_source']) {
            case self::PB_ARTICLE_SOURCE_K2 :
                $k2ArticleModel = new JSNPbK2ArticlesModel();
                $result = $k2ArticleModel->jsnGetData($attributes);
                foreach ($result as $_key => $_value) {
                    $articles[$_key] = $_value;
                    $articles[$_key]['direct_url'] = JRoute::_(K2HelperRoute::getItemRoute($_value['id'], $_value['catid']));
                    $articles[$_key]['category_direct_url'] = JRoute::_(K2HelperRoute::getCategoryRoute($_value['catid']));

                    $articles[$_key]['category_title'] = $_value['category'];
                    $imageName = md5("Image" . $_value['id']);
                    if (JFile::exists(JPATH_ROOT  . '/media/k2/items/src/' . $imageName . '.jpg')) {
                        $images = array(
                            'image_intro'     => JRoute::_(JUri::root() . 'media/k2/items/cache/' . $imageName . '_XL.jpg'),
                            'image_intro_alt' => $_value['title'],
                        );
                        $articles[$_key]['images'] = json_encode($images);
                    }
                }
                break;
            case self::PB_ARTICLE_SOURCE_EASY :
                // Get version EasyBlog
                $attributes['is_old_version'] = self::checkOldVersionEasyBlog();
                if (!$attributes['is_old_version']) {
                    include_once JPATH_ROOT . '/administrator/components/com_easyblog/includes/post/post.php';
                }
                $easyArticleModel = new JSNPbEasyblogArticlesModel();
                $result = $easyArticleModel->jsnGetData($attributes);
                foreach ($result as $_key => $_value) {
                    $articles[$_key] = $_value;
                    $articles[$_key]['direct_url'] = JRoute::_(EasyBlogRouter::_('index.php?option=com_easyblog&view=entry&id=' . $_value['id']));
                    $articles[$_key]['category_direct_url'] = JRoute::_(EasyBlogRouter::_('index.php?option=com_easyblog&view=categories&layout=listings&id=' . $_value['category_id']));

                    if ($attributes['is_old_version']) {
                        $articles[$_key]['introtext'] = $_value['intro'];
                        $imageData = json_decode($_value['image'], true);
                        if (!is_null($imageData) && isset($imageData['title']) && isset($imageData['url'])) {
                            $images = array(
                                'image_intro' => JRoute::_($imageData['url']),
                                'image_intro_alt' => $imageData['title'],
                            );
                            $articles[$_key]['images'] = json_encode($images);
                        }
                    } else {
                        $_blog = new EasyBlogPost((int)$_value['id']);
                        $articles[$_key]['introtext'] = $_blog->getIntro();
                        $images = array(
                            'image_intro' => JRoute::_($_blog->getImage('original', true, true)),
                            'image_intro_alt' => $_value['title'],
                        );
                        $articles[$_key]['images'] = json_encode($images);
                    }
                }
                break;
            default :
                $articlesModel = new JSNPbArticlesModel();
                $result = $articlesModel->getArticlesByAttributes($attributes);
                foreach ($result as $_key => $_value) {
                    $articles[$_key] = $_value;
                    $articles[$_key]['direct_url'] = JRoute::_(ContentHelperRoute::getArticleRoute($_value['id'], $_value['catid']));
                    $articles[$_key]['category_direct_url'] = JRoute::_(ContentHelperRoute::getCategoryRoute($_value['catid']));
                }

                break;
        }
        return $articles;
    }

    public static function wordLimiter($str, $limit = 100, $end_char = '&#8230;')
    {
        $limit = $limit > 3000 ? 3000 : $limit;
        if (trim($str) === '') {
            return $str;
        }
        $str = strip_tags(trim($str));
        preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);

        if (strlen($str) === strlen($matches[0])) {
            $end_char = '';
        }

        return rtrim($matches[0]) . $end_char;
    }

    public static function removeShortCode($text, $shortCodeRegex) {
        if (is_array($text)) {
            $removeShortCodeText = "";
            foreach ($text as $_text) {
                preg_match_all("/" . $shortCodeRegex . "/s", $_text, $tmp_params, PREG_PATTERN_ORDER);
                if (count($tmp_params[5]) > 0) {
                    $removeShortCodeText .= self::removeShortCode($tmp_params[5], $shortCodeRegex);
                } else {
                    $removeShortCodeText .= $_text;
                }
            }
            return $removeShortCodeText;
        } else {
            preg_match_all("/" . $shortCodeRegex . "/s", $text, $tmp_params, PREG_PATTERN_ORDER);
            if (count($tmp_params[5]) > 0) {
                return self::removeShortCode($tmp_params[5], $shortCodeRegex);
            } else {
                return $text;
            }
        }
    }
}
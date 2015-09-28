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
 * Helper Article for JSN Pagebuilder
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPagebuilderHelpersArticles
{
    /**
     * @return mixed
     */
    public static function updateArticleUsedPageBuilderToPlugin(){


        //get Last Article ID
        $db = JFactory::getDbo();
        $limit = 6;
        $article = array();
        $articles = self::getArticleUsedPB($article);
//        if(!count($articles)) return false;
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('*');
        $query->from($db->quoteName('#__extensions'));
        $query->where($db->quoteName('type') .' = '. $db->quote('plugin') .' AND '. $db->quoteName('folder') .' = '. $db->quote('system') .' AND '. $db->quoteName('element') .' = '. $db->quote('pagebuilder'));
        $db->setQuery($query);
        $item = $db->loadObject();
        if($item != null){

            if($item->params != ''){
                $params = json_decode($item->params, true);
                $params ['articles'] = $articles;
            }else{
                $params = array();
                $params['articles'] = $articles;
            }

            //Check total article used pagebuilder if < $limit -> coninues update parameter

            $count_article = count($params['articles']);

            if ($count_article < $limit) {
                $default = json_encode($params);
                $query = $db->getQuery(true);
                $query->clear();
                $query->update($db->quoteName('#__extensions'));
                $query->set($db->quoteName('params') . ' = ' . $db->quote($default));
                $query->where($db->quoteName('type') . ' = ' . $db->quote('plugin') . ' AND ' . $db->quoteName('folder') . ' = ' . $db->quote('system') . ' AND ' . $db->quoteName('element') . ' = ' . $db->quote('pagebuilder'));
                $db->setQuery($query);
                return $db->execute();
            }
        }

    }

    /**
     * @return mixed
     */
    public static function getArticleUsedPB(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id');
        $query->from($db->quoteName('#__content'));
        $query->where($db->quoteName('introtext') .' LIKE '. $db->quote('%pb_row%') . ' AND ' .$db->quoteName('state') .' = ' . $db->quote('1'));
        $db->setQuery($query);
        $article = $db->loadRowList();
        return $article;

    }

    public static function buildQuery(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('*');
        $query->from($db->quoteName('#__extensions'));
        $query->where($db->quoteName('type') .' = '. $db->quote('plugin') .' AND '. $db->quoteName('folder') .' = '. $db->quote('system') .' AND '. $db->quoteName('element') .' = '. $db->quote('pagebuilder'));
        $db->setQuery($query);
        $item = $db->loadObject();
        return $item;
    }

    public static function getListArticleUedPagebuilderFromPlugin(){
        $item = self::buildQuery();
        $articleFromPlugin = array();
        $params = json_decode($item->params, true);
        if(isset($params['articles']))
        {
            $articles = $params['articles'];
            foreach ($articles as $article) {
                $implodeArticle = implode($article);
                $articleFromPlugin[] = $implodeArticle;
            }
            $listArticleId = join($articleFromPlugin, ',');
        }else{
            $listArticleId = 0;
        }

        return $listArticleId;


    }

    /**
     * @return mixed
     */
    public static function  getCountArticleUsedPageBuilderFromPlugin(){
        $item = self::buildQuery();
        $params = json_decode($item->params, true);
        if(isset($params['articles'])) {
            $countArticle = count($params['articles']);
        }else{
            $countArticle = 0;
        }
        return $countArticle;
    }
}
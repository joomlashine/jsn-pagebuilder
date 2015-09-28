<?php

/**
 *  @version    $Id$
 *  @package    JSN_PageBuilder
 *  @author     JoomlaShine Team <support@joomlashine.com>
 *  @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 *  @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 *  Websites: http://www.joomlashine.com
 *  Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

//No direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');
class JSNPagebuilderModelManager extends JModelList{

    var $_data = null;
    public function __construct($config){
        parent::__construct($config);
    }

    protected function getListQuery($countable = false){
        $config = JSNConfigHelper::get('com_pagebuilder');
        $configExtensions = json_decode($config->extension_support);
        $configExtensionsOrder = explode(",", $config->extension_support_order);
        $query = $this->_db->getQuery(true);
        if($configExtensionsOrder == 'com_modules'){
            if($countable == true){
                $query->select('COUNT(*)');
            }else{
                $query->select('m.*, e.element, e.manifest_cache');
            }
            $query->from('#__modules AS m');
            $query->innerJoin('#__extensions AS e ON (m.module = e.element AND e.type =\'module\'');
            $query->where("m.content LIKE '%pb_row%' AND e.client_id=0 AND m.published !=-2");
            return $query;
        }elseif($configExtensionsOrder == 'com_content'){
            if($countable == true){
                $query->select('COUNT(*)');
            }else{
                $query->select('*');
            }
            $query->from('#__content ');
            $query->where("introtext LIKE '%pb_row%' AND published !=-2");
            return $query;
        }elseif($configExtensionsOrder == 'com_k2'){
            if($countable == true){
                $query->select('COUNT(*)');
            }else{
                $query->select('*');
            }
            $query->from('#__k2_items');
            $query->where("introtext LIKE '%pb_row%' AND published !=-2");
            return $query;
        }elseif($configExtensionsOrder == 'com_easyblog'){
            if($countable == true){
                $query->select('COUNT(*)');
            }else{
                $query->select('*');
            }
            $query->from('#__easyblog_post');
            $query->where("intro LIKE '%pb_row%' AND published !=-2");
            return $query;
        }

        if($countable == true){
            $query->select('COUNT(*)');
        }else{
            $query->select('*');
        }

        $query->from($this->_getDataTable());
        $query->where($this->_getDataConditions());
        return $query;
    }

    protected function _getList($query, $limitstart = 0, $limit = 0){
        $ordering = $this->getState('list.ordering', 'ordering');
        $this->_db->setQuery($query);
        $result = $this->_db->loadObjectList();
        JArrayHelper::sortObjects($result, $ordering, $this->getState('list.direction') == 'desc' ? -1 : 1 , true, true);
        $total = count($result);
        $this->cache[$this->getStoreId('getTotal')] = $total;
        if($total < $limitstart){
            $limitstart = 0;
            $this->setState('list.start', 0);
        }
        return array_slice($result, $limitstart, $limit ? $limit : null);
    }

    public function getItems(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('a.id, a.title, a.catid, a.state, a.publish_up, a.publish_down, c.title AS category');
        $query->from('#__content AS a');
        $query->leftJoin('#__categories AS c ON a.catid = c.id');
        $query->where("a.introtext LIKE '%pb_row%' AND a.state !=-2");
        $db->setQuery($query);
        $items = $db->loadObjectList();
        return $items;
    }

    public function getItemModules(){
        $config = JSNConfigHelper::get('com_pagebuilder');
        $extension = json_decode($config->extension_support);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('m.id, m.title, m.position, m.published');
        $query->from('#__modules AS m');
        $query->where("m.content LIKE '%pb_row%' AND m.published !=-2");
        $db->setQuery($query);
        $modules = $db->loadObjectList();
        return $modules;

    }

    public function getItemK2(){
        jimport('joomla.application.component.helper');
        if(JSNPagebuilderHelpersExtensions::enableExt('k2', 'jsnpagebuilder', true) == false) return false;
        $db = JFactory::getDbo();
        $db->getQuery(true);
        $db->setQuery("SELECT enabled FROM #__extensions WHERE name = 'com_k2'");
        $is_enabled = $db->loadResult();
        if($is_enabled == 1) {
            $query = $db->getQuery(true);
            $query->clear();
            $query->select('k.id, k.title, k.catid, k.published, k.publish_up, k.publish_down, kc.name AS category');
            $query->from('#__k2_items AS k');
            $query->leftJoin('#__k2_categories AS kc ON k.catid = kc.id');
            $query->where("k.introtext LIKE '%pb_row%' AND k.published !=-2");
            $db->setQuery($query);
            $k2 = $db->loadObjectList();
            return $k2;
        }
    }

    public function getItemEasyblog(){

        $db = JFactory::getDbo();
        $db->getQuery(true);
        $db->setQuery("SELECT enabled FROM #__extensions WHERE name = 'com_easyblog'");
        $is_enabled = $db->loadResult();

        if($is_enabled == 1)
        {
            $query = $db->getQuery(true);
            $query->clear();
            $query->select('e.id, e.title, e.category_id, e.published, e.publish_up, e.publish_down, c.title AS category');
            $query->leftJoin('#__easyblog_category AS c ON e.category_id = c.id');
            $query->from('#__easyblog_post AS e');
            $query->where("e.intro LIKE '%pb_row%' AND e.published != -2");
            $db->setQuery($query);
            $easyBlog = $db->loadObjectList();
            return $easyBlog;
        }
    }



}
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

// No direct access
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$offset = is_numeric($_POST['offset']) ? $_POST['offset'] : die();

$query = "SELECT a.id, a.title, a.note, a.position, a.module, a.language,a.checked_out, a.checked_out_time, a.published+2*(e.enabled-1) as published, a.access, a.ordering, a.publish_up, a.publish_down,l.title AS language_title,uc.name AS editor,ag.title AS access_level,MIN(mm.menuid) AS pages,e.name AS name
FROM #__modules AS a
LEFT JOIN #__languages AS l ON l.lang_code = a.language
LEFT JOIN #__users AS uc ON uc.id=a.checked_out
LEFT JOIN #__viewlevels AS ag ON ag.id = a.access
LEFT JOIN #__modules_menu AS mm ON mm.moduleid = a.id
LEFT JOIN #__extensions AS e ON e.element = a.module
WHERE a.published = 1 AND a.client_id = 0 AND e.client_id =0
GROUP BY a.id, a.title, a.note, a.position, a.module, a.language,a.checked_out,a.checked_out_time, a.published, a.access, a.ordering,l.title, uc.name, ag.title, e.name,l.lang_code, uc.id, ag.id, mm.moduleid, e.element, a.publish_up, a.publish_down,e.enabled LIMIT 50 OFFSET " . $offset;
$modules = $db->setQuery($query);
$modules = $db->loadObjectList();

?>
<div class="jsnpbd-module-content">
    
    <?php foreach ($modules as &$module) : ?>
                <div class="jsn-item-type" id="<?php echo $module->id; ?>" style="">
                    <?php 
                        $string = $this->escape($module->title);
                        $moduleType = $this->escape($module->title);
                        $title = JHTML::_('string.truncate', $string, 30);
                        $client = JApplicationHelper::getClientInfo(0);
                        $lang = JFactory::getLanguage();
                        $path = JPath::clean($client->path . '/modules/' . $module->module. '/' . $module->module. '.xml');
                        if(file_exists($path)){
                            $module->xml = simplexml_load_file($path);
                        }else{
                            $modile->xml = null;
                        }
                        //load language
                        $lang->load($module->module. '.sys', $client->path, null, false, true) || $lang->load($module->module. 'sys', $client->path . '/modules/'. $module->module, null, false, true);
                        $module->name = JText::_($module->name);
                        //if description isset return description text, if not isset return text module is no description
                        if(isset($module->xml) && $text = trim($module->xml->description)){
                            $module->desc = JText::_($text);
                        }  else {
                            $module->desc = JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_MODULE_IS_NO_DES');
                        }
                        $short_desc	= JHTML::_('string.truncate', ($this->escape($module->desc)), 40);
                        

                    ?>
                    <div class="editlinktip btn" data-module-title="<?php echo $title; ?>" title="<?php echo $title; ?>">
                    <span><?php echo $title; ?></span>
                    <p><?php  echo '['.$moduleType .'] - '. $short_desc; ?></p>
                </div>
            </div>
            <?php endforeach;?>
</div>

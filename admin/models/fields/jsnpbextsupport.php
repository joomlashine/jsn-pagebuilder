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

defined('_JEXEC') or die('Restricted access');

class  JFormFieldJSNPbextsupport extends JFormField{
    protected $type = 'JSNPbextsupport';
    protected $forceMultiple = true;

    protected function getLabel(){
        $label = '';
        if($this->hidden){
            return $label;
        }

        // Get the label text from the XML element, defaul to the element name
        $text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
        $text - $this->translateLabel ? JText::_($text) : $text;

        // Build the class for the label

        $class = array('control-label');
        $class[] = $this->required == true ? ' reuquired' : '';
        $class[] = !empty($this->labelclass) ? ' ' . $this->labelclass : '';
        $class = implode('', $class);

        // Add the open label tag and class attribute
        $label .= '<label class="' . $class .'"';

        // If a description is specified, use it to build a tooltip
        if(!empty($this->description)){
            $label .= ' title="'. htmlspecialchars(trim($text, ':') . '::' . ($this->translateDescription ? JText::_($this->description) : $this->description), ENT_COMPAT, 'UTF-8'). '"';
        }

        // Add the label text and close tag
        $label .= '>' . $text . ($this->required ? '<span class="star">&#160;*</span>' : '') . '</label>';
        return $label;
    }

    protected function getInput(){
        jimport('administrator.components.com_pagebuilder.helpers.pagebuilder');
        $config = JSNConfigHelper::get('com_pagebuilder');
        $extSupports = JSNPagebuilderHelpersPagebuilder::getExtensionSupports();

        if(count(@$config->extension_support_order)){
            $extSupportOrder = explode(",", $config->extension_support_order);

            // Add new if it not exist in database
            if(count($extSupports)){
                foreach($extSupports as $support){
                    if(!in_array($support, $extSupportOrder)){
                        array_push($extSupportOrder, $support);
                    }
                }
            }
        }else{
            $extSupportOrder = $extSupports;
        }

        if((string) $this->value != '') {
            $selectedExtension = json_decode($this->value);
        }else{
            $selectedExtension = JSNPagebuilderHelpersPagebuilder::getExtensionSupports(false);

        }
        
        $html[] = '<ul class="sortable">';
        foreach($extSupportOrder as $support){
            if($support){
                if(strpos($support, JSN_PAGEBUILDER_3RD_EXTENSION_STRING) != false && !count(JPluginHelper::getPlugin('pagebuilder', str_replace(JSN_PAGEBUILDER_3RD_EXTENSION_STRING . '-', '', $support)))){
                    continue;
                }else{
                    $checked = in_array($support, $selectedExtension) ? 'checked' : '';
                    /*if($config->extension_support == null){
                        $checked = '';
                    }*/
                    $html[] = '<li class="item" id="'. $support . '">
                    <ins class="sortable-handle"></ins>
                    <label class="checkbox">
                    <input type="checkbox" name="'. $this->name . '" value="'. $support . '" ' . $checked . '/>
                    ' . JText::_('JSN_PAGEBUILDER_SUPPORT_'. str_ireplace(JSN_PAGEBUILDER_3RD_EXTENSION_STRING. '-', '', strtoupper($support))) .'
                    </label>
                    <div class="clearbreak"></div>
                    </li>';
                }
            }
        }
        $html[] = '</ul>';
        $html[] = '<input type="hidden" value="'. implode(',', $extSupportOrder) . '" id="params_extension_support_order" name="jsnconfig[extension_support_order]" />';
        return implode($html);


    }

    protected function getOption(){
        JSNPagebuilderHelpersPagebuilder::localimport('helpers.pagebuilder');
        $options = array();
        $options = JSNPagebuilderHelpersPagebuilder::getExtensionSupports();
        return $options;
    }
}
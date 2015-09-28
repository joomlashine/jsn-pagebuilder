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
defined('_JEXEC') or die('Restricted access');

class JSNPBShortcodeModule extends IG_Pb_Element{
    
    /**
     * Constructor
     * 
     * @return type
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Include admin script
     * 
     * @return type
     */
    
    public function backend_element_assets() {
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-linktype.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL.'/module/assets/js/module-setting.js', 'js' );
    }
    
    /**
     * define configuration information of shortcode
     * 
     * @return type Description
     */
    
    public function element_config() {
    	$this->config['shortcode']			= 'pb_module';
    	$this->config['name']				= JText::_('JSN_PAGEBUILDER_ELEMENT_MODULE');
    	$this->config['']				= JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_MEDIA');
    	$this->config['icon']				= 'icon-joomla';
    	$this->config['description']		= JText::_('JSN_PAGEBUILDER_ELEMENT_MODULE_DES');
    	$this->config['has_subshortcode']	= __CLASS__ . 'Item';
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
        	'content'	=> array(
        		array(
        			'name' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE'),
        			'id'	=> 'el_title',
        			'type'	=> 'text_field',
        			'class'	=> 'jsn-input-xxlarge-fluid',
        			'std'	=> JText::_('JSN_PAGEBUILDER_ELEMENT_MODULE_ELEMENT_TITLE_STD'),
        			'role'  => 'title',
        			'tooltip'=> JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES'),
        		),
                array(
                    'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_MODULE_NAME' ),
                    'id'      => 'module_name',
                    'type'    => 'select_module',
                    'std'     => '',
                    'class'   => 'jsn-input-large-fluid',
                    'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_MODULE_NAME_DES' )
                ),
                        
        	),
            'styling' => array(
                array(
                    'type' => 'preview',
                ),
                array(
                    'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT' ),
                    'id'      => 'module_alignment',
                    'type'    => 'select',
                    'std'     => 'left',
                    'options' => array(
                            '0'      => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO_ALIGNMENT' ),
                            'left'   => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_LEFT' ),
                            'right'  => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_RIGHT' ),
                            'center'  => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_CENTER' ),
                    ),
                    'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_DES' ),
                ),
                array(
                    'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SHOW_TITLE' ),
                    'id'      => 'module_show_title',
                    'type'    => 'radio',
                    'std'     => 'yes',
                    'options' => array( 'yes' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES' ), 'no' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO' ) ),
                    'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_SHOW_TITLE_DES' )
                ),
            )
        );
        
        
    }
    
    /**
     * define shortcode content
     * 
     * @param type $atts
     * @param type $content
     */
    
    public function element_shortcode($atts = null, $content = null) {
        $document = JFactory::getDocument();
        $document->addStyleSheet( JSNPB_ELEMENT_URL . '/module/assets/css/module.css', 'text/css' );
        $html_element  = '';
        $arr_params	   = ( JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts ) );
        extract( $arr_params );
        $module_class = 0;
        $container_style = '';
        $container_class = '';
        if ( $arr_params['module_alignment'] === 'right' ) {
        	$container_style .= 'float: right;';
        } else if ( $arr_params['module_alignment'] === 'center' ) {
        	$container_style .= 'margin: 0 auto;';
            $container_class = 'center';
        } else if ( $arr_params['module_alignment'] === 'left' ) {
        	$container_style .= 'float: left;';
        }
        $container_style = $container_style ? ' style=" ' . $container_style . ' " ' : '';
        $container_class = $container_class ? 'center' : '';
                
        if(empty($module_name)){
                        $html_element = "<p class='jsn-bglabel'>" . JText::_( 'No module selected' ) . '</p>';
        }  else {
	    $module_id =  preg_replace("/[^0-9^]/", "", substr($module_name, 0, 5));
        $show_title = $arr_params['module_show_title'];
        if(isset($module_id)){
            $html_element .= '<div class="jsn-pb-module-element pb-element-container pb-element-module '. $container_class .'"'.$container_style.'>';
            $html_element .= $this->jsn_load_module($module_id, $module_class, $style='none', $show_title);
            $html_element .= '</div>';
            $html_element .= '<div style="clear: both"></div>';
        }
        
        }
	    $html_element = JSNPagebuilderHelpersBuilder::generateShortCode($html_element, false, 'frontend', true);
        return $this->element_wrapper( $html_element, $arr_params );
    }
    
    /**
     * Load module content
     * 
     * @param type $module_id Description
     */
    public function jsn_load_module($module_id, $module_class, $style = 'none', $show_title){
        jimport( 'joomla.application.module.helper' );
        $user = JFactory::getUser();
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $db = JFactory::getDbo();
        $document = JFactory::getDocument();
        $renderer = $document->loadRenderer('module');
        $contents = '';
        $query = $db->getQuery(true);
        $query->select('m.id, m.title, m.module, m.position, m.ordering, m.content, m.showtitle, m.params, m.access');
        $query->from('#__modules AS m');
        $query->where('m.id='.$module_id.' AND m.client_id=0');
        $query->where('m.access IN (' . $groups . ')');
        
        $module = $db->setQuery($query);
        $module = $db->loadObject();
        
        if (!count($module))
        {
        	return '';
        }
        
        $module->user ='';
        $title=	$module->title;
        $content = $module->content;
        $id = $module->id;      
        
        if (!is_object($module))
        {
            if (is_null($content))
            {
                return '';
            }
            else
            {
                /**
                 * If module isn't found in the database but data has been pushed in the buffer
                 * we want to render it
                 */
                $tmp = $module;
                $module = new stdClass;
                $module->params = null;
                $module->module = $tmp;
                $module->id = 0;
                $module->user = 0;
            }
        }
        // Set the module content
        if (!is_null($content))
        {
                $module->content = $content;
        }
        // Get module parameters
        $params = new JRegistry;
        $params->loadString($module->params);
        $module->params = $params;
        $contents = '<div class="pb-module-'.$id.'">';
        if($renderer->render($module, $params, $content) == ""){
            $contents .="<p class='jsn-bglabel'>" . JText::_( 'Preview is not available for this module' ) . '</p>';
        }else {
            if ($show_title === 'yes') {
                $contents .= '<h3 class="pb-module-title">' . $title . '</h3>';
            }
            $contents .= '<div class="pb-module-content">';
            $contents .= $renderer->render($module, $params, $content);
        }
        $contents .= '</div>';
        $contents .= '</div>';
        return $contents;

    }
   
    /**
     * generate Module item row template
     * 
     * @return string
     */
    
    private static function generateModuleItemRowTemplate(){
        JModuleHelper::getLayoutPath($module);
    }
    
}


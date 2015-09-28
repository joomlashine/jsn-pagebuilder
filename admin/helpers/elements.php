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
 * Helper for elements
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPagebuilderHelpersElements
{
	private $elements;
	
	private $isShortcodeGenerated = false;	
	
	private $css	= array();
	
	private $js	= array();
	/**
	 * Constructor.
	 *
	 * @return  void
	 */
	function __construct() {
	    $this->elements = array();
		$this->registerElements();
	}
	
	/**
	 * Method to set PageBuilder shortcode status
	 * 
	 * @param unknown_type $status
	 * 
	 * @return void
	 */
	public function setGeneratedStatus($status) {
		$this->isShortcodeGenerated	= $status;
	}
	
	/**
	 * Method to get PageBuilder shortcode status
	 * 
	 * @return void
	 */
	
	public function getGeneratedStatus() {
		return $this->isShortcodeGenerated;
	}
	/**
	 * Get array of shortcode elements
	 * 
	 * @return type
	 */
	function getElements() {
	    return $this->elements;
	}
	
	/**
	 * Add shortcode element
	 * 
	 * @param type $type: type of element (element/layout)
	 * @param type $class: name of class
	 * @param type $element: instance of class
	 * 
	 * @return void
	 */
	function setElement($class, $element = null) {
	    if (empty($element)){
	        $this->elements[strtolower($class)] = new $class();
	     }else{
	        $this->elements[strtolower($class)] = $element;
	     }
	}
	
	/**
	 * Register elements
	 * 
	 * @return void
	 */
	public function registerElements()
	{
		//$current_shortcode = IG_Pb_Functions::current_shortcode();
		$shortcodes = JSNPagebuilderHelpersShortcode::getShortcodeTags();
		//echo "<pre>";
		//print_r($shortcodes); die();
		// Use Row and Column as shortcodes
		$shortcodes['pb_row']		= 'row';
		$shortcodes['pb_column']	= 'column';
		
		foreach ($shortcodes as $name => $type) {					    
		        $class = JSNPagebuilderHelpersShortcode::getShortcodeClass($name);
		        if (class_exists($class)){
		        	$element = new $class();
		        	$this->setElement($class, $element);
		        }       
		}
	}
	
	/**
	 * Method to store Element's own stylesheets
	 * Enter description here ...
	 * @param unknown_type $url
	 */
	public function addStyleSheet($url)
	{
		if (!in_array($url, $this->css)) {
			array_push($this->css, $url);
		}
	}
	
	/**
	 * Method to store Element's own javascript files
	 * Enter description here ...
	 * @param unknown_type $url
	 */
	public function addScript($url)
	{
		if (!in_array($url, $this->js)) {
			array_push($this->js, $url);
		}
	}	
}

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
 * Parent class for all elements of page builder
 *
 * @package  IG_PageBuilder
 * @since    1.0.0
 */
class IG_Pb_Common {

    /**
     * element type: layout/element
     */
    public $type;

    /**
     * config information of this element
     */
    public $config;

    /**
     * setting options of this element
     */
    public $items;

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct() {

    }

    /**
     * Include backend assets
     * 
     * @return type
     */
    public function backend_element_assets() {
    	 
    }
    
	/**
     * Include frontend assets
     * 
     * @return type
     */
    public function frontend_element_assets() {
    	 
    }

    /**
     * HTML structure of an element in SELECT ELEMENT modal box
     * 
     * @param type $sort
     * 
     * @return type
     */
    public function element_button($sort) {

    }

    /**
     * HTML structure of an element in Page Builder area
     * 
     * @return type
     */
    public function element_in_pgbldr() {

    }
}
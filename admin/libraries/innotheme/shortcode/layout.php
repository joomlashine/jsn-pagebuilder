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

include_once 'common.php';

/**
 * Parent class for layout elements
 *
 * @package  IG_PageBuilder
 * @since    1.0.0
 */
class IG_Pb_Layout extends IG_Pb_Common {

	/**
	 * Constructor
	 * 
	 * @return type
	 */
    public function __construct() {
        $this->type = 'layout';

        $this->element_config();
        $this->element_items();
        $this->shortcode_data();       
    }

    /**
     * HTML structure of item in List item
     * 
     * @return type
     */
    public function element_button($sort) {

    }

    /**
     * HTML structure of element in Page Builder area
     * 
     * @return type
     */
    public function element_in_pgbldr() {

    }

    /**
     * DEFINE shortcode content
     *
     * @param type $atts
     * @param type $content
     * 
     * @return type
     */
    public function element_shortcode($atts = null, $content = null) {

    }

    /**
     * Get params & structure of shortcode
     * 
     * @return type
     */
    public function shortcode_data() {
    }

}
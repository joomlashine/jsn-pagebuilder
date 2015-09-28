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
 * Parent class for sub elements
 *
 * @package  IG_PageBuilder
 * @since    1.0.0
 */
class IG_Pb_Child extends IG_Pb_Element {

	/**
	 * DEFINE html structure of shortcode in Page Builder area
	 *
	 * @param type $content
	 * @param type $shortcode_data: string stores params (which is modified default value) of shortcode
	 * @param type $el_title: Element Title used to identifying elements in Pagebuilder
	 * Ex:  param-tag=h6&param-text=Your+heading&param-font=custom&param-font-family=arial
	 * 
	 * @return type
	 */
	public function element_in_pgbldr( $content = '', $shortcode_data = '', $el_title = '' ) {
		$this->config['sub_element'] = true;
		return parent::element_in_pgbldr( $content, $shortcode_data, $el_title );
	}

}

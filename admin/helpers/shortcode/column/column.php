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

require_once JSNPB_ADMIN_ROOT . '/libraries/innotheme/shortcode/layout.php';

/**
 * Column element layout
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeColumn extends IG_Pb_Layout {

	/**
	 * Constructor of Column element layout
	 * 
	 * @return void
	 */
    public function __construct() {
        parent::__construct();
    }

    /**
     * DEFINE configuration information of shortcode
     * 
     * @return void
     */
    function element_config() {
        $this->config['shortcode'] = 'pb_column';
        $this->config['extract_param'] = array('span');
    }

    /**
     * contain setting items of this element (use for modal box)
     *
     * @return void
     */
    function element_items() {

    }

    /**
     * contain setting items of this element (use for modal box)
     *
     * @return void
     */
    function backend_element_items() {

    }

    /**
     * html structure of element in Page Builder area
     *
     * @param type $content             : inner shortcode elements of this column
     * @param string $shortcode_data
     * @return string
     */
    public function element_in_pgbldr($content = '', $shortcode_data = '') {
        
    	$column_html = empty($content) ? '' : JSNPagebuilderHelpersBuilder::generateShortCode($content, true);
        $span = (!empty($this->params['span'])) ? $this->params['span'] : "span12";
        $shortcode_data = '[' . $this->config['shortcode'] . ' span="' . $span . '"]';
        $column = '<div class="jsn-column-container clearafter shortcode-container ">
                        <div class="jsn-column ' . $span . '">
                            <div class="thumbnail clearafter">
                                <textarea class="hidden" name="shortcode_content[]" >' . $shortcode_data . '</textarea>
                                <div class="jsn-column-content item-container" data-column-class="' . $span . '" >
                                    <div class="jsn-handle-drag jsn-horizontal jsn-iconbar-trigger"><div class="jsn-iconbar layout"><a class="item-delete column" onclick="return false;" title="'.JText::_('Delete column').'" href="#"><i class="icon-trash"></i></a></div></div>
                                    <div class="jsn-element-container item-container-content">' . $column_html . '</div>
                                    <a class="jsn-add-more pb-more-element" href="javascript:void(0);"><i class="icon-plus"></i>' . JText::_('JSN_PAGEBUILDER_HELPER_BUILDER_ADD_ELEMENT') . '</a>
                                </div>
                                <textarea class="hidden" name="shortcode_content[]" >[/' . $this->config['shortcode'] . ']</textarea>
                            </div>
                        </div>
                    </div>';
        return $column;
    }

    /**
     * define shortcode structure of element
     * 
     * @return string
     */
    function element_shortcode($atts = null, $content = null) {
        extract(JSNPagebuilderHelpersShortcode::shortcodeAtts(array('span' => 'span6', 'style' => ''), $atts));
        
        $style   = empty( $style ) ? '' : "style='$style'";
		$span    = intval( substr( $span, 4 ) );
		$class   = "col-md-$span col-sm-$span col-xs-12";
		$column_html = empty($content) ? JSNPagebuilderHelpersShortcode::removeAutop($content) : JSNPagebuilderHelpersBuilder::generateShortCode($content, true, 'frontend');		
		return '<div class="' . $class . '" ' . $style . '>' . $column_html . '</div>';
    }
    
    /**
     * get params & structure of shortcode
     * 
     * @return void
     */
    public function shortcode_data() {
    	$this->config['params'] = JSNPagebuilderHelpersShortcode::generateShortcodeParams($this->items, null, null, false, true);
    	$this->config['shortcode_structure'] = JSNPagebuilderHelpersShortcode::generateShortcodeStructure($this->config['shortcode'], $this->config['params']);
    }

}

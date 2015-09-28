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

include_once (dirname(__FILE__) . '/common.php');

/**
 * Parent class for normal elements
 *
 * @package  IG_PageBuilder
 * @since    1.0.0
 */
class IG_Pb_Element extends IG_Pb_Common {

	/**
	 * Constructor
	 * 
	 * @return type
	 */
    public function __construct() {
        $this->type = 'element';
        $this->items = array();
        $this->element_config();
        $this->frontend_element_items();
        $this->element_items_extra();
        $this->shortcode_data();
    }

    /**
     * DEFINE configuration information of shortcode
     * 
     * @return type
     */
    public function element_config() {

    }

    /**
     * DEFINE setting options of shortcode
     * 
     * @return type
     */
    public function element_items() {

    }

    /**
     * DEFINE setting options of shortcode
     *
     * @return type
     */
    public function frontend_element_items() {

    }

    /**
     * DEFINE setting options of shortcode
     *
     * @return type
     */
    public function backend_element_items() {

    }

    /**
     * Add more options to all elements
     * 
     * @return type
     */
    public function element_items_extra() {
    	
    	$css_suffix = array(
    		'name'    => JText::_( 'JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_CSS_CLASS_SUFFIX' ),
    		'id'      => 'css_suffix',
    		'type'    => 'text_field',
    		'std'     => JText::_( '' ),
    		'tooltip' => JText::_( 'JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_CSS_CLASS_SUFFIX_DES' )
    	);
        $appearing_animation = array(
            'name'      => JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_APPEARING_ANIMATION'),
            'id'        => 'appearing_animation',
            'type'      => 'select',
            'std'       => '0',
            'options'   => JSNPagebuilderHelpersType::getAnimation(),
            'tooltip'   => JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_APPEARING_ANIMATION_DES'),
            'has_depend' => '1'
        );
        $appearing_animation_speed = array(
            'name'          => JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_APPEARING_ANIMATION_SPEED'),
            'id'            => 'appearing_animation_speed',
            'type'          => 'select',
            'dependency'    =>  array('appearing_animation', '!=', '0'),
            'std'           => 'medium',
            'options'       => array(
                'slow' => JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_SLOW'),
                'medium' => JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_MEDIUM'),
                'fast' => JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_FAST')
            ),
            'tooltip'   => JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_APPEARING_ANIMATION_SPEED_DES')
        );
    	if ( isset ( $this->items['styling'] ) ) {
    		$this->items['styling'] = array_merge(
    			$this->items['styling'], array(
                    $appearing_animation,
                    $appearing_animation_speed,
   					$css_suffix,
    				// always at the end of array
    				array(
    					'name'			=> JText::_( 'JSN_PAGEBUILDER_HELPER_SHORTCODE_MARGIN' ),
    					'container_class' 	=> 'combo-group',
    					'id'			=> 'div_margin',
    					'type'			=> 'margin',
    					'extended_ids'	=> array( 'div_margin_top', 'div_margin_bottom' ),
    					'div_margin_top'	=> array( 'std' => '' ),
    					'div_margin_bottom'	=> array( 'std' => '' ),
    					'margin_elements'	=> 't, b',
    					'tooltip' 			=> JText::_( 'Set margin size' )
    				),
    			)
    		);
    	} else {
    		if ( isset ( $this->items['Notab'] ) ) {
    			$this->items['Notab'] = array_merge(
    				$this->items['Notab'], array(
//     					$css_suffix
    				)
    			);
    		}
    	}
    }
    
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
    public function element_in_pgbldr($content = '', $shortcode_data = '', $el_title = '') {
    	// Assign Untitled
    	$shortcode = $this->config['shortcode'];
    	if ( ( ( $el_title == '<i class=""></i>' ) && empty( $content ) ) || ( empty( $el_title ) && empty( $content ) ) ) {
    		if ( strpos( $shortcode, '_item' ) !== false ) {
    			$el_title = JText::_( 'JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_UNTITLED' );
    		} else {
    			$el_title = '';
    		}
    	}
        $is_sub_element = (isset($this->config['sub_element'])) ? true : false;
        $parent_shortcode = ($is_sub_element) ? str_replace(array('pb_','_item'), "", $shortcode) : $shortcode;
        $type = !empty($this->config['el_type']) ? $this->config['el_type'] : 'element';
        $buttons = array(
            'edit' => '<a href="#" onclick="return false;" title="'.JText::_('JSN_PAGEBUILDER_HELPER_BUILDER_EDIT_ELEMENT').'" data-shortcode="' . $shortcode . '" class="element-edit"><i class="icon-pencil"></i></a>',
            'clone' => '<a href="#" onclick="return false;" title="'.JText::_('JSN_PAGEBUILDER_HELPER_BUILDER_DUPLICATE_ELEMENT').'" data-shortcode="' . $shortcode . '" class="element-clone"><i class="icon-copy"></i></a>',
            'delete' => '<a href="#" onclick="return false;" title="'.JText::_('JSN_PAGEBUILDER_HELPER_BUILDER_DELETE_ELEMENT').'" class="element-delete"><i class="icon-trash"></i></a>'
        );
        // Empty content if this is not sub element
        if (!$is_sub_element)
            $content = "";
        $exception = isset($this->config['exception']) ? $this->config['exception'] : array();
        $content = (isset($exception['default_content'])) ? $exception['default_content'] : $content;

        // if content is still empty, Generate it
        if (empty($content)) {
            if (!$is_sub_element)
                $content = ucfirst(str_replace(JSNPB_SHORTCODE_PREFIX, "", $shortcode));
            else{
                if(isset($exception['item_text'])){
                    if(!empty ($exception['item_text'])){
                    	$content = $exception['item_text'] . " PB_INDEX_TRICK";
                    }   
                }
                else{
                	$content = JText::_(ucfirst($parent_shortcode)) ." ". JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_ITEM') ." PB_INDEX_TRICK";
                }
            }
        }

        $content = !empty ($el_title) ? ($content . ": " . "<span>$el_title</span>") : $content;
        $content = ( ! empty ($el_title) && $is_sub_element == true ) ? $el_title : $content;
        // element name
        if($type == "element"){
            if (!$is_sub_element)
                $name = ucfirst(str_replace(JSNPB_SHORTCODE_PREFIX, "", $shortcode));
            else
                $name = JText::_(ucfirst($parent_shortcode)) ." ". JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_ITEM');
        }
        else{
            $name = $content;
        }
        if (empty($shortcode_data))
            $shortcode_data = $this->config['shortcode_structure'];

        $shortcode_data = stripslashes($shortcode_data);
        $element_wrapper = !empty ($exception['item_wrapper']) ? $exception['item_wrapper'] : ( $is_sub_element ? "li" : "div");
        $content_class = ($is_sub_element) ? "jsn-item-content" : "pb-plg-element";
        $action_btns = (empty($exception['action_btn'])) ? implode("", $buttons) : $buttons[$exception['action_btn']];
        $modal_title = !empty ($exception['data-modal-title']) ? "data-modal-title='{$exception['data-modal-title']}'" : "";
        $element_type = "data-el-type='$type'";
        $exclude_gen_shortcode = ( isset( $exception['exclude_gen_shortcode'] ) ) ? 'exclude_gen_shortcode' : '';
        
        $data = array(
        	'element_wrapper' => $element_wrapper,
        	'modal_title' => $modal_title,
        	'element_type' => $element_type,
        	'name' => $name,
        	'shortcode' => $shortcode,
       		'shortcode_data' => $shortcode_data,
        	'content_class' => $content_class,
       		'content' => $content,
       		'action_btn' => empty( $exception['action_btn'] ) ? '' : $exception['action_btn'],
        	'exclude_gen_shortcode' => $exclude_gen_shortcode
        );
        $extra = array();
        if ( isset( $this->config['exception']['disable_preview_container'] ) ) {
        	$extra = array(
        		'has_preview' => FALSE,
        	);
        }
        $data = array_merge( $data, $extra );
        $html_preview = JSNPagebuilderHelpersFunctions::getElementItemHtml( $data );
        return $html_preview;
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
     * Wrap output html of a shortcode
     *
     * @param array $arr_params
     * @param string $html_element
     * @param string $extra_class
     * 
     * @return string
     */
    public function element_wrapper( $html_element, $arr_params, $extra_class = '', $custom_style = '' ) {
    	$shortcode_name = str_replace( 'pb_', '', $this->config['shortcode'] );
    	// extract margin here then insert inline style to wrapper div
    	$styles = array();
    	if ( ! empty ( $arr_params['div_margin_top'] ) ) {
    		$styles[] = 'margin-top:' . intval( $arr_params['div_margin_top'] ) . 'px';
    	}
    	if ( ! empty ($arr_params['div_margin_bottom'] ) ) {
    		$styles[] = 'margin-bottom:' . intval( $arr_params['div_margin_bottom'] ) . 'px';
    	}
    	$style = count( $styles ) ? implode( '; ', $styles ) : '';
    	if ( ! empty( $style ) || ! empty( $custom_style ) ){
    		$style = "style='$style $custom_style'";
    	}
    	
    	if ($shortcode_name != 'text' && $shortcode_name != 'module')
    	{	
    		$class  = "jsn-bootstrap3 jsn-pagebuilder pb-element-container pb-element-$shortcode_name";
    	}
    	else 
    	{
    		$class  = "pb-element-container pb-element-$shortcode_name";
    	}
    	
    	$extra_class .= ! empty ( $arr_params['css_suffix'] ) ? ' ' . htmlspecialchars( $arr_params['css_suffix'] ) : '';
    	$class .= ! empty ( $extra_class ) ? " $extra_class" : '';
        //Element appearing animation
        $appearing_animation = '';
        $appearing_animation_speed =  '';
        if(!empty($arr_params['appearing_animation']) || $arr_params['appearing_animation'] != 0){

            if(!empty($arr_params['appearing_animation_speed'])){
                switch($arr_params['appearing_animation_speed']){
                    case 'slow':
                        $appearing_animation_speed .= '0.9';
                        break;
                    case 'medium':
                        $appearing_animation_speed .= '0.6';
                        break;
                    case 'fast':
                        $appearing_animation_speed .= '0.3';
                        break;
                }
            }
            switch($arr_params['appearing_animation']){
                case 'slide_from_top':
                    $appearing_animation = 'data-scroll-reveal="enter top and move 150px over '. $appearing_animation_speed . 's"';
                    break;
                case 'slide_from_right':
                    $appearing_animation = 'data-scroll-reveal="enter right and move 150px over '. $appearing_animation_speed . 's"';
                    break;
                case 'slide_from_bottom':
                    $appearing_animation = 'data-scroll-reveal="enter bottom and move 150px over '. $appearing_animation_speed . 's"';
                    break;
                case 'slide_from_left':
                    $appearing_animation = 'data-scroll-reveal="enter left and move 150px over '. $appearing_animation_speed . 's"';
                    break;
                case 'fade_in':
                    $appearing_animation = 'data-scroll-reveal="ease-in 0 over '. $appearing_animation_speed . 's"';
                    break;
            }
            $script = "

					(function($) {
					 var revealObjects  = null;
                        $(document).ready(function() {
                            if($('[data-scroll-reveal]').length) {
                                if (!revealObjects) {
                                    revealObjects = new scrollReveal({
                                            reset: true
                                        });
                                }
                            }
                        });
                    })(jQuery);
                            ";
            JFactory::getDocument()->addScriptDeclaration($script, 'text/javascript');
            self::animation_scripts();
        }
        $html = "<div class='$class' $style>" . $html_element . '</div>';
        if($appearing_animation){
            $html = "<div $appearing_animation>" . $html . "</div>";
        }

        return $html;
    }

    /**
     * add script animation to element
     */
    static function animation_scripts(){
        $document = JFactory::getDocument();
        $document->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/scrollreveal/scrollReveal.js', 'text/javascript' );
    }
    
    /**
     * DEFINE html structure of shortcode in "Select Elements" Modal
     *
     * @param type $sort
     * 
     * @return type
     */
    public function element_button($sort) {
        // don't sort by element type anymore, sort by element/widet criteria
        ///$type = is_array($this->config['cat']) ? implode(" ", $this->config['cat']) : $this->config['cat'];
        $type = 'element';
        $extra_ = ($sort > 0) ? "data-value='".strtolower($this->config['shortcode'])."' data-type = '" . ($type) . "'" : '';
        $extra_ .= isset( $this->config['exception']['data-modal-title'] ) ? ' data-modal-title="' . $this->config['exception']['data-modal-title'] . '"' : ' data-modal-title="' . $this->config['name'] . '"';
        return self::el_button($extra_, $this->config);
    }

    public static function el_button($extra_, $config) {
        $icon = isset ($config['icon']) ? '<i class="jsn-icon-formfields jsn-' . $config['icon'] . '"></i>' : '';
        return '<li class="jsn-item" ' . $extra_ . ' style="border: medium none; box-sizing: border-box; float: left; min-width: 250px; padding: 10px; width: 25% !important;">
                    <button data-shortcode="' . $config['shortcode'] . '" class="shortcode-item btn">
                        ' . $icon . '<span>'. $config['name'] .'</span><br><br>'. '<p class="help-block">'.$config['description'] .'</p>
                    </button>
                </li>';
    }

    /**
     * Get params & structure of shortcode
     * 
     * @return type
     */
    public function shortcode_data() {
		$params = JSNPagebuilderHelpersShortcode::generateShortcodeParams($this->items, null, null, false, true);
		// add Margin parameter for Not child shortcode
		if ( strpos( $this->config['shortcode'], '_item' ) === false ) {
			$this->config['params'] = array_merge( array( 'div_margin_top' => '', 'div_margin_bottom' => '', 'css_suffix' => '' ), $params );
		}
		else {
			$this->config['params'] = $params;
		}
		$this->config['shortcode_structure'] = JSNPagebuilderHelpersShortcode::generateShortcodeStructure($this->config['shortcode'], $this->config['params']);
    }

}

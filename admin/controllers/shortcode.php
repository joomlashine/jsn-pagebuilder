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
 * Shortcode controller
 *
 * @package     JSN_PageBuilder
 * @since       1.0.0
 */
class JSNPagebuilderControllerShortcode extends JSNBaseController
{
	/**
	 * Save session for shortcode params
	 * 
	 * @return void
	 */
	public function saveSession()
	{
		// Using $_POST instead JRequest::getVar() because getVar() can't get tinyMCE content has img tag

		$params		    = $_POST['params'];
		$params 		= str_replace( 'PB_INDEX_TRICK', '1', $params );
		$shortcodeName	= JRequest::getVar('shortcode');

		if ($params && $shortcodeName) {
			$session = JFactory::getSession();
			$_SESSION[JSNPB_SHORTCODE_SESSION_NAME][$shortcodeName]['params']	= $params;
			$session->set('JSNPA_SHORTCODEPARAMS', $params);
		}
		exit();
	}
	
	/**
	 * Method to generate HTML content for shortcode
	 * in pagebuilder layout
	 * 
	 * @return string
	 */
	public function generateHolder()
	{
		// Using $_POST instead JRequest::getVar() because getVar() can't get tinyMCE content has img tag
		$params		= $_POST['params'];
		$params		= urldecode($params);
		$shortcode	= JRequest::getVar('shortcode');
		$element_title	= JRequest::getVar('el_title');
		$class		= JSNPagebuilderHelpersShortcode::getShortcodeClass($shortcode);
		$instance	= null;
			global $JSNPbElements;
        	$elements = $JSNPbElements->getElements();
			$instance = isset($elements[strtolower($class)]) ? $elements[strtolower($class)] : null;
			if (!is_object($instance)) {
	            $instance = new $class();
	        }
	        
	        // Process icon prepend title
	        if ( isset( $instance->items ) ) {
	        	$items = array_shift( $instance->items );
	        	foreach( $items as $i => $item ) {
	        		if ( ( isset( $item['role'] ) && isset( $item['role_type'] ) ) && ( $item['role'] == 'title_prepend' && $item['role_type'] == 'icon' ) ) {
	        			$arr_params  = JSNPagebuilderHelpersShortcode::shortcodeParseAtts( $params );
	        			$element     = JSNPagebuilderHelpersShortcode::shortcodeAtts( $instance->config['params'], $arr_params );
	        			if ( isset( $element['icon'] ) ) {
	        				$element_title = '<i class="' . $element['icon'] . '"></i>' . $element_title;
	        			}
	        		}
	        	}
	        }
	        
	        $content	= $instance->element_in_pgbldr('', $params, $element_title);
			echo $content;
		exit();
	}
	
	/**
	 * Method to generate shortcode preview
	 * 
	 * @return string
	 */
	public function preview()
	{
		$session = JFactory::getSession();
		JHtml::_('jquery.framework');
		JHtml::_('bootstrap.framework');
					
		$document = JFactory::getDocument();
				
		$document->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . 'js/joomlashine.noconflict.js', 'text/javascript');
		$document->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/bootstrap3/js/bootstrap.min.js', 'text/javascript' );
		
		$document->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/bootstrap3/css/bootstrap.min.css', 'text/css' );
		$document->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/jsn-gui-frontend.css', 'text/css' );
		$document->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/pagebuilder.css', 'text/css' );
		$document->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/front_end.css', 'text/css' );
		$document->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/front_end_responsive.css', 'text/css' );
		$document->addStyleSheet( JSNPB_ASSETS_URL . 'css/preview.css', 'text/css' );
		
		if (!$_POST['params']) {
			exit (JText::_("JSN_PAGEBUILDER_CONTROLLERS_SHORTCODE_CANNOT_FIND_INPUT_DATA"));
		}
		
		$shortcode_content	= urldecode($_POST['params']);
		$session->set('JSNPA_SHORTCODECONTENT', $shortcode_content);
		$helper				= new JSNPagebuilderHelpersBuilder();
		$html				=	$helper->generateShortCode($shortcode_content, false, 'frontend');
		echo '<div class="jsn-bootstrap3">' . $html . '</div>';
		
	}
	
	/**
	 * Load custom action for elements
	 * 
	 * @return void
	 */
	public function customAction() {
		$shortcode = isset( $_POST['shortcode'] ) ? $_POST['shortcode'] : '';
		$action = isset( $_POST['action'] ) ? $_POST['action'] : '';
	
		if ( ! empty( $shortcode ) && ! empty( $action ) ) {
			// Check file exists
			if ( file_exists( JPATH_ROOT . "/plugins/pagebuilder/{$shortcode}/helpers/{$action}.php" ) ) {
				require_once JPATH_ROOT . "/plugins/pagebuilder/{$shortcode}/helpers/{$action}.php";
			}
		}
		
		if ( ! empty( $shortcode ) && ! empty( $action ) ) {
			// Check file exists
			if ( file_exists( JPATH_ROOT . "/plugins/jsnpagebuilder/defaultelements/{$shortcode}/helpers/{$action}.php" ) ) {
				require_once JPATH_ROOT . "/plugins/jsnpagebuilder/defaultelements/{$shortcode}/helpers/{$action}.php";
			}
		}
	}
	
	/**
	 * Method to print element settings
	 */
	public function settings() {
		
		$js = '
			var JSNPbParams	= {pbstrings : {}};
			JSNPbParams.rootUrl = \'' . JUri::root() . '\';
			JSNPbParams.pbstrings.NO_ITEM_FOUND = \'' . JText::_( 'JSN_PAGEBUILDER_CONTROLLERS_SHORTCODE_NO_ITEM_FOUND' ) . '\';
			JSNPbParams.pbstrings.SINGLE_ENTRY = \'' . JText::_( 'JSN_PAGEBUILDER_CONTROLLERS_SHORTCODE_SINGLE_ENTRY' ) . '\';
			JSNPbParams.pbstrings.SETTINGS = \'' . JText::_( 'JSN_PAGEBUILDER_CONTROLLERS_SHORTCODE_SETTINGS' ) . '\';
			JSNPbParams.pbstrings.INVALID_LINK = \'' . JText::_( 'JSN_PAGEBUILDER_CONTROLLERS_SHORTCODE_THE_LINK_IS_INVALID' ) . '\';
			JSNPbParams.pbstrings.COPY = \'' . JText::_( 'JSN_PAGEBUILDER_CONTROLLERS_SHORTCODE_COPY' ) . '\';
			JSNPbParams.pbstrings.EMPTY = \'' . JText::_('JSN_PAGEBUILDER_CONTROLLERS_SHORTCODE_EMPTY') . '\';
			JSNPbParams.pbstrings.SELECT_DES_MARKER = \'' . JText::_( 'JSN_PAGEBUILDER_CONTROLLERS_SHORTCODE_SELECT_DES_MARKER' ) . '\';
			JSNPbParams.pbstrings.ALERT_DELETE_ROW = \'' . JText::_('JSN_PAGEBUILDER_CONTROLLERS_SHORTCODE_ALERT_DELETE_ROW') . '\';
			JSNPbParams.pbstrings.ALERT_DELETE_COLUMN = \'' . JText::_('JSN_PAGEBUILDER_CONTROLLERS_SHORTCODE_ALERT_DELETE_COLUMN') . '\';
			JSNPbParams.pbstrings.ALERT_DELETE_ELEMENT = \'' . JText::_('JSN_PAGEBUILDER_CONTROLLERS_SHORTCODE_ALERT_DELETE_ELEMENT') . '\';
		';
		JSNPagebuilderHelpersFunctions::print_asset_tag($js, 'js', null, true);
		$shortcode	= JRequest::getString('shortcode');
		
		$params		= isset($_POST['params']) ? $_POST['params'] : '';

		// TODO: move under assets inside shortcode
		// Add common js library for elements.
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-tipsy/jquery.tipsy.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-tipsy/tipsy.css', 'css');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-general.css', 'css'); // for accordion_item, buttonbar_item,
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-fonticomoon.css', 'css'); // for accordion_item, buttonbar_item,
		
		$extract_params     = '';
		$shortcodeHelper	= new JSNPagebuilderHelpersShortcode();
		$data			    = array();
		if (!empty($shortcode)) {
				// elements
				$class	= $shortcodeHelper->getShortcodeClass($shortcode);
				if (class_exists($class)) {
					// Get all regitered element shortcodes
					global $JSNPbElements;
	        		$elements = $JSNPbElements->getElements();
	        		
					$instance = isset($elements[strtolower($class)]) ? $elements[strtolower($class)] : null;
					
					// Init and register new instance if it not
					if (!is_object($instance))
						$instance = new $class();

                    $instance->backend_element_items();
                    if ($shortcode != "pb_row" && $shortcode != "pb_column") {
                        $instance->element_items_extra();
                    }
                    // recall this to re-extract params
                    $instance->shortcode_data();

					if (!empty($params)) {
						$params = stripslashes($params);
//						$params = urldecode($params);
					}else{						
						$params	= $instance->config['shortcode_structure'];
					}

					// Add neccessary assets for the shortcode
					$instance->backend_element_assets();

					if (!empty($params)) {
						$extract_params = JSNPagebuilderHelpersShortcode::extractParams($params, $shortcode);
						// if have sub-shortcode, get content
						if (!empty($instance->config['has_subshortcode'])) {
							$sub_sc_data = JSNPagebuilderHelpersShortcode::extractSubShortcode($params, true);
							$extract_params['sub_sc_content'] = $sub_sc_data;
						}
						// MODIFY $instance->items
						$shortcodeHelper->generateShortcodeParams($instance->items, NULL, $extract_params, TRUE);
						// recall this to re-extract params
						$instance->shortcode_data();
					}
					// get Modal setting box
					$settings 			= $instance->items;
					$shortcodeAterfix 	= substr($shortcode,-5);
					$showPreview 		= true;
					if($shortcodeAterfix == '_item'){
						$showPreview = false;
					}
					if($shortcode == 'pb_row'){
						$showPreview = false;
					}
					$modalContent 	= $shortcodeHelper->getShortcodeModalSettings($settings, $shortcode, $extract_params);
				}
		}
		$html[] = '';
		if($showPreview) $html[] = '<div id="jsn_column1" class="pull-left">';
		$html[] = '<div class="jsn-bootstrap" id="settings-form-container">
			<div id="modalOptions" class="form-horizontal modalOptions">
				' . $modalContent . '
				<div id="modalAction"></div>
			</div>
			<textarea class="hidden" id="shortcode_content" name="shortcode_content">' . $params . '</textarea>
			<textarea class="hidden" id="pb_share_data"  ></textarea>
			<textarea class="hidden" id="pb_merge_data"  ></textarea>
			<textarea class="hidden" id="pb_extract_data"  ></textarea>
			<input type="hidden" id="pb_previewing" value="0" />
			<input id="shortcode_name" name="shortcode_name" type="hidden" value="' . $shortcode . '" />
		</div>';
		
		if($showPreview) $html[] = '</div>';
		
		if($showPreview){
		$html[] = '<div id="jsn_column2" class="pull-left">
			<div class="preview_title">' . JText::_("JSN_PAGEBUILDER_CONTROLLERS_SHORTCODE_PREVIEW") . '</div>
			<div id="framePreview" class="preview_border">
				<div id="iframeLoading" class="iframe_loading_border"><div class="iframe_loading_image"><img src="components/com_pagebuilder/assets/images/icons-32/ajax-loader.gif"></div></div>
				<div class="control-group">
					<div id="preview_container">
						<iframe id="shortcode_preview_iframe" scrolling="auto" name="shortcode_preview_iframe" class="shortcode_preview_iframe" width="100%"></iframe>
						<div id="preview"></div>
					</div>
				</div>
			</div>
		</div>';
		}
		$html[] = '<div class="clearfix"></div>';
		print_r(implode('', $html));
		exit();
	}
	
}

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
 * Installer view
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPagebuilderViewShortcodesetting extends JSNBaseView
{
	public function display($tpl = null)
	{	
		JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/js/shortcodesetting/settings-handler.js');

		
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
		';
		JSNHtmlAsset::addInlineScript($js);
		$shortcode	= JRequest::getString('shortcode');
		$params		= '';
		// Get params from session then clear the session
		if (isset($_SESSION[JSNPB_SHORTCODE_SESSION_NAME][$shortcode]['params'])) {
			$params		= $_SESSION[JSNPB_SHORTCODE_SESSION_NAME][$shortcode]['params'];
			$params     = json_decode( $params );
			$_SESSION[JSNPB_SHORTCODE_SESSION_NAME][$shortcode]['params'] = '';
		}
		
		// TODO: move under assets inside shortcode
		// Add common js library for elements.
		JSNHtmlAsset::addScript(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-tipsy/jquery.tipsy.js');
		JSNHtmlAsset::addStyle(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-tipsy/tipsy.css');
		JSNHtmlAsset::addStyle(JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-general.css'); // for accordion_item, buttonbar_item,
		JSNHtmlAsset::addStyle(JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-fonticomoon.css'); // for accordion_item, buttonbar_item,

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
					
					if (!empty($params)) {
						$params = stripslashes($params);
						$params = urldecode($params);
					}else{						
						$params	= $instance->config['shortcode_structure'];
					}
					
					// Add neccessary assets for the shortcode
					$instance->backend_element_assets();
					
					// process require_js at backend setting
					if ( isset( $instance->config['exception']['require_js'] ) ) {
						$requires = $instance->config['exception']['require_js'];
						foreach ( $requires as $i => $require ) {
							if ( file_exists( JSNPB_ASSETS_PATH . $require ) ) {
								JSNHtmlAsset::addScript( JSNPB_ASSETS_PATH . $require );
							}
						}
					}
					
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
					
					$this->assign('content', $modalContent);
					$this->assign('params', $params);
					$this->assign('shortcodeName', $shortcode);
					$this->assign('showPreview', $showPreview);
				}			
		}	
		return parent::display();
	}
}
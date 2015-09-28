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

include_once JPATH_ROOT . '/administrator/components/com_pagebuilder/defines.pagebuilder.php';
jimport('joomla.plugin.plugin');

/**
 * Plugin system of JSN Pagebuilder
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class plgSystemPagebuilder extends JPlugin
{
	/**
	 * This method is to load neccessary access
	 * for PageBuilder need
	 * 
	 * @return void
	 */
	public function onBeforeRender()
	{
		$app = JFactory::getApplication();
        $tpl = $app->input->getInt('tp', 0);
		if ($app->isAdmin() || $tpl) return;

		// Get requested component, view and task
		$option		= $app->input->getCmd('option', '');
		$view		= $app->input->getCmd('view', '');
		$layout		= $app->input->getCmd('layout', '');
		$user		= JFactory::getUser();
		
		if ($app->isSite() && $option == 'com_content' && $view == 'form' && $layout == 'edit' && $user->get('id') > 0)
		{
			return;
		}	

		$doc 	= JFactory::getDocument();
		if (get_class($doc) != "JDocumentHTML") return;

		if ($app->isSite() && $option == 'com_k2' && $view == 'item' && $app->input->getInt('id', 0)) {
			if (file_exists(JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/shortcode.php')) {
				if (class_exists('K2HelperUtilities'))
				{
					include_once JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/shortcode.php';
					$shortCodeRegex = JSNPagebuilderHelpersShortcode::getShortcodeRegex();
					JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_k2/models');
					$K2ModelItem = JModelLegacy::getInstance('k2modelitem');
					$k2Item = $K2ModelItem->getData();
					if (count($k2Item))
					{
						$metaDescItem = preg_replace("#{(.*?)}(.*?){/(.*?)}#s", '', $k2Item->introtext.' '.$k2Item->fulltext);
						$metaDescItem = strip_tags($metaDescItem);
						$k2params = K2HelperUtilities::getParams('com_k2');
						$metaDescItem = self::removeShortCode($metaDescItem, $shortCodeRegex);
						$metaDescItem = K2HelperUtilities::characterLimit($metaDescItem, $k2params->get('metaDescLimit', 150));

						if ($doc->getMetaData('og:description') != null) {
							$doc->setMetaData('og:description', $metaDescItem);
						}
						if ($doc->getDescription() != '') {
							$doc->setDescription($metaDescItem);
						}
					}
				}
			}
		}
		
		// Check if JoomlaShine extension framework is enabled?
		$framework = JTable::getInstance('Extension');
		$framework->load(
			array(
				'element'	=> 'jsnframework',
				'type'		=> 'plugin',
				'folder'	=> 'system'
			)
		);
		
		// Do nothing if JSN Extension framework not found.
		if ( !$framework->extension_id ) return;
		
		// Get PageBuilder configuration.
		$params 		= JSNConfigHelper::get('com_pagebuilder');
		// Check if it's enabled or not.
		$isEnabled		= $params->get('enable_pagebuilder', 1);
		
		// Do nothing if PageBuilder not enabled;
		if ( !$isEnabled ) {} ;
		
		// Register autoloaded classes
		JSN_Loader::register(JSNPB_ADMIN_ROOT . '/helpers' , 'JSNPagebuilderHelpers');
		JSN_Loader::register(JSNPB_ADMIN_ROOT . '/helpers/shortcode' , 'JSNPBShortcode');
		//JSN_Loader::register(JPATH_ROOT . '/plugins/pagebuilder/' , 'JSNPBShortcode');
		//JSN_Loader::register(JPATH_ROOT . '/administrator/components/com_pagebuilder/elements/' , 'JSNPBShortcode');
		JSN_Loader::register(JPATH_ROOT . '/plugins/jsnpagebuilder/defaultelements/' , 'JSNPBShortcode');
		
		/*
		 * Move all css files of PageBuilder
		 * to the end of css list
		 * 
		 */
		$data	= $doc->getHeadData();
		$styleSheetList	=	$data['styleSheets'];
		$_tmpList		= 	array();
		if (count($styleSheetList)) {			
			foreach ($styleSheetList as $cssUrl=>$css) {
				// Check if the file belongs to PageBuilder
				if (strpos($cssUrl, 'plugins/pagebuilder/') !== false || strpos( $cssUrl, 'com_pagebuilder') !== false) {
					$_tmpList[$cssUrl]	= $css;
					unset($styleSheetList[$cssUrl]);
				}	
			}
		}
		
		$styleSheetList	= array_merge($styleSheetList, $_tmpList);
		
		$data['styleSheets']	= $styleSheetList;
		$doc->setHeadData($data);
	}
	
	/**
	 * Check the whole site content then replace found
	 * PageBuilder shortcodes by Elements
	 *
	 * @return  Changed HTML format
	 */
	public function onAfterRender()
	{
		// Get requested component, view and task
		$app        = JFactory::getApplication();
        $tpl        = $app->input->getInt('tp', 0);
		$option		= $app->input->getCmd('option', '');
		$view		= $app->input->getCmd('view', '');
		$layout		= $app->input->getCmd('layout', '');
		$user		= JFactory::getUser();
		// Remove scrollspy jQuery conflict
		if ($app->isAdmin() && $option == 'com_pagebuilder')
		{
			if ( $view == 'configuration')
			{
				$html = $app->getBody();

				if (preg_match_all("/\\$\('\.subhead'\)\.scrollspy\(\{[^\r\n]+\}\);/", $html, $matches, PREG_SET_ORDER))
				{
					$html = preg_replace("/\\$\('\.subhead'\)\.scrollspy\(\{[^\r\n]+\}\);/", '',  $html);
					$app->setBody($html);
				}
			}
		}

        if ($app->isAdmin() || $tpl) return;
		
		$doc 			= JFactory::getDocument();	
		if (get_class($doc) != "JDocumentHTML") return;		

		if ($app->isSite() && $option == 'com_content' && $view == 'form' && $layout == 'edit' && $user->get('id') > 0)
		{
			return;
		}
		
		// Check if JoomlaShine extension framework is enabled?
		$framework = JTable::getInstance('Extension');
		$framework->load(
			array(
				'element'	=> 'jsnframework',
				'type'		=> 'plugin',
				'folder'	=> 'system'
			)
		);
		
		// Do nothing if JSN Extension framework not found.
		if ( !$framework->extension_id ) return;
		
		// Require base shorcode element		
		require_once JSNPB_ADMIN_ROOT . '/libraries/innotheme/shortcode/element.php';
		require_once JSNPB_ADMIN_ROOT . '/libraries/innotheme/shortcode/parent.php';
		require_once JSNPB_ADMIN_ROOT . '/libraries/innotheme/shortcode/child.php';
		
		
		global $JSNPbElements;
		$JSNPbElements		= new JSNPagebuilderHelpersElements();
		
		// Get PageBuilder configuration.
		$params 			= JSNConfigHelper::get('com_pagebuilder');
		// Check if it's enabled or not.
		$isEnabled			= $params->get('enable_pagebuilder', 1);
		
		// Do nothing if PageBuilder not enabled;
		if ( !$isEnabled ) {} ;
					
		$data	= $doc->getHeadData();		
		
		JHtml::_('jquery.framework');
		$doc->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . 'js/joomlashine.noconflict.js', 'text/javascript');
		$doc->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/bootstrap3/js/bootstrap.min.js', 'text/javascript' );
		
		$doc->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/bootstrap3/css/bootstrap.min.css', 'text/css' );
        $doc->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/pagebuilder.css', 'text/css' );
		$doc->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/jsn-gui-frontend.css', 'text/css' );
		$doc->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/front_end.css', 'text/css' );
		$doc->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/front_end_responsive.css', 'text/css' );
		
		// Store the assets before transforming.
		$inlineScriptBefore	= isset($data['script']['text/javascript']) ? $data['script']['text/javascript'] : '';
		$inlineStyleBefore	= isset($data['style']['text/css']) ? $data['style']['text/css'] : '';
		$scriptsBefore	= $data['scripts'];
		$styleSheetsBefore	= $data['styleSheets'];
		
		$scriptCount		= count($scriptsBefore);
		$styleSheetCount	= count($styleSheetsBefore);
	
		// Analyze page content and use PageBuilder to
		// transform code if Pb structure found.
	
		// Get the responsed body
		$content	= JResponse::getBody();
		// preg_replace falsely process $ symobols as commands in text copy.
		$content    = str_replace('$', '&dollar;', $content);
	
		$body_content = '';
		preg_match("/<body.*\/body>/si", $content, $body_content);
		
		if (!isset($body_content[0])) return;
		
		$body_content   = $body_content[0];
		
		$helper				= new JSNPagebuilderHelpersBuilder();
		// Transform the content inside body tag only
		$body_content	=	$helper->generateShortCode($body_content, false, 'frontend');
		// Apply the body content into page content
		$content = preg_replace("/(<body.*\/body>)/si", $body_content, $content);
		 
		/*
		 * Arrange the assets loaded from PageBuilder
		 * Because onAfterRender not accept add assets by JFactory::getDocument()
		 * so we need under code to modify document's header 
		 */
		$data	= $doc->getHeadData();
		
		$inlineScriptAfter	= isset($data['script']['text/javascript']) ? $data['script']['text/javascript'] : '';
		$inlineStyleAfter	= isset($data['style']['text/css']) ? $data['style']['text/css'] : '';
		$scriptsAfter		= $data['scripts'];
		$styleSheetsAfter	= $data['styleSheets'];
		
		// Separate assets of page builder.
		$pbInlineScript		= str_replace($inlineScriptBefore, '', $inlineScriptAfter);
		$pbInlineStyle		= str_replace($inlineStyleBefore, '', $inlineStyleAfter);
		$pbScripts			= array_splice($scriptsAfter, $scriptCount);
		$pbStyleSheets		= array_splice($styleSheetsAfter, $styleSheetCount);
	
		// Append PageBuilder's assets
		// Only support css file with type is "text/css"
		// and js type with type is "text/javascript"
		// in this period.
		$pbAssets		= array();
		if (count($pbStyleSheets)) {
			foreach ($pbStyleSheets as $css=>$v){
				$pbAssets[]	= '<link rel="stylesheet" href="' . $css . '" type="text/css" />';
			}
		}
		
		if (count($pbScripts)) {
			foreach ($pbScripts as $js=>$v) {
				$pbAssets[]	= '<script src="' . $js . '" type="text/javascript"></script>';
			}
		}
		$pbAssets[]		= '<script type="text/javascript">' . $pbInlineScript . '</script>';
		$pbAssets[]		= '<style>' . $pbInlineStyle . '</style>';
		
		$pbAssets		= implode("\n", $pbAssets);
		
		// Append assets to content
		$content		= str_replace("</head>", $pbAssets . "\n</head>", $content);
		// preg_replace falsely process $ symobols as commands in text copy.
		$content = str_replace('&dollar;', '$', $content);
		JResponse::setBody($content);
	}

	private static function removeShortCode($text, $shortCodeRegex) {
		if (is_array($text)) {
			$removeShortCodeText = "";
			foreach ($text as $_text) {
				preg_match_all("/" . $shortCodeRegex . "/s", $_text, $tmp_params, PREG_PATTERN_ORDER);
				if (count($tmp_params[5]) > 0) {
					$removeShortCodeText .= self::removeShortCode($tmp_params[5], $shortCodeRegex);
				} else {
					$removeShortCodeText .= $_text;
				}
			}
			return $removeShortCodeText;
		} else {
			preg_match_all("/" . $shortCodeRegex . "/s", $text, $tmp_params, PREG_PATTERN_ORDER);
			if (count($tmp_params[5]) > 0) {
				return self::removeShortCode($tmp_params[5], $shortCodeRegex);
			} else {
				return $text;
			}
		}
	}
}

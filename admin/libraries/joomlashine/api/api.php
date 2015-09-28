<?php
/**
 * @version    $Id$
 * @package    JSN_Framework
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class JSNPageBuilderAPI 
{
	private $_shortcodeSyntax = '#\[(\[?)(pb_row)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)#';
	
	public function process($content)
	{	
		preg_match_all($this->_shortcodeSyntax, $content, $out);
		
		if (count($out[0]))
		{
			$elemenPath = JPATH_ROOT . '/administrator/components/com_pagebuilder/libraries/innotheme/shortcode/element.php';
			$childPath	= JPATH_ROOT . '/administrator/components/com_pagebuilder/libraries/innotheme/shortcode/child.php';
			$parentPath	= JPATH_ROOT . '/administrator/components/com_pagebuilder/libraries/innotheme/shortcode/parent.php';
			
			if (file_exists($elemenPath) && file_exists($childPath) && file_exists($parentPath))
			{
				include_once $elemenPath;
				include_once $parentPath;
				include_once $childPath;
				
				// Autoload all helper classes.
				JSN_Loader::register(JPATH_ROOT . '/administrator/components/com_pagebuilder' , 'JSNPagebuilder');
				
				// Autoload all shortcode
				JSN_Loader::register(JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/shortcode' , 'JSNPBShortcode');
				
				// Backward compatible for all JSN PageBuilder version =< 1.0.4
				if (is_dir(JPATH_ROOT . '/administrator/components/com_pagebuilder/elements'))
				{
					JSN_Loader::register(JPATH_ROOT . '/administrator/components/com_pagebuilder/elements/' , 'JSNPBShortcode');
				}
				else
				{
					JSN_Loader::register(JPATH_ROOT . '/plugins/jsnpagebuilder/defaultelements/' , 'JSNPBShortcode');
				}

				global $JSNPbElements;
				$pcontent = '';
					
				$this->addScript();
				$JSNPbElements							= new JSNPagebuilderHelpersElements();
				$objJSNPagebuilderHelpersBuilder 		= new JSNPagebuilderHelpersBuilder();
				$objJSNPagebuilderHelpersShortcode   	= new JSNPagebuilderHelpersShortcode();
				$content								= $objJSNPagebuilderHelpersShortcode::removeAutop($content);
					
					
				$pcontent .= $objJSNPagebuilderHelpersBuilder->generateShortCode($content);
				$content = '<div id="jsnpa-pagebuilder-form-container" class="jsn-layout">' . $pcontent . '</div>';
								
			}
		}
		
		return $content;
	}
	
	public function getShortcodeSyntax()
	{
		return $this->_shortcodeSyntax;
	}
	
	public function addScript()
	{
		$jscode = $this->_javascript();
		if ($jscode != '')
		{
			$document	= JFactory::getDocument();
			$document->addScriptDeclaration($jscode);
		}		
	}	
	
	public function _javascript()
	{
		$jscode = '(function($){ 
					$.JSNPAPageBuilder = function(options) {
						this.options  			= $.extend({}, options);
					
						this.initialize = function ()
						{
							var self = this;
							this.wrapper = $("#jsnpa-pagebuilder-form-container");
							this.maxWidth = this.wrapper.width() ;
							self.updateColumnWidth(self, this.wrapper, this.maxWidth);
						};
						
						this.updateColumnWidth = function(self, wapper, maxWidth)
						{
							wapper.find(".jsn-row-container").each(function() {
				                var countColumn = $(this).find(".jsn-column-container").length;                
				                self.updateColumnSpanWidth(countColumn, maxWidth, $(this));
				            });
				        };
				
				        // Update span width of columns in each row
				        this.updateColumnSpanWidth = function(countColumn, totalWidth, parentForm) {
				            var seperateWidth = countColumn * 12;
				            var remainWidth = totalWidth - seperateWidth;
				            parentForm.find(".jsn-column-container").each(function () {
				                var selfSpan = $(this).find(".jsn-column-content").attr("data-column-class").replace("span","");
				                var columnWidth = parseInt(selfSpan)*remainWidth/12;
				                if(columnWidth >= totalWidth) columnWidth = totalWidth - 12;
				                $(this).find(".jsn-column").css("width", columnWidth + "px");
				            });
				        };
					}
					
					$(window).load(function ()
					{
						var JSNPAPageBuilder 	= new $.JSNPAPageBuilder();
						JSNPAPageBuilder.initialize();	
					});

					$(document).ready(function ()
					{
						var JSNPAPageBuilder 	= new $.JSNPAPageBuilder();
						JSNPAPageBuilder.initialize();	
					});
				})((typeof JoomlaShine != "undefined" && typeof JoomlaShine.jQuery != "undefined") ? JoomlaShine.jQuery : jQuery);';
		return $jscode;			
	}	
}
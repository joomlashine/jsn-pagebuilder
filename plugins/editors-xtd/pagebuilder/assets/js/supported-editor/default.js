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

/**
 * The helper for CodeMirror editor
 */

(function ($){
	$.JSNPbEditorHelper	= function (){
		var JSNPbEditorHelper	= this;
		/**
		 * Inject the entry buttons group into editor
		 */
		JSNPbEditorHelper.initEntryButtons	= function (source_id, pbEntryButtons){
			var container		=	$('#' + source_id).parent();
			var buttonGroups	= container.prepend(pbEntryButtons);
			var wrapperOverflow	= $('#' + source_id).parents('.editor').css('overflow');
			
			$('.switchmode-button.pb-on', buttonGroups).on('click',  function (){
				if (!$(this).hasClass('active')) {
					switchPagebuilder(source_id, 'on');
					$('.switchmode-button', buttonGroups).removeClass('active').removeClass('btn-success');
					$(this).addClass('active').addClass('btn-success');
					
					// Fix overlowed wrapper problem on Joomla! 3.2.2
					$('#' + source_id).parents('.editor').css('overflow', 'inherit');
				}
			});
			
			$('.switchmode-button.pb-off', buttonGroups).on('click',  function (){
				if (!$(this).hasClass('active')) {
					switchPagebuilder(source_id, 'off');
					$('.switchmode-button', buttonGroups).removeClass('active').removeClass('btn-success');
					$(this).addClass('active').addClass('btn-success');
					
					// Revert wrapper overflow status
					$('#' + source_id).parents('.editor').css('overflow', wrapperOverflow);
				}
			});
			return buttonGroups;
		},
		
		/**
		 * Create the container object for original editor
		 */
		JSNPbEditorHelper.getContainer	= function (source_id){
			var container	=	'<div class="jsnpb_container"></div>';			
		},
		
		/**
		 * Method to hide active editor and its related elements
		 */
		JSNPbEditorHelper.hideEditor	= function (container, source_id){
			$('#' + source_id + ', #editor-xtd-buttons, .toggle-editor', container).addClass('editor-hidden').hide();	
		},
		
		/**
		 * Method to transform PageBuilder to source code
		 * @param source jQuery Object - source object
		 * @param builder_wrapper jQuery Object- PageBuilder wrapper object
		 */
		JSNPbEditorHelper.transformToSource	= function (source, builder_wrapper){
			JSNPbEditorHelper.updateSource(source);
            
            $('.editor-hidden').removeClass('editor-hidden').show();
            builder_wrapper.remove();
		}
		
		/**
		 * Method to update content when change is made on UI
		 * 
		 */
		JSNPbEditorHelper.updateSource	= function(source){
			var content = '';
            $("#form-container textarea[name^='shortcode_content']").each(function(){
            	content += $(this).text();
            });
            
            source.html(content);
            source.val(content);
            return content;
		}
		
		return JSNPbEditorHelper;
	}
})(JoomlaShine.jQuery);
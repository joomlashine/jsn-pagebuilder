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
 * Custom script for Audio element
 */
JoomlaShine.jQuery( function ($){
	$.JSNColorPicker = $.JSNColorPicker || {};
	$.ShortcodeSetting = $.ShortcodeSetting || {};
	
	$(document).ready(function () {
		$.ShortcodeSetting.initSelectImage($('#modalOptions'));
        $('body').bind('pb_modal_loaded', function(e, modal) {
        	new $.JSNColorPicker('#modalOptions .color-selector', modal);
        });
		
		var video_source		= $('#param-video_sources', $('#modalOptions'));
		var local_file	= $('#param-video_source_local', $('#modalOptions'));
		var youtube		= $('#param-video_source_link_youtube', $('#modalOptions'));
		var vimeo		= $('#param-video_source_link_vimeo', $('#modalOptions'));
		
		video_source.select2({minimumResultsForSearch:-1});

		// Fix horizon scrollbar
		video_source.css('display', 'none');
		$('.select2-offscreen', $('#parent-param-video_sources')).css('display', 'none');

		// Hide Show List param for youtube
		$('#parent-param-video_youtube_show_list', $('#modalOptions')).removeClass('pb_hidden_depend').addClass('pb_hidden_depend');

		// Trigger change then validate file when Youtube link changed.
		youtube.on('change', function (){
			validate_file();
		});

		// Trigger change then validate file when Vimdeo link changed.
		vimeo.on('change', function (){
			validate_file();
		});

		if (youtube.val()){
			validate_file();
		}else{
			youtube.parent().removeClass('input-append');
		}

		if (vimeo.val()){
			validate_file();
		}else{
			vimeo.parent().removeClass('input-append');
		}

		var audioxhr;
		function validate_file()
		{
			var _video_source	= video_source.val();
			var file_type	= 'youtube';
			var obj;
			if (_video_source == 'youtube') {
				obj		= youtube;
			}else if (_video_source == 'vimeo'){
				obj		= vimeo;
				file_type	= 'vimeo';
			}

			$('span.add-on', obj.parent()).remove();
			if (!obj.val()) {
				obj.parent().removeClass('input-append');
				return;
			}
			if(audioxhr && audioxhr.readystate != 4){
				audioxhr.abort();
	        }
			obj.parent().addClass('input-append');

			obj.after($('<span class="add-on"></span'));
			var loading_icon	= $('<i class="audio-validate jsn-icon16 jsn-icon-loading" ></i>');
			var ok_icon			= $('<i class="audio-validate icon-ok pb-label-des-tipsy" ></i>');
			var ban_icon		= $('<i class="audio-validate icon-warning pb-label-des-tipsy" title="'+JSNPbParams.pbstrings.INVALID_LINK+'"></i>');
			$('#modalOptions .audio-validate').remove();
			obj.next('.add-on').append(loading_icon);
			audioxhr	= $.post(
				JSNPbParams.rootUrl + 'administrator/index.php?option=com_pagebuilder&task=shortcode.customAction',
	            {
	                shortcode 	: 'video',
	                action 		: 'validate_file',
	                file_url	: obj.val(),
	                file_type	: file_type
	            }
            ).done(function (data) {
            	if (data === 'false') {
            		$('#modalOptions .audio-validate').remove();
            		loading_icon.remove();
            		obj.next('.add-on').append(ban_icon);
            	}else{
            		$('#modalOptions .audio-validate').remove();
            		loading_icon.remove();
            		obj.next('.add-on').append(ok_icon);
            		var res		= $.parseJSON(data);
            		$(ok_icon).attr('title', res.content);
            		// unhide "Show List" parameter if detected video url had list param
            		if (res.type == 'list') {
            			$('#parent-param-video_youtube_show_list', $('#modalOptions')).removeClass('pb_hidden_depend');
            		}else{
            			$('#parent-param-video_youtube_show_list', $('#modalOptions')).removeClass('pb_hidden_depend').addClass('pb_hidden_depend');
            		}
            	}

            	$('#modalOptions .audio-validate').tipsy({
                    gravity: 'e',
                    html: true,
                    fade: true
                });

            });
		}
	});
});
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
		var audio_source		= $('#param-audio_sources', $('#modalOptions'));
		var soundcloud_file	= $('#param-audio_source_link', $('#modalOptions'));
		var local_file	= $('#param-audio_source_local', $('#modalOptions'));
		audio_source.select2({minimumResultsForSearch:-1});
		// Fix horizon scrollbar
		audio_source.css('display', 'none');
		$('.select2-offscreen', $('#parent-param-audio_sources')).css('display', 'none');

		$('#parent-param-audio_start_track', $('#modalOptions')).removeClass('pb_hidden_depend').addClass('pb_hidden_depend');


		if ($('option:selected', audio_source).length > 0) {
			var audio_source_value	= $('option:selected', audio_source)[0].value;
			if (audio_source_value == 0){
				$('#modalOptions .jsn-tabs').hide();
			}
		}

		audio_source.on('change', function (e){
			$.ShortcodeSetting.updateState(0);
			if ($('option:selected', audio_source).length > 0) {
				var audio_source_value	= $('option:selected', audio_source)[0].value;

				if (audio_source_value == 0){
					$('#modalOptions .jsn-tabs').hide();
				}else{
					$('#modalOptions .jsn-tabs').show();
				}
			}else{
				$('#modalOptions .jsn-tabs').show();
			}


		});


		if (soundcloud_file.val()){
			validate_file();
		}else{
			soundcloud_file.parent().removeClass('input-append');
		}

		soundcloud_file.on('change', function (){
			validate_file();
		});

		var audioxhr;
		function validate_file()
		{
			$('span.add-on', soundcloud_file.parent()).remove();
			if (!soundcloud_file.val()) {
				soundcloud_file.parent().removeClass('input-append');
				return;
			}
			if(audioxhr && audioxhr.readystate != 4){
				audioxhr.abort();
			}
			soundcloud_file.parent().addClass('input-append');

			soundcloud_file.after($('<span class="add-on"></span'));
			var loading_icon	= $('<i class="audio-validate jsn-icon16 jsn-icon-loading" ></i>');
			var ok_icon			= $('<i class="audio-validate icon-ok pb-label-des-tipsy" ></i>');
			var ban_icon		= $('<i class="audio-validate icon-warning pb-label-des-tipsy" title="'+JSNPbParams.pbstrings.INVALID_LINK+'"></i>');
			$('#modalOptions .audio-validate').remove();
			soundcloud_file.next('.add-on').append(loading_icon);
			audioxhr	= $.post(
				JSNPbParams.rootUrl + 'administrator/index.php?option=com_pagebuilder&task=shortcode.customAction',
				{
					shortcode 	: 'audio',
					action		: 'validate_file',
					file_url	: soundcloud_file.val()
				}

			).done(function (data) {
					if (data === 'false') {
						$('#modalOptions .audio-validate').remove();
						loading_icon.remove();
						soundcloud_file.next('.add-on').append(ban_icon);
					}else{
						$('#modalOptions .audio-validate').remove();
						loading_icon.remove();
						soundcloud_file.next('.add-on').append(ok_icon);
						var title	= '';
						var res		= $.parseJSON(data);
						$(ok_icon).attr('title', res.content);
						if (res.type != 'list') {
							$('#parent-param-audio_start_track', $('#modalOptions')).removeClass('pb_hidden_depend').addClass('pb_hidden_depend');
						}else{
							$('#parent-param-audio_start_track', $('#modalOptions')).removeClass('pb_hidden_depend');
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
} );
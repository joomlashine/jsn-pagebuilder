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
 * Custom script for QRCode element
 */
(function ($) {
	
	"use strict";
	
	$.JSNQRCode = $.JSNQRCode || {};
	
	$.JSNQRCode = function () {
		// QR Code element process
        $('#param-qr_content_area').on('change', function () {
        	var html = $(this).val();
        	html = html.replace(/&/g, '');
        	html = html.replace(/$/g, '');
        	html = html.replace(/#/g, '');
        	var encode_html = html.replace(/"/g, '<pb_quote>');
        	$('#param-qr_content_area').val(html.substring(0, 1200));
        	$('#param-qr_content').val(encode_html);
        });
        $('#param-qr_content_area').trigger('change');
	}
	
	$(document).ready(function () {
		$.JSNQRCode();
	});
	
})(JoomlaShine.jQuery)
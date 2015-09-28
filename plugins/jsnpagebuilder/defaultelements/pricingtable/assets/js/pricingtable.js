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
 * Custom script for pricing table item element
 */
(function ($) {
	'use strict';

	$.Pb_Pricing_Tbl = $.Pb_Pricing_Tbl || {};

	$.Pb_Pricing_Tbl = function (options) {
		var self = this;
		// Object parameters
		this.options = $.extend({}, options);

		var options_ = this.options;

		// Get wrapper
		this.wrapper = options_.wrapper;

		// Get edit button
		this.button = options_.button;

	}

	$.Pb_Pricing_Tbl.prototype = {

		// Get Attributes list
		get_attibutes: function () {
			var attributes_content = [];
			if ($('#param-prtbl_attr').length) {
				$('#param-prtbl_attr').find("[name^='shortcode_content']").each(function (i) {
					attributes_content[i] = $(this).val();
				});
			}

			return attributes_content.join('--[pb_seperate_sc]--');
		}
	}

	$(document).ready(function () {
		var $pb_pricing_item = new $.Pb_Pricing_Tbl({
			wrapper: $('body'),
			button : '#param-prtbl_items .element-edit'
		});

		$('body').on('filter_shortcode_data', function( e ){
			var attributes = $pb_pricing_item.get_attibutes();
			//$.HandleElement.removeCookie('Pb_data_for_modal');
			//$.HandleElement.setCookie('Pb_data_for_modal', attributes);
		});
	});

})(jQuery);
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

/**
 * Custom script for List element
 */
( function ($)
{
	"use strict";

	$.JSNLTSelect = $.JSNLTSelect || {};

	$.JSNLTSelect = function () {
		$('#param-link_type').on('change', function () {
            var option_text = $("#param-link_type option:selected").text();
            var option_val = $("#param-link_type option:selected").val();
            var label = $("#parent-param-single_item").children('.control-label');
            label.html(JSNPbParams.pbstrings.SINGLE_ENTRY.replace('%s', option_text));

            var controls = $("#parent-param-single_item").children('.controls');
            var visibleChild = controls.children("[data-depend-value='"+option_val+"']");
            if($.trim(visibleChild.html()) == ''){
                visibleChild.html('<label style="margin-top: 6px;">'+JSNPbParams.pbstrings.NO_ITEM_FOUND.replace('%s', option_text.toLowerCase())+'</label>');
            }
		});
	}

	$(document).ready(function () {
		$.JSNLTSelect();
	});

})(JoomlaShine.jQuery);
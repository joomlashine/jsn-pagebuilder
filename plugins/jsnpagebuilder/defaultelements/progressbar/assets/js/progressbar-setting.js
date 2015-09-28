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
 * Custom script for ProgressBar element
 */
(function ($) {
	
	"use strict";
	
	$.JSNProgressbar = $.JSNProgressbar || {};
	
	$.JSNProgressbar = function (modal) {
		$('#param-progress_bar_style').on('change', function () {
            var selectValue = $(this).val();

            if ( selectValue ) {
                var shortcodes = $('#group_elements .jsn-item textarea');
                var total = 0;
                shortcodes.each(function () {
                    var shortcode_str = $(this).html();
                    var result 	= shortcode_str.replace(/pbar_group="[a-z\-]+"/g, 'pbar_group="' + selectValue + '"');
                    var match 	= shortcode_str.match(/\b([0-9]+)\b/g);
                    total += parseInt(match[1]);
                    $(this).html(result);
                });
                
                // Progress total percentage
                if(selectValue == 'stacked') {
                    if (total > 100) {
                        shortcodes.each(function () {
                            var shortcode_str = $(this).html();
                            var match = shortcode_str.match(/\b([0-9]+)\b/g);
                            var percent = parseInt(match[1]) / (total / 100);
                            var result = shortcode_str.replace(/pbar_percentage="[0-9]+"/g, 'pbar_percentage="' + percent + '"');
                            $(this).html(result);
                        });
                    }
                }
            }
            
        });
		
		$('#param-progress_bar_style').trigger('change');
	}
	
	$(document).ready(function () {
        $('body').bind('pb_modal_loaded', function(e, modal) {
        	$.JSNProgressbar(modal);
        });
		
	});
	
})(JoomlaShine.jQuery)
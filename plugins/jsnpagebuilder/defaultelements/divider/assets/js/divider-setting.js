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

(function ($) {
	"use strict";
	
	$.JSNColorPicker = $.JSNColorPicker || {};
	
	$(document).ready(function () {
        $('body').bind('pb_modal_loaded', function(e, modal) {
        	new $.JSNColorPicker('#modalOptions .color-selector', modal);
        });
		
	});
	
})(JoomlaShine.jQuery);
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

    $.ShortcodeSetting = $.ShortcodeSetting || {};
    $.JSNColorPicker = $.JSNColorPicker || {};
    $(document).ready(function () {
        $('body').bind('pb_modal_loaded', function(e, modal) {
        	new $.JSNColorPicker('#modalOptions .color-selector', modal);
        });

        // Update preview when select icon
        $('#modalOptions').delegate('.jsn-iconselector .jsn-items-list .jsn-item', 'click', function () {
            var parent_tab = $(this).parents('.pb-setting-tab');
            var stop_reload_iframe = ((parent_tab.length > 0 && parent_tab.is("#styling")) || (parent_tab.length > 0 && parent_tab.is("#modalAction"))) ? 0 : 1;

            $.ShortcodeSetting.shortcodePreview(null, null, null, null, stop_reload_iframe);

        })

        // Limit percent input
        $('#modalOptions #param-percent').on('change', function () {
            var percent_value = parseInt($(this).val());
            if (percent_value > 100) {
                $(this).val('100')
            }
        })
    });

})(JoomlaShine.jQuery);
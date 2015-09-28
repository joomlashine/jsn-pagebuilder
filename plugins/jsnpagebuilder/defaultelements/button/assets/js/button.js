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

( function ($) {
    "use strict";
    $.ShortcodeSetting = $.ShortcodeSetting || {};

    $(window).load(function () {
        // Update preview when select icon
        $( '#modalOptions' ).delegate( '.jsn-iconselector .jsn-items-list .jsn-item', 'click', function () {
            var parent_tab = $(this).parents('.wr-pb-setting-tab');
            var stop_reload_iframe = ((parent_tab.length > 0 && parent_tab.is("#styling")) || (parent_tab.length > 0 && parent_tab.is("#modalAction"))) ? 0 : 1;

            $.ShortcodeSetting.shortcodePreview(null, null, null, null, stop_reload_iframe);
        });
        $( ".pb-button-fancy" ).fancybox( {
            "width"		: "75%",
            "height"	: "75%",
            "autoScale"	: false,
            "transitionIn"	: "elastic",
            "transitionOut"	: "elastic",
            "type"		: "iframe"
        } );
    });

})(JoomlaShine.jQuery);

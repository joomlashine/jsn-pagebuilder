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
 * Custom script for Row
 */
(function ($) {
    "use strict";

    $.ShortcodeSetting = $.ShortcodeSetting || {};

    $.JSNColorPicker = $.JSNColorPicker || {};

    function hidePosition(value) {
        if (value == 'full') {
            $('#parent-param-position').addClass('pb_hidden_depend2');
        } else {
            $('#parent-param-position').removeClass('pb_hidden_depend2');
        }
    }

    $(document).ready(function () {
        $('body').bind('pb_modal_loaded', function(e, modal) {
        	new $.JSNColorPicker('#modalOptions .color-selector', modal);
        });
        $.ShortcodeSetting.initSelectImage($('#modalOptions'));

        $('#param-background').change(function () {
            var value = $(this).val();
            if (value == 'image') {
                value = $('#parent-param-stretch button.active').attr('data-value');
                hidePosition(value);
            }
        });
        $('#param-background').trigger('change');
        $('#parent-param-stretch button').click(function () {
            var value = $(this).attr('data-value');
            hidePosition(value);
        });
        $('#parent-param-parallax').on('click', function () {
            if ($('input[name=param-parallax]:checked').val() == 'no') {
                $('#parent-param-parallax_scroll').addClass('pb_hidden_depend');
                $('#parent-param-parallax_scroll_direction').addClass('pb_hidden_depend');
            } else {
                $('#parent-param-parallax_scroll').removeClass('pb_hidden_depend');
                $('#parent-param-parallax_scroll_direction').removeClass('pb_hidden_depend');
            }
        })
    });

})(JoomlaShine.jQuery);
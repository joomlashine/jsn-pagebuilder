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
 * Custom script for List element
 */
(function ($) {
    "use strict";

    $.JSNList = $.JSNList || {};

    $.JSNSelectFonts = $.JSNSelectFonts || {};

    $.JSNColorPicker = $.JSNColorPicker || {};

    $.JSNList = function () {
        new $.JSNSelectFonts();
        $('body').bind('pb_modal_loaded', function(e, modal) {
        	new $.JSNColorPicker('#modalOptions .color-selector', modal);
        });
        $('#param-font').on('change', function () {
            if ($(this).val() == 'inherit') {
                $('#param-font_face_type').val('standard fonts');
                $('.jsn-fontFaceType').trigger('change');
                $('#param-font_size_value_').val('');
                $('#param-font_style').val('bold');
                $('#param-color').val('#000000');
                $('#color-picker-param-color').ColorPickerSetColor('#000000');
                $('#color-picker-param-color div').css('background-color', '#000000');
            }
        });
    }

    $(document).ready(function () {
        $.JSNList();
        $('#parent-param-show_heading').on('click', function () {
            if ($('select[name=param-font] option:selected').val() == 'custom') {
                if ($('input[name=param-show_heading]:checked').val() == 'no') {
                    $('#parent-param-font-family').addClass('pb_hidden_depend');
                    $('#parent-param-font-attributes').addClass('pb_hidden_depend');
                } else {
                    $('#parent-param-font-family').removeClass('pb_hidden_depend');
                    $('#parent-param-font-attributes').removeClass('pb_hidden_depend');
                }
            }
        })
    });

})(JoomlaShine.jQuery);
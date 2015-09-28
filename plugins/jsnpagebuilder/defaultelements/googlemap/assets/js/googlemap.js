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
    $.GoogleMapElement = $.GoogleMapElement || {};

    $.GoogleMapElement = function (iframe) {
        var html_options = '<option value=""> - Select Destination Marker - </option>';
        var select_destination = $(document.getElementById('select_param-gmi_destination'));
        var exclude_title = $(document.getElementById('param-gmi_title')).val();
        var currentValue = $(document.getElementById('param-gmi_destination')).val();

        $('#modalOptions .jsn-item textarea[data-sc-info="shortcode_content"]').each(function () {
            var html_str = $(this).html();
            var title = html_str.match(/gmi_title="[^*!"]+"/g);
            var value = title[0].replace('"', '');
            value = value.replace('"', '');
            value = value.replace('gmi_title=', '');

            if (exclude_title != '' && exclude_title == value) {
                html_options += '';
            } else if (currentValue) {
                var current_selected = '';
                if (currentValue == value) {
                    current_selected = 'selected="selected"';
                }
                html_options += '<option value="' + value + '"' + current_selected + '>' + value + '</option>';
            } else {
                html_options += '<option value="' + value + '">' + value + '</option>';
            }

        });

        if (html_options) {
            select_destination.html(html_options);
            $(select_destination).attr('class', 'form-control input-sm');

        }
        $(document.getElementById('select_param-gmi_destination')).on('change', function () {
            $(this).closest('.controls').find('param-gmi_destination').val($(this).val());
        });
    }
    $(document).ready(function () {
        $('body').bind('pb_submodal_load', function (e, iframe) {
            setTimeout(function () {
                $.GoogleMapElement(iframe);
            }, 200);
        });
    });
})(JoomlaShine.jQuery)
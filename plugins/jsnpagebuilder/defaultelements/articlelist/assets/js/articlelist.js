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

    $(document).ready(function () {
        $.ShortcodeSetting.initSelectImage($('#modalOptions'));
        $('.jsn-articlelist-multiple-select').chosen();
        Calendar.setup({
            inputField: "param-articlelist_range_date_start",
            onSelect: function (calendar, date) {
                $('#param-articlelist_range_date_start').val(date).trigger('change');
            }
        });
        Calendar.setup({
            inputField: "param-articlelist_range_date_end",
            onSelect: function (calendar, date) {
                $('#param-articlelist_range_date_end').val(date).trigger('change');
            }
        });

        var toggleFeatureThumbDimension = function () {
            var layoutValue = $('[name="param-articlelist_layout"]:checked').val();
            var thumbType = $('[name="param-articlelist_first_thumbnail_type"]').val();

            if (layoutValue != 'layout_list' && thumbType == 'custom') {
                $('#parent-param-articlelist_first_thumbnail_dimension').show();
            } else {
                $('#parent-param-articlelist_first_thumbnail_dimension').hide();
            }
        };

        toggleFeatureThumbDimension();
        $('[name="param-articlelist_layout"]').on('change', function () {
            toggleFeatureThumbDimension();
        });
        $('[name="param-articlelist_first_thumbnail_type"]').on('change', function () {
            toggleFeatureThumbDimension();
        });

        var toggleItemThumbDimension = function () {
            var listStyle = $('[name="param-articlelist_list_style"]').val();
            var thumbType = $('[name="param-articlelist_thumbnail_type"]').val();

            if (listStyle == 'thumbnail' && thumbType == 'custom') {
                $('#parent-param-articlelist_thumbnail_dimension').show();
            } else {
                $('#parent-param-articlelist_thumbnail_dimension').hide();
            }
        };
        toggleItemThumbDimension();
        $('[name="param-articlelist_list_style"]').on('change', function () {
            toggleItemThumbDimension();
        });
        $('[name="param-articlelist_thumbnail_type"]').on('change', function () {
            toggleItemThumbDimension();
        });
    });
})(JoomlaShine.jQuery);
/**
 * @version    $Id$
 * @package    IGPGBLDR
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support: Feedback - http://www.woorockets.com/contact-us/get-support.html
 */

/**
 * Custom script for pricing table item element
 */
(function ($) {
    'use strict';

    $.Pb_Pricing_Item = $.Pb_Pricing_Item || {};
    $.ShortcodeSetting = $.ShortcodeSetting || {};
    var sending_request = 0;

    $.Pb_Pricing_Item = function (options) {
        var self = this;
        // Object parameters
        this.options = $.extend({}, options);

        var options_ = this.options;

        this.wrapper = options_.wrapper;

        // Get action button
        this.action_btn = options_.action_btn;

        // Get edit button
        this.button = options_.button;

        $(this.button).attr('data-custom-action', 'popover');

        // Get selector for parameters to check for change event
        this.parameter = options_.parameter;


        // Update shortcode content when a parameter changes
        $('.submodal_frame_2').delegate('change', this.parameter, function (event) {
            event.preventDefault();
            event.stopPropagation();
            self.update_attibutes();
            // Update value in Attributes box
            if ($(this).is('#param-prtbl_item_attr_value')) {
                $('.submodal_frame_2').closest('.jsn-item').first().find('.jsn-item-content').html($(this).val());
            }

            self.regenerate_sc();
        });
        // Trigger change for #prtbl_item_attr_type
        $('.prtbl_item_attr_type input[type="radio"]').on('click', function (event) {
            //event.stopPropagation();

            if ($(this).is(':checked')) {
                var textarea = $(this).closest('.jsn-item').first().find("[name^='shortcode_content']");
                var shortcode_content = textarea.text();
                var value = $(this).val();

                //if (shortcode_content.indexOf('prtbl_item_attr_value="yes"') >= 0) {
                //    shortcode_content = shortcode_content.replace('prtbl_item_attr_value="yes"', 'prtbl_item_attr_value="' + value + '"');
                //
                //} else {
                shortcode_content = shortcode_content.replace(/(prtbl_item_attr_value=")([^\"]*)(")/, '$1' + value + '$3');

                //}
                //if (shortcode_content.indexOf('prtbl_item_attr_value') < 0) {
                //    shortcode_content = shortcode_content.replace(']', 'prtbl_item_attr_value="' + value + '"]');
                //
                //}
                textarea.text(shortcode_content);

                //self.regenerate_sc();
            }
        });
    }

    $.Pb_Pricing_Item.prototype = {

        /**
         * Regenerate shortcode for Pricing table and Preview
         *
         * @returns {undefined}
         */
        regenerate_sc: function () {

            // Get active Item
            var pricing_item = $('#modalOptions');

            // Get Attributes content of this Item
            var attributes_sc = '';
            pricing_item.find('.jsn-items-list').find("[name^='shortcode_content']").each(function () {
                attributes_sc += $(this).val();
            });

            // Merge existed shortcode content with Attributes content
            var pricing_item_sc_obj = pricing_item.find("[name^='shortcode_content']").first();

            var pricing_item_sc = pricing_item_sc_obj.val();

            if (pricing_item_sc.indexOf('pb_pricingtable_item_item') > 0) {
                pricing_item_sc = pricing_item_sc.replace(/(\[pb_pricingtable_item_item.*)(\[\/pb_pricingtable_item)/, attributes_sc + '$2');
            } else {
                pricing_item_sc = pricing_item_sc.replace(/(\[\/pb_pricingtable_item)/, attributes_sc + '$1');
            }

            // Update whole shortcode content of Pricing Item
            pricing_item_sc_obj.text(pricing_item_sc);

            // Re-generate shortcode of pricing table
            var pricing_sc_obj = $('#modalOptions').find('textarea');
            var pricing_sc = pricing_sc_obj.val();

            // Get shortcode of all pricing item
            var sc_items = '';
            $('#modalOptions #group_elements').children('.jsn-item').each(function () {
                sc_items += $(this).find("[name^='shortcode_content']").first().val();
            });

            pricing_sc = pricing_sc.replace(/(\[pb_pricingtable_item.*)(\[\/pb_pricingtable)/, sc_items + '$2');
            pricing_sc_obj.val(pricing_sc);

            // Recall preview
            $.ShortcodeSetting.shortcodePreview(pricing_sc, 'pb_pricingtable');
        },

        // Update shortcode content when change parameter's value in Popover
        update_attibutes: function () {
            $('.jsn-items-list .shortcode-content').each(function () {
                var content = $(this).text();
                $('.submodal_frame_2').closest('.jsn-item').first().find("[name^='shortcode_content']").text(content);

            })

        }
    }

    $(document).ready(function () {
        $.ShortcodeSetting.initSelectImage();
        new $.Pb_Pricing_Item({
            wrapper: $('#modalAction'),
            button: '.group-table .element-edit-ct',
            parameter: '[id^="param"]'
        });
        $('.no-hover-subitem').closest('.jsn-item').css({
            'border': 'none',
            'background-color': '#FFFFFF'
        });


    });

})(jQuery);
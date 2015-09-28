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
    'use strict';

    $(document).ready(function () {

        // Set manual event previous for testimonial left control
        $('.pb-element-testimonial .left').on('click', function (e) {
            e.preventDefault();
            var parent_id = $(this).closest('.pb-testimonial').attr('id');
            if (typeof ($('#' + parent_id).carousel) == "function") {
                $('#' + parent_id).carousel('prev');
            }
        });

        // Set manual event next for testimonial left control
        $('.pb-element-testimonial .right').on('click', function (e) {
            e.preventDefault();
            var parent_id = $(this).closest('.pb-testimonial').attr('id');
            if (typeof ($('#' + parent_id).carousel) == "function") {
                $('#' + parent_id).carousel('next');
            }
        });

        // Set manual event for testimonial indicators control
        $('.pb-element-testimonial .carousel-indicators li').each(function (index) {
            $(this).on('click', function (e) {
                e.preventDefault();
                var parent_id = $(this).closest('.pb-testimonial').attr('id');
                if (typeof ($('#' + parent_id).carousel) == "function") {
                    $('#' + parent_id).carousel(index);
                }
            })
        })
    });
})(jQuery)
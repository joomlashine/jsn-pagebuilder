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

	$(window).load(function () {

		$(".pb-prtbl-button-fancy").fancybox({
			"width"        : "75%",
			"height"       : "75%",
			"autoScale"    : false,
			"transitionIn" : "elastic",
			"transitionOut": "elastic",
			"type"         : "iframe"
		});

        if (typeof balanceHeight != 'function') {
            var balanceHeight = function () {
                $('.pb-element-pricingtable').each(function () {
                    var maxHeight = [];
                    var maxHeightMeta = 0;
                    $(this).find('.pb-prtbl-cols').each(function () {
                        var _i = 0;
                        $(this).find('.pb-prtbl-features li').each(function () {
                            if (maxHeight[_i] == undefined || maxHeight[_i] < $(this).height()) {
                                maxHeight[_i] = $(this).height();
                            }
                            _i++;
                        });
                    });
                    $(this).find('.pb-prtbl-cols').each(function () {
                        var _i = 0;
                        $(this).find('.pb-prtbl-features li').each(function () {
                            if (maxHeight[_i] != undefined) {
                                $(this).height(maxHeight[_i]);
                            }
                            _i++;
                        });
                    });

                    $(this).find('.pb-prtbl-header .pb-prtbl-meta').each(function () {
                        if (maxHeightMeta < $(this).height()) {
                            maxHeightMeta = $(this).height();
                        }
                    });
                    $(this).find('.pb-prtbl-header .pb-prtbl-meta').each(function () {
                        $(this).height(maxHeightMeta);
                    });
                });
            };

            balanceHeight();
        }
	});

})(jQuery);
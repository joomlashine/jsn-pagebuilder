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

    $(document).ready(function () {
        $('.pb-weather-location-code').autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "https://search.yahoo.com/sugg/gossip/gossip-gl-location/?appid=weather&output=sd1&p2=cn,t,pt,z&lc=en-US",
                    dataType: "jsonp",
                    data: {
                        command: request.term
                    },
                    success: function (data) {
                        var countryData = [];
                        for (var i = 0; i < data.r.length; i++) {
                            var temp = data.r[i].d;
                            // TODO bad code?
                            if (temp.indexOf("sc=") != -1) {
                                countryData[i] = data.r[i].k + ',' + temp.substr(temp.indexOf("sc=") + 3, 2);
                            } else {
                                countryData[i] = data.r[i].k + ',' + temp.substr(temp.indexOf("iso") + 4, 2);
                            }
                        }
                        response(countryData);
                    }
                });
            },
            change: function (event, ui) {
                $.ShortcodeSetting.shortcodePreview(null, null, null, null, 1);
            },
            select: function (event, ui) {
            }
        });

        var toggleNumberForecast = function () {
            var layoutValue = $('[name="param-weather_layout"]:checked').val();
            var showForecast = $('[name="param-weather_show_next"]:checked').val();

            if (layoutValue == 'advanced' && showForecast == 'yes') {
                $('#parent-param-weather_number_day').show();
            } else {
                $('#parent-param-weather_number_day').hide();
            }
        };

        toggleNumberForecast();
        $('[name="param-weather_layout"]').on("change", function () {
            toggleNumberForecast();
        });

        $('[name="param-weather_show_next"]').on("change", function () {
            toggleNumberForecast();
        });
    });
})(JoomlaShine.jQuery);
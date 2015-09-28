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

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip({
            html: true,
            track: true,
            placement: 'left'
        });

        var toggleAutoPlaySpeed = function () {
            var layoutValue = $('[name="param-market_layout"]:checked').val();
            var autoPlay = $('[name="param-market_auto_play_carousel"]:checked').val();

            if (layoutValue == 'carousel' && autoPlay == 'yes') {
                $('#parent-param-market_auto_play_speed').show();
            } else {
                $('#parent-param-market_auto_play_speed').hide();
            }
        };

        var toggleNumberToShow = function () {
            var layoutValue = $('[name="param-market_layout"]:checked').val();
            var slideDimension = $('[name="param-market_slide_dimension"]').val();

            if (layoutValue == 'carousel' && slideDimension == 'horizontal') {
                $('#parent-param-market_number_to_show').show();
            } else {
                $('#parent-param-market_number_to_show').hide();
            }
        };

        toggleAutoPlaySpeed();
        toggleNumberToShow();
        $('[name="param-market_layout"]').on("change", function () {
            toggleAutoPlaySpeed();
            toggleNumberToShow();
        });

        $('[name="param-market_auto_play_carousel"]').on("change", function () {
            toggleAutoPlaySpeed();
        });

        $('[name="param-market_slide_dimension"]').on("change", function () {
            toggleNumberToShow();
        });
    });
})(JoomlaShine.jQuery);
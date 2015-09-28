/**
 * @version    $Id$
 * @package    JSN_Framework
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

(function ($) {
    "use strict";

    $.JSNColorPicker = function (selector, modal) {
        this.init(selector, modal);
    };

    $.JSNColorPicker.prototype = {
        init: function (selector, modal) {
            if ( ! selector )
                return false;

            $( selector ).each(function () {
                var self	= $(this);
                var colorInput = self.siblings('input').last();
                var inputId 	= colorInput.attr('id');
                var inputValue 	= inputId.replace(/_color/i, '') + '_value';
                if ($('#' + inputValue).length){
                    $('#' + inputValue).val($(colorInput).val());
                }

                self.ColorPicker({
                    color: $(colorInput).val(),
                    onShow: function (colpkr) {
                        $(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        $(colpkr).fadeOut(500);
                        if (typeof modal != 'undefined')
                        {
                        	$.ShortcodeSetting.shortcodePreview(modal.container);
                        }
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        $(colorInput).val('#' + hex);

                        if ($('#' + inputValue).length){
                            $('#' + inputValue).val('#' + hex);
                        }
                        self.children().css('background-color', '#' + hex);
                    }
                });
            });
        }
    }

})(JoomlaShine.jQuery);
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
    $(document).ready(function(){
        $('.pb-progress-circle').each(function () {
            if(typeof ($.fn.lazyLoad) == 'function'){
                $(this).lazyLoad({
                    effect: 'fadeIn'
                });
                $(this).on('appear', function(){
                    if(typeof ($.fn.circliful) == 'function'){
                        var html_content = $(this).html();
                        if(!html_content){
                            $(this).circliful();
                        }
                    }
                });
            }else{
                if(typeof ($.fn.circliful) == 'function'){
                    $(this).circliful();
                }
            }

            var pbCirCleDimention = $(this).data('dimension');
            $(this).find('.circle-info').each(
                function () {
                    $(this).css({
                        "line-height": (pbCirCleDimention * 0.75) + "px",
                        "padding-top": (pbCirCleDimention * 0.25) + "px"
                    });
                }
            );
        });
    });
})(jQuery);
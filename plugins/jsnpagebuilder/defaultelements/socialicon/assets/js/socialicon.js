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
        $('.pb-social-icons').each(function(){
            if($(this).hasClass('brand')){
                if($(this).find('i.fa-facebook'))
                {
                    $(this).find('i.fa-facebook').parent().css({'background': '#3b5998'});
                }
                if($(this).find('i.fa-android'))
                {
                    $(this).find('i.fa-android').parent().css({'background': '#a4c639'});
                }
                if($(this).find('i.fa-apple'))
                {
                    $(this).find('i.fa-apple').parent().css({'background': '#a3a7a6'});
                }
                if($(this).find('i.fa-behance'))
                {
                    $(this).find('i.fa-behance').parent().css({'background': '#1769ff'});
                }
                if($(this).find('i.fa-delicious'))
                {
                    $(this).find('i.fa-delicious').parent().css({'background': '#3399ff'});
                }
                if($(this).find('i.fa-deviantart'))
                {
                    $(this).find('i.fa-deviantart').parent().css({'background': '#4dc47d'});
                }
                if($(this).find('i.fa-digg'))
                {
                    $(this).find('i.fa-digg').parent().css({'background': '#000000'});
                }
                if($(this).find('i.fa-dribbble'))
                {
                    $(this).find('i.fa-dribbble').parent().css({'background': '#ff8833'});
                }
                if($(this).find('i.fa-flickr'))
                {
                    $(this).find('i.fa-flickr').parent().css({'background': '#0063dc'});
                }
                if($(this).find('i.fa-google-plus'))
                {
                    $(this).find('i.fa-google-plus').parent().css({'background': '#dd4b39'});
                }
                if($(this).find('i.fa-instagram'))
                {
                    $(this).find('i.fa-instagram').parent().css({'background': '#3f729b'});
                }
                if($(this).find('i.fa-linkedin'))
                {
                    $(this).find('i.fa-linkedin').parent().css({'background': '#0976b4'});
                }
                if($(this).find('i.fa-pinterest'))
                {
                    $(this).find('i.fa-pinterest').parent().css({'background': '#cc2127'});
                }
                if($(this).find('i.fa-skype'))
                {
                    $(this).find('i.fa-skype').parent().css({'background': '#00aff0'});
                }
                 if($(this).find('i.fa-slideshare'))
                {
                    $(this).find('i.fa-slideshare').parent().css({'background': '#0087be'});
                }
                 if($(this).find('i.fa-soundcloud'))
                {
                    $(this).find('i.fa-soundcloud').parent().css({'background': '#ff8800'});
                }
                 if($(this).find('i.fa-stumbleupon'))
                {
                    $(this).find('i.fa-stumbleupon').parent().css({'background': '#eb4924'});
                }
                 if($(this).find('i.fa-tumblr'))
                {
                    $(this).find('i.fa-tumblr').parent().css({'background': '#35465c'});
                }
                if($(this).find('i.fa-twitter'))
                {
                    $(this).find('i.fa-twitter').parent().css({'background': '#55acee'});
                }
                if($(this).find('i.fa-youtube'))
                {
                    $(this).find('i.fa-youtube').parent().css({'background': '#cd201f'});
                }
            }
        });
    });
})(jQuery)
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
(
    function ($) {
        var JSNIconSelector = function (params) {

        }
        JSNIconSelector.prototype = {
            GenerateSelector:function (container, actionSelector, value) {
                var self = this;
                var resultsFilter = $("<ul/>", {"class":"jsn-items-list"});
                $("#jsn-quicksearch-icons").val("");
                $(container).find(".jsn-reset-search").hide();
                self.renderListIconSelector(resultsFilter, self.SocialIcon(), actionSelector, value);
                $.fn.delayKeyup = function (callback, ms) {
                    var timer = 0;
                    var el = $(this);
                    $(this).keyup(function () {
                        clearTimeout(timer);
                        timer = setTimeout(function () {
                            callback(el)
                        }, ms);
                    });
                    return $(this);
                };
                var oldIconFilter = "";
                return $("<div/>", {"class":"jsn-iconsocialselector"}).append(
                    $("<div/>", {"class":"jsn-fieldset-filter"}).append(
                        $("<fieldset/>").append(
                            $("<div/>", {"class":"pull-right jsn-quick-search"}).append(
                                $("<input/>", {"class":"input search-query", "type":"text","id":"jsn-quicksearch-icons", "placeholder":"Search..."}).delayKeyup(function (el) {
                                    if ($(el).val() != oldIconFilter) {
                                        oldIconFilter = $(el).val();
                                        self.filterResults($(el).val(), resultsFilter);
                                    }
                                    if($(el).val() == ""){
                                        $(el).parents(".jsn-iconsocialselector").find(".jsn-reset-search").hide();
                                    }else{
                                        $(el).parents(".jsn-iconsocialselector").find(".jsn-reset-search").show();
                                    }
                                }, 500)
                            ).append(
                                $("<a/>",{"href":"javascript:void(0);","title":"Clear Search","class":"jsn-reset-search"}).append($("<i/>",{"class":"icon-remove"})).click(function(){
                                    $(this).parents(".jsn-iconsocialselector").find("#jsn-quicksearch-icons").val("");
                                    oldIconFilter = "";
                                    self.filterResults("", resultsFilter);
                                    $(this).hide();
                                })
                            )
                        )
                    )
                ).append(resultsFilter);

            },
            filterResults:function (value, resultsFilter) {
                $(resultsFilter).find("li").hide();
                if (value != "") {
                    $(resultsFilter).find("li").each(function () {
                        var textField = $(this).find("a").attr("data-value").toLowerCase();
                        if (textField.search(value.toLowerCase()) == -1) {
                            $(this).hide();
                        } else {
                            $(this).fadeIn(1200);
                        }
                    });
                } else {
                    $(resultsFilter).find("li").each(function () {
                        $(this).fadeIn(1200);
                    });
                }
            },
            renderListIconSelector:function ( container, list, actionSelector, valueDefault) {
                $(container).find("li").removeClass("active");
                $(container).html("");

                var _nonIconClass	= 'jsn-item';
                if (!valueDefault) {
                    _nonIconClass	= 'jsn-item active';
                }
                $(container).append(
                    $("<li/>", {'class': _nonIconClass}).append(
                        $("<a/>", {"href":"javascript:void(0)", "class":"icons-item", "data-value":''}).append($("<i/>", {"class":'fa-'})).append('None').click(function () {
                            actionSelector(this);
                        })
                    )
                );

                $.each(list, function (value, title) {
                    var classActive = {"class":"jsn-item"};
                    if (value == valueDefault) {
                        classActive = {"class":"jsn-item active"};
                    }
                    $(container).append(
                        $("<li/>", classActive).append(
                            $("<a/>", {"href":"javascript:void(0)", "class":"icons-item pb-tooltip", "data-value":value, "data-original-title":value, "title": title}).append($("<i/>", {"class":value})).click(function () {
                                actionSelector(this);
                            })
                        )
                    );
                });
            },
            SocialIcon:function () {
                return {
                    "fa fa-android":"Android",
                    "fa fa-apple":"Apple",
                    "fa fa-behance":"Behance",
                    "fa fa-delicious":"Delicious",
                    "fa fa-deviantart":"Deviantart",
                    "fa fa-digg":"Digg",
                    "fa fa-dribbble":"Dribbble",
                    "fa fa-facebook":"Facebook",
                    "fa fa-flickr":"Flickr",
                    "fa fa-google-plus":"Google-plus",
                    "fa fa-instagram":"Instagram",
                    "fa fa-linkedin":"Linkedin",
                    "fa fa-pinterest":"Pinterest",
                    "fa fa-skype":"Skype",
                    "fa fa-slideshare":"Slideshare",
                    "fa fa-soundcloud":"Soundcloud",
                    "fa fa-stumbleupon":"Stumbleupon",
                    "fa fa-tumblr":"Tumblr",
                    "fa fa-twitter":"Twitter",
                    "fa fa-youtube":"Youtube"
                }
            }
        }

        $(document).ready(function() {

            if($("#icon_selector").length){
                var inputIcon  = $("#icon_selector").find(":hidden").first()
                var iconSelector = new JSNIconSelector()
                var actionSelector = $.proxy(function (_this) {
                    $(_this).parents(".jsn-items-list").find(".active").removeClass("active");
                    $(_this).parent().addClass("active");
                    inputIcon.val($(_this).attr("data-value"));
                    inputIcon.trigger('change');
                }, this);
                $('[data-original-title]').tooltip({
                    placement: 'bottom'
                });
                $("#icon_selector").append(iconSelector.GenerateSelector($("#icon_selector"), actionSelector, inputIcon.val()));
                // focus selected icon
                $("[data-value='" + inputIcon.val() + "']").focus()
            }
        })
    })(jQuery)
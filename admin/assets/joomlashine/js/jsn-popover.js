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
    $.JSNPopoverOptions = $.JSNPopoverOptions || {};

    $.JSNPopoverOptions = function () {
    };

    $.JSNPopoverOptions.prototype = {
        init: function (modal) {
            this.container = $(".jsn-items-list");
            this.addIconbar();
            this.actionIconbar(this, modal);
            if (this.container.parents('.unsortable').length == 0) {
                this.container.sortable({
                    placeholder: "ui-state-highlight",
                    stop: function (event, ui) {
                        $.ShortcodeSetting.shortcodePreview();
                    }
                });
                this.container.disableSelection();
            }
        },
        addIconbar: function () {
            this.container.find(".jsn-item").find(":input[data-popover-item='yes']").each(function () {
                $(this).after('<div class="jsn-iconbar"><a class="element-action-edit" href="javascript:void(0)"><i class="icon-cog"></i></a></div>');
            })
        },
        actionIconbar: function (this_, modal) {
            this_.container.find(".element-action-edit").unbind('click').click(function (e) {
                this_.openActionSettings(this_, $(this), null, false, modal);
                $.ShortcodeSetting.select2_color();
                // fix select2 error
                //$('select.jsn-fontFace, select.jsn-fontFaceType').select2("destroy");
                // end fix
                e.stopPropagation();
            });
        },
        openActionSettings: function (this_, btnInput, specific, callback, modal) {
            this_.container.find(".jsn-item.ui-state-edit").removeClass("ui-state-edit");
            $(btnInput).parents(".jsn-item").addClass("ui-state-edit");
            $(".control-list-action").hide();
            var dialog, value, el_title;
            if (specific == null) {
                value = $(btnInput).parents(".jsn-item").find(":input").val();
            }
            else {
                value = $(btnInput).parents(".jsn-item").find(":input#param-elements").val();
            }
            el_title = $(btnInput).parents(".jsn-item").find("label").text();

            if ($("#control-action-" + value).length == 0) {
                var dialog_html = '';
                $('body').find('[data-related-to="' + value + '"]').each(function () {
                    dialog_html += $("<div />").append($(this).clone()).html();
                    $(this).remove();
                })
                dialog = $("<div/>", {
                    'class': 'control-list-action jsn-bootstrap',
                    'id': "control-action-" + value,
                    'style': 'position: absolute;width:300px;'
                }).append(
                    $("<div/>", {
                        "class": "popover left"
                    }).css("display", "block").append($("<div/>", {
                        "class": "arrow"
                    })).append(
                        $("<h3/>", {
                            "class": "popover-title",
                            text: el_title + ' ' + JSNPbParams.pbstrings.SETTINGS
                        })
                    ).append(
                        $("<div/>", {
                            "class": "popover-content"
                        }).append(
                            dialog_html
                        )
                    )
                )
                $(dialog).find('.hidden').removeClass('hidden');
                $(dialog).hide();
                $(dialog).appendTo('#modalAction');
            }
            else {
                dialog = $("#control-action-" + value);
            }
            dialog.fadeIn(500);
            // update HTML DOM
            $('.control-list-action').delegate('[id^="param"]', 'change', function () {
                $(this).attr('value', $(this).val());
                if ($(this).is('select')) {
                    var html = $(this).html();
                    html = html.replace('selected=""', '').replace('value="' + $(this).val() + '"', 'value="' + $(this).val() + '" selected=""');
                    $(this).html(html);
                }
            });

            if (callback)
                callback(dialog);

            var elmStyle = this_.getBoxStyle($(dialog).find(".popover")),
                parentStyle = this_.getBoxStyle($(btnInput)),
                position = {};
            
            position.left = parentStyle.offset.left - elmStyle.outerWidth - 100; // 11 is width of arrow of popover left
            position.top = parentStyle.offset.top - elmStyle.outerHeight - 80;

            dialog.css(position).click(function (e) {
                e.stopPropagation();
            });
            $(document).click(function (e) {
                var eventTarget = e.target;
                if ($(eventTarget).parent().attr('class').indexOf('colorpicker') == -1 && $(eventTarget).parent().attr('class') !== 'popover') {
                    if ($(eventTarget).parent().attr('class').indexOf('colorpicker') == -1 && $(eventTarget).parent().attr('class').indexOf('jsn-master') != 0) {
                        dialog.hide();
                    }
                }
                this_.container.find(".jsn-item.ui-state-edit").removeClass("ui-state-edit");
            });

            // fire hook event after insert popover html
            $('body').trigger('pb_after_popover', [modal]);
        },
        // Get element's dimension
        getBoxStyle: function (element) {
            var style = {
                width: element.width(),
                height: element.height(),
                outerHeight: element.outerHeight(),
                outerWidth: element.outerWidth(),
                offset: element.offset(),
                margin: {
                    left: parseInt(element.css('margin-left')),
                    right: parseInt(element.css('margin-right')),
                    top: parseInt(element.css('margin-top')),
                    bottom: parseInt(element.css('margin-bottom'))
                },
                padding: {
                    left: parseInt(element.css('padding-left')),
                    right: parseInt(element.css('padding-right')),
                    top: parseInt(element.css('padding-top')),
                    bottom: parseInt(element.css('padding-bottom'))
                }
            };

            return style;
        }
    }

    $(document).ready(function () {
        $('body').bind('pb_modal_loaded', function(e, modal) {
        	
        	var PB_Content = new $.JSNPopoverOptions();
            PB_Content.init(modal);
        });
        
    })

})(JoomlaShine.jQuery);
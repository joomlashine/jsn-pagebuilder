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

/**
 * Root wrapper div #form-container, contains all content
 * Columns are seperated by a 12px seperators
 * HTML structure: #form-container [.jsn-row-container [.jsn-column-container]+]+
 *
 */
var JSNallowChange = false;
function JSNPbLayoutCustomizer() {
};
(function ($) {
    JSNPbLayoutCustomizer.prototype = {
        init: function (_this) {
            // Get necessary elements
            this.wrapper = $("#form-container");
            this.wrapper_width = 0;
            this.columns = $(_this).find('.jsn-column-container');
            this.addcolumns = '.add-container';
            this.addelements = '.pb-more-element';
            this.resizecolumns = '.ui-resizable-e';
            this.deletebtn = '.item-delete';
            this.moveItemEl = "[class^='jsn-move-']";
            this.resize = 1;
            this.effect = 'easeOutCubic';

            // Initialize variables
            this.maxWidth = this.wrapper.width();
            this.spacing = 12;

            this.column_tmpl = $("#tmpl-jsn_pagebuilder_layout_column").html();
            this.row_tmpl = $("#tmpl-jsn_pagebuilder_layout_row").html();

            this.addRow(this, this.wrapper);
            this.updateSpanWidthPBDL(this, this.wrapper, this.maxWidth);
            this.initResizable(-1);
            this.addColumn(this.column_tmpl);
            this.removeItem();
            this.moveItem(this);
            this.moveItemDisable(this.wrapper);
            this.resizeHandle(this);
            this.sortableColumn(_this);
            this.sortableElement();
            this.addElement();
            this.searchElement();

        },

        // Update column width when window resize
        resizeHandle: function (self) {
            $(window).resize(function () {
                if (self.resize && self.wrapper.width()) {
                    $("#form-container").width(( $('#myTabContent .span9').width() - 60 ) + 'px');
                    if ($('#myTabContent .span9').width() === null) {
                        $("#form-container").width(( $('#jsnpagebuilder-text').width() - 60 ) + 'px');
                    }
                    self.maxWidth = self.wrapper.width();
                    // re-calculate step width
                    self.calStepWidth(0, 'reset');
                    self.initResizable(-1, false);
                    self.updateSpanWidthPBDL(self, self.wrapper, self.maxWidth);
                    $('body').trigger('jsnpb_changed');
                }
            });

        },

        // Calculate step width when resize column
        calStepWidth: function (countColumn, reset) {
            var this_column = this.columns;
            if (reset != null) {
                this_column = $("#form-container").find(".jsn-row-container").first().find('.jsn-column-container');
            }

            var formRowLength = (countColumn > 0) ? countColumn : this_column.length;
            this.step = parseInt((this.maxWidth - (this.spacing * (formRowLength - 1))) / 12);
        },

        // Resize columns
        initResizable: function (countColumn, getStep) {
            var self = this;
            if (getStep == null || getStep)
                self.calStepWidth(countColumn);

            var step = self.step;
            var handleResize = $.proxy(function (event, ui) {
                var span = parseInt((ui.element.width() ) / this.step),
                    thisWidth = (this.step * span),
                    nextWidth = ui.element[0].__next[0].originalWidth - (thisWidth - ui.originalSize.width);

                if (thisWidth < this.step) {
                    thisWidth = this.step;
                    nextWidth = ui.element[0].__next[0].originalWidth - (thisWidth - ui.originalSize.width);

                } else if (nextWidth < this.step) {

                    nextWidth = this.step;
                    thisWidth = ui.originalSize.width - (nextWidth - ui.element[0].__next[0].originalWidth);

                }
                // Snap column to grid
                ui.element.css('width', thisWidth + 'px');

                // Resize next sibling element as well
                ui.element[0].__next.css('width', nextWidth - 20 + 'px');


                self.percentColumn($(ui.element), "add", step);
            }, this);
            // Reset resizable column

            $(".jsn-column", self.wrapper).each($.proxy(function (i, e) {
                $(e).resizable({
                    handles: 'e',
                    minWidth: step,
                    grid: [step, 0],
                    start: $.proxy(function (event, ui) {
                        // Get next sibling element
                        ui.element[0].__next = ui.element[0].__next || ui.element.parent().next().children();
                        ui.element[0].__next[0].originalWidth = ui.element[0].__next.width() + 20;

                        // Store original data
                        ui.element[0].__class = ui.element.attr('class');
                        ui.element[0].__before = [ui.element.parent().html(), ui.element[0].__next.parent().html()];

                        // Reset max width option
                        ui.element.resizable('option', 'maxWidth', '');
                        // disable resize handle
                        self.resize = 0
                    }, this),
                    resize: handleResize,
                    stop: $.proxy(function (event, ui) {
                        var oldValue = parseInt(ui.element.find(".jsn-column-content").attr("data-column-class").replace('span', '')),
                        // Round up, not parsetInt
                            newValue = Math.round(ui.element.width() / this.step),
                            nextOldValue = parseInt(ui.element[0].__next.find(".jsn-column-content").attr("data-column-class").replace('span', ''));

                        if ((nextOldValue - (newValue - oldValue)) == 0) {
                            newValue = newValue - 1;
                        }
                        // Update field values
                        if (nextOldValue > 0 && newValue > 0 && nextOldValue - (newValue - oldValue) > 0) {
                            ui.element.find(".jsn-column-content").attr("data-column-class", 'span' + newValue);
                            ui.element[0].__next.find(".jsn-column-content").attr('data-column-class', 'span' + (nextOldValue - (newValue - oldValue)));
                            // Update visual classes
                            ui.element.attr('class', ui.element.attr('class').replace(/\bspan\d+\b/, 'span' + newValue));
                            ui.element[0].__next.attr('class', ui.element[0].__next.attr('class').replace(/\bspan\d+\b/, 'span' + (nextOldValue - (newValue - oldValue))));
                            ui.element.find("[name^='shortcode_content']").first().text(ui.element.find("[name^='shortcode_content']").first().text().replace(/span\d+/, 'span' + newValue));
                            ui.element[0].__next.find("[name^='shortcode_content']").first().text(ui.element[0].__next.find("[name^='shortcode_content']").first().text().replace(/span\d+/, 'span' + (nextOldValue - (newValue - oldValue))));
                            ui.element[0].__next.find("[name^='shortcode_content']").first().text(ui.element[0].__next.find("[name^='shortcode_content']").first().text().replace(/span\d+/, 'span' + (nextOldValue - (newValue - oldValue))));
                            $(e).css({
                                "height": "auto"
                            });
                        }

                        // enable resize handle
                        self.resize = 1;
                        self.updateSpanWidthPBDL(self, self.wrapper, self.wrapper.width());
                        $('body').trigger('jsnpb_changed');
                        self.percentColumn($(ui.element), "remove", step);

                        // Trigger an event after resizing elements
                        if (ui.element[0].__class.replace(' ui-resizable-resizing', '') != ui.element.attr('class')) {
                            $(document).trigger('pb_after_edit_element', [$([ui.element.parent()[0], ui.element[0].__next.parent()[0]]), ui.element[0].__before]);
                        }
                        delete ui.element[0].__class;
                        delete ui.element[0].__before;
                    }, this)
                });
            }, this));

            // remove duplicated resizable-handle div
            if (countColumn > 0) {
                $(".jsn-column", self.wrapper).each(function () {
                    if ($(this).find('.ui-resizable-handle').length > 1)
                        $(this).find('.ui-resizable-handle').last().remove();
                });
            }
        },
        percentColumn: function (element, action, step) {

            if (action == "add") {
                var container = $(element).parents(".jsn-row-container");
                var maxWidth = parseInt(step * 12);

                var toFixed = function toFixed(value, precision) {
                    var power = Math.pow(10, precision || 0);
                    return String(Math.round(value * power) / power);
                }
                $(container).find(".jsn-column").each(function () {
                    var thisWidth = $(this).width();

                    var percent = toFixed(thisWidth / maxWidth * 100, 2).replace(".00", "") + "%";
                    var thumbnail = $(this).find(".thumbnail");
                    $(thumbnail).css('position', 'relative');
                    $(thumbnail).find("percent-column").remove();
                    if ($(thumbnail).find(".jsn-percent-column").length) {
                        $(thumbnail).find(".jsn-percent-column .jsn-percent-inner").html(percent);
                    } else {
                        $(thumbnail).append(
                            $("<div/>", {"class": "jsn-percent-column"}).append(
                                $("<div/>", {"class": "jsn-percent-arrow"})
                            ).append(
                                $("<div/>", {"class": "jsn-percent-inner"}).append(percent)
                            )
                        );
                    }
                    var widthThumbnail = $(thumbnail).width();
                    var widthPercent = $(thumbnail).find(".jsn-percent-column").width();
                    $(thumbnail).find(".jsn-percent-column").css({"left": parseInt((widthThumbnail + 10) / 2) - parseInt(widthPercent / 2) + "px"});
                    $(thumbnail).find(".jsn-percent-column .jsn-percent-arrow").css({"left": parseInt(widthPercent / 2) - 4 + "px"});
                });
            }
            if (action == "remove") {
                container = $(element).parents(".jsn-row-container");
                $(container).find(".jsn-percent-column").remove();
            }
        },

        // Add Row
        addRow: function (self, this_wrapper) {
            this.wrapper.delegate('.pb-layout-thumbs .thumb-wrapper', 'click', function (e) {
                $(this).parent().find('.active').removeClass('active');
                $(this).addClass('active');
                $('#jsn-add-container').trigger('click', [true]);
            })
            // Set animation on hover "add row" button
            this.wrapper.delegate('#jsn-add-container', 'mouseover', function (e) {
                $('.pb-layout-thumbs').addClass('pb-mouse-over');
                var timeout = setTimeout(function () {
                    if ($('.pb-layout-thumbs').hasClass('pb-mouse-over') && !$('.pb-layout-thumbs').hasClass('open')) {
                        $('.pb-layout-thumbs').addClass('open');
                        $('.pb-layout-thumbs').css({'height': 'auto', 'max-height': '150px'});
                    }
                    clearTimeout(timeout);
                }, 200);
            });
            self.wrapper.delegate('#jsn-add-container', 'mouseleave', function (e) {
                e.preventDefault();
                $('.pb-layout-thumbs').removeClass('pb-mouse-over');
                $('#form-container').on('mouseleave', function (e) {
                    e.preventDefault();
                    $('.pb-layout-thumbs').removeClass('open');
                    $('.pb-layout-thumbs').css({'max-height': '1px'});
                });
            })
            $("#jsn-add-container").click(function (e, get_chosen_layout) {
                e.preventDefault();
                self._addRow(this_wrapper, this, null, null, get_chosen_layout);
                if ($("#form-container").find('.jsn-row-container').last().is(':animated')) return;
                $(this).before(self.row_tmpl);
                var new_el = $("#form-container").find('.jsn-row-container').last();
                var height_ = new_el.height();
                new_el.css({'opacity': 0, 'height': 0});
                new_el.addClass('overflow_hidden');
                new_el.show();
                new_el.animate({height: height_}, 300, self.effect, function () {
                    new_el.removeClass('overflow_hidden');
                    $(this).animate({opacity: 1}, 300, self.effect, function () {
                        new_el.css('height', 'auto');
                    });
                });
                // update width for colum of this new row
                var parentForm = self.wrapper.find(".jsn-row-container").last();
                self.updateSpanWidth(1, self.maxWidth, parentForm);
                // enable/disable move icons
                self.moveItemDisable(this_wrapper);

                self.updateSpanWidthPBDL(self, self.wrapper, self.wrapper.width());
                $('body').trigger('jsnpb_changed');
            });
        },

        _addRow: function (this_wrapper, target, row_html, position, get_chosen_layout) {
            var self = this;
            if ($("#form-container").find('.jsn-row-container').last().is(':animated')) return;
            if (row_html && position) {
                row_html = $(row_html).css('display', '');
                var rows = $('.jsn-row-container');
                if (rows.length) {
                    if (position.row >= rows.length) {
                        rows.last().after(row_html);
                    } else {
                        for (var i = 0; i < rows.length; i++) {
                            if (i == position.row) {
                                rows.eq(i).before(row_html);

                                break;
                            }
                        }
                    }
                } else {
                    this_wrapper.prepend(row_html);
                }
            } else {
                // if not choose any layout
                row_html = $($('#tmpl-jsn_pagebuilder_layout_row').html().replace('custom_style', 'style="display:none"'));
                var full_row_html = row_html.find('.jsn-pb-row-content').html();
                var html = '';
                if (get_chosen_layout && $('.pb-layout-thumbs .active').length > 0) {
                    var columns = $('.pb-layout-thumbs .active').attr('data-columns');
                    columns = columns.split(',');
                    $.each(columns, function (i, v) {
                        html += full_row_html.replace(/\bspan\d+\b/g, 'span' + v);
                    });
                }
                if (html !== '') {
                    row_html.find('.jsn-pb-row-content').html(html);
                }

                $(target).before(row_html);

                // Trigger an event after adding an element
                $(document).trigger('jsn_pb_after_add_element', row_html);

                // Animation
                var height_ = $(row_html).height();

                $(row_html)
                    .css({opacity: 0, height: 0, display: 'block'})
                    .addClass('overflow_hidden')
                    .show()
                    .animate({height: height_}, 300, self.effect, function () {
                        $(this).animate({opacity: 1}, 300, self.effect, function () {
                            $(row_html)
                                .css({opacity: '', height: '', display: ''})
                                .removeClass('overflow_hidden');
                        });
                    });
            }

            // Update width for column of this new row
            var parentForm = self.wrapper.find(".jsn-row-container").last();
            self.updateSpanWidth(1, self.maxWidth, parentForm);
            // Enable/disable move icons
            self.moveItemDisable(this_wrapper);
            self.rebuildSortable();
            //self.updateSpanWidthPBDL(self, self.wrapper, $(".pb-form-container.jsn-layout").width());
            self.updateSpanWidthPBDL(self, self.wrapper, self.wrapper.width());
            $('body').trigger('jsnpb_changed');
        },

        //Update sortable event for row and clumn layout
        rebuildSortable: function () {
            //Sortable for column in row
            var self = this;
            $('.jsn-pb-row-content').sortable({
                exis: 'x',
                handle: '.jsn-handle-drag',
                start: $.proxy(function (event, ui) {
                    // store original data
                    ui.item.css('position', '');
                    ui.item[0].__changed = false;
                    ui.item.css('position', 'absolute');

                    // Update placeholder
                    ui.placeholder.append(ui.item.children().clone());
                    ui.item.parent().children('.ui-sortable-placeholder').show();

                }, this),
                update: function (event, ui) {
                    ui.item[0].__changed = true;
                },
                stop: $.proxy(function (event, ui) {
                    // Show resizable handle
                    ui.item.parents(".jsn-pb-row-content").find(".ui-resizable-handle").show();

                    // Trigger an event after sorting elements
                    if (ui.item[0].__changed) {
                        $(document).trigger('jsn_pb_after_edit_element', [ui.item.closest('.jsn-row-container'), ui.item[0].__before]);
                    }

                    // Clean-up
                    delete ui.item[0].__changed;
                    delete ui.item[0].__before;

                    self.wrapper.trigger('jsn-pagebuilder-layout-changed', [ui.item]);
                }, this)
            });

            $(".jsn-pb-row-content").disableSelection();

            // Sortable elements
            self.initResizable();
            this.sortableElement();
        },

        // Wrap content of row
        wrapContentRow: function (a, b, direction) {
            var self = this;
            if (a.is(':animated') || b.is(':animated')) return;
            var this_wrapper = self.wrapper;
            var stylea = self.getBoxStyle(a);
            var styleb = self.getBoxStyle(b);
            var time = 500, extra1 = 16, extra2 = 16, effect = self.effect;
            if (direction > 0) {
                a.animate({top: '-' + (styleb.height + extra1) + 'px'}, time, effect, function () {
                });
                b.animate({top: '' + (stylea.height + extra2) + 'px'}, time, effect, function () {
                    a.css('top', '0px');
                    b.css('top', '0px');
                    a.insertBefore(b);
                    self.moveItemDisable(this_wrapper);
                });
            }
            else {
                a.animate({top: '' + (styleb.height + extra2) + 'px'}, time, effect, function () {
                });
                b.animate({top: '-' + (stylea.height + extra1) + 'px'}, time, effect, function () {
                    a.css('top', '0px');
                    b.css('top', '0px');
                    a.insertAfter(b);
                    self.moveItemDisable(this_wrapper);
                });
            }
        },

        // Handle when click Up/Down Row Icons
        moveItem: function (self) {
            var this_wrapper = this.wrapper;
            this.wrapper.delegate(this.moveItemEl, "click", function () {
                if (!$(this).hasClass("disabled")) {
                    var otherRow, direction;
                    var class_ = $(this).attr("class");
                    var parent = $(this).parents(".jsn-row-container");

                    if (class_.indexOf("jsn-move-up") >= 0) {
                        otherRow = parent.prev(".jsn-row-container");
                        direction = 1;
                    } else if (class_.indexOf("jsn-move-down") >= 0) {
                        otherRow = parent.next(".jsn-row-container");
                        direction = -1;
                    }
                    self.wrapContentRow(parent, otherRow, direction);
                    self.moveItemDisable(this_wrapper);
                    parent.css('opacity', 0.1);
                    parent.animate({'opacity': 1}, 600);
                    // Set trigger timeout to be sure it happens after animation
                    setTimeout(function () {
                        $('body').trigger('jsnpb_changed');
                    }, 1001);
                }
            });
        },

        // Disable Move Row Up, Down Icons
        moveItemDisable: function (this_wrapper) {
            this_wrapper.find(this.moveItemEl).each(function () {
                var class_ = $(this).attr("class");
                var parent = $(this).parents(".jsn-row-container");

                // add "disabled" class
                if (class_.indexOf("jsn-move-up") >= 0) {
                    if (parent.index() == 0)
                        $(this).addClass("disabled");
                    else
                        $(this).removeClass("disabled");
                }
                else if (class_.indexOf("jsn-move-down") >= 0) {
                    if (parent.index() == this_wrapper.find(".jsn-row-container").length - 1)
                        $(this).addClass("disabled");
                    else
                        $(this).removeClass("disabled");
                }
            })
        },

        // Update span width of columns in each row of PageBuilder at Page Load
        updateSpanWidthPBDL: function (self, this_wrapper, totalWidth) {
            this_wrapper.find(".jsn-row-container").each(function () {
                var countColumn = $(this).find(".jsn-column-container").length;
                self.updateSpanWidth(countColumn, totalWidth, $(this));
            });
        },

        // Update span width of columns in each row
        updateSpanWidth: function (countColumn, totalWidth, parentForm) {
            //totalWidth	=	totalWidth - 16;
            //12px is width of the resizeable div
            // remainWidth = totalwidth - 12
            var seperateWidth = countColumn * 12;
            var remainWidth = totalWidth - seperateWidth;
            parentForm.find(".jsn-column-container").each(function () {
                var selfSpan = $(this).find(".jsn-column-content").attr("data-column-class").replace('span', '');
                var columnWidth = parseInt(selfSpan) * remainWidth / 12;
                if (columnWidth >= totalWidth) columnWidth = totalWidth - 12;
                $(this).find('.jsn-column').css('width', columnWidth + 'px');
            });
        },

        // Add Column
        addColumn: function (column_html) {
            var self = this;
            this.wrapper.delegate(this.addcolumns, "click", function () {
                var parentForm = $(this).parents(".jsn-row-container");
                var countColumn = parentForm.find(".jsn-column-container").length;
                if (countColumn < 12) {
                    countColumn += 1;
                    var span = parseInt(12 / countColumn);
                    var exclude_span = (12 % countColumn != 0) ? span + (12 % countColumn) : span;
                    // update span old columns
                    parentForm.find(".jsn-column-container").each(function () {
                        $(this).attr('class', $(this).attr('class').replace(/span[0-9]{1,2}/g, 'span' + span));
                        $(this).html($(this).html().replace(/span[0-9]{1,2}/g, 'span' + span));
                    });

                    // update span new column
                    column_html = column_html.replace(/span[0-9]{1,2}/g, 'span' + exclude_span);

                    // add new column
                    parentForm.find(".jsn-pb-row-content").append(column_html);

                    // update width for all columns
                    self.updateSpanWidth(countColumn, self.maxWidth, parentForm);
                }
                // actiave resizable for columns
                self.initResizable(countColumn);
                $('body').trigger('jsnpb_changed');
            });
        },

        // Confirm message when delete item
        removeConfirmMsg: function (item, type, column_to_row, callback) {
            var self = this;
            var msg = "";
            var show_confirm = 1;
            switch (type) {
                case 'row':
                    if (item.find('.jsn-column-content').find('.shortcode-container').length == 0)
                        show_confirm = 0;
                    msg = JSNPbParams.pbstrings.ALERT_DELETE_ROW;
                    break;
                case 'column':
                    var check_item = (column_to_row != null) ? column_to_row : item;
                    if (check_item.find('.shortcode-container').length == 0)
                        show_confirm = 0;
                    msg = JSNPbParams.pbstrings.ALERT_DELETE_COLUMN;
                    break;
                default:
                    msg = JSNPbParams.pbstrings.ALERT_DELETE_ELEMENT;
            }

            var confirm_ = show_confirm ? confirm(msg) : true;
            if (confirm_) {
                if (type == 'row') {
                    item.animate({opacity: 0}, 300, self.effect, function () {
                        item.animate({height: 0}, 300, self.effect, function () {
                            item.remove();
                            self.moveItemDisable(self.wrapper);
                            setTimeout(function () {
                                $('body').trigger('jsnpb_changed');
                            }, 601);
                        });
                    });
                }
                else if (type == 'column') {
                    item.animate({height: 0}, 500, self.effect, function () {
                        item.remove();
                        if (callback != null) callback();
                        setTimeout(function () {
                            $('body').trigger('jsnpb_changed');
                        }, 601);
                    });
                }
                else {
                    item.remove();
                    setTimeout(function () {
                        $('body').trigger('jsnpb_changed');
                    }, 601);
                }

                return true;
            } else {
                return false;
            }
        },

        // Remove Row/Column/Element Handle
        removeItem: function () {
            var self = this;
            var this_wrapper = this.wrapper;
            this.wrapper.delegate(this.deletebtn, "click", function () {
                if ($(this).hasClass('row')) {
                    self.removeConfirmMsg($(this).parents(".jsn-row-container"), 'row');
                }
                else if ($(this).hasClass('column')) {
                    var totalWidth = this_wrapper.width();
                    var parentForm = $(this).parents(".jsn-row-container");
                    var countColumn = parentForm.find(".jsn-column-container").length;
                    countColumn -= 1;
                    if (countColumn == 0) {
                        // remove this row
                        self.removeConfirmMsg(parentForm, 'column', $(this).parents(".jsn-column-container"));
                        return true;
                    }
                    var span = parseInt(12 / countColumn);
                    var exclude_span = (12 % countColumn != 0) ? span + (12 % countColumn) : span;

                    // remove current column
                    //self.removeConfirmMsg($(this).parents(".jsn-column-container"), 'column');
                    if (!self.removeConfirmMsg($(this).parents(".jsn-column-container"), 'column', null, function () {
                            // update span remain columns
                            parentForm.find(".jsn-column-container").each(function () {
                                $(this).attr('class', $(this).attr('class').replace(/span[0-9]{1,2}/g, 'span' + span));
                                $(this).html($(this).html().replace(/span[0-9]{1,2}/g, 'span' + span));
                            });

                            // update span last column
                            parentForm.find(".jsn-column-container").last().html(parentForm.find(".jsn-column-container").last().html().replace(/span[0-9]{1,2}/g, 'span' + exclude_span));

                            // update width for all columns
                            self.updateSpanWidth(countColumn, totalWidth, parentForm);
                            // actiave resizable for columns
                            self.initResizable(countColumn);
                            setTimeout(function () {
                                $('body').trigger('jsnpb_changed');
                            }, 501);
                        }))
                        return false;
                }
                else {
                    if (self.removeConfirmMsg($(this).parents(".jsn-row-container"), 'row')) {
                        self.updateSpanWidthPBDL(self, self.wrapper, self.wrapper.width());
                        $('body').trigger('jsnpb_changed');
                    }
                }
            });
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
        },

        // Sortable Column
        sortableColumn: function (_this) {
            // Sortable for columns in row
            $(_this).find(".jsn-pb-row-content").each(function () {
                $(this).sortable({
                    axis: 'x',
                    helper: "clone",
                    //   placeholder:'ui-state-highlight',
                    start: $.proxy(function (event, ui) {
                        ui.placeholder.append(ui.item.children().clone());
                        $(ui.item).parents(".jsn-pb-row-content").find(".ui-resizable-handle").hide();
                    }, this),
                    handle: ".jsn-handle-drag",
                    change: function () {
                        JSNallowChange = true;
                    },
                    stop: $.proxy(function (event, ui) {
                        if (JSNallowChange) {
                            $('body').trigger('jsnpb_changed');
                            JSNallowChange = false;
                            $(ui.item).parents(".jsn-pb-row-content").find(".ui-resizable-handle").show();
                        }
                    }, this)
                });
            });
            $(_this).find(".jsn-pb-row-content").disableSelection();
        },

        // Sortable Element
        sortableElement: function () {
            $(".jsn-element-container").each(function () {
                $(this).sortable({
                    connectWith: ".jsn-element-container",
                    placeholder: "ui-state-highlight",
                    handle: '.element-drag',
                    tolerance: 'pointer',
                    change: function (e, ui) {
                        var width = ui.placeholder.width();
                        ui.helper.width(width);
                        JSNallowChange = true;
                    },
                    stop: function () {
                        if (JSNallowChange) {
                            $('body').trigger('jsnpb_changed');
                            JSNallowChange = false;
                        }
                    }
                });
            });
            $(".jsn-element-container").disableSelection();
        },

        // Show Add Elements Box
        addElement: function () {
            var self = this;
            var winW = parent.document.body.clientWidth;
            var winH = parent.document.body.clientHeight;
            this.wrapper.delegate(this.addelements, "click", function (e) {
                var loading = $('<div id="jsn_overlay"><div class="jsn-modal-overlay"></div><div class="jsn-modal-indicator"></div></div>');
                loading.appendTo('body');
                $('.jsn-modal-overlay', loading).show();
                $('.jsn-modal-indicator', loading).show();

                e.stopPropagation();
                self.wrapper.trigger('jsnpb-add-more-element-click', $(this));
                offset_ = {};
                $("#pb-add-element").offset(offset_).click(function (e) {
                    e.stopPropagation();
                });
                //modalW = parent.document.body.clientWidth * 0.9;
                //modalH = parent.document.body.clientHeight * 0.75;
        		modalW = $(window).width()*0.9;
        		modalH = $(window).height()*0.8;
                $("#pb-add-element").dialog({
                    title: "Select element",
                    height: modalH,
                    width: modalW,
                    draggable: false,
                    resizable: false,
                    modal: false,
                    backdrop: 'static',
                    position: 'absolute',
                    open: function (event, ui) {
                        /* Disable window scroll*/
                        $("body").css({overflow: 'hidden'});
                        /* Reset modal size & information*/
                        $('.ui-dialog').css('z-index', 103);
                        $('.ui-widget-overlay').css('z-index', 102);
                        $('#pb-add-element').css('overflow', 'hidden');
                        $('.jsn-master .jsn-elementselector .jsn-items-list').css('overflow', 'auto').css('height', modalH * 0.76);
                        $('.jsn-master .jsn-elementselector .jsn-items-list li button').css('height', '90px');
                        $('#pb-add-element .popover-content').css('padding', '0px');
                        /* check if searchbox is not exists, add the search box*/
                        if (!$('.ui-dialog-titlebar').hasClass('jsn_has_search_box')) {
                            $('.ui-dialog-titlebar').append('<div class="pull-right"><div class="jsn-quick-search"><input id="jsn-quicksearch-field" class="input search-query" type="text" placeholder="Search..."><a id="reset-search-btn" class="jsn-reset-search" title="Clear Search" href="javascript:void(0);"><i class="icon-remove"></i></a></div></div></div>');
                            $('.pull-right').css('padding-right', '5px').css('padding-top', '2px');
                            $('.ui-dialog-titlebar').addClass("jsn_has_search_box");
                        }
                        // focus searchbox on firefox
                        $('#jsn-quicksearch-field').click(function () {
                            $(this).focus();
                        })
                      
                        self.searchElement();

                        /*Reset modal size when window resize*/
                        $(window).resize(function () {
                            modalW = parent.document.body.clientWidth * 0.9;
                            modalH = parent.document.body.clientHeight * 0.75;
                            winW = parent.document.body.clientWidth;
                            $('.jsn-master .jsn-elementselector .jsn-items-list').css('overflow', 'auto').css('height', modalH - 220);
                            $("#pb-add-element").css('height', modalH - 200);
                            $('.ui-dialog').css('width', modalW);
                            $('.ui-dialog').css('left', winW / 2 - modalW / 2);
                        });
                        self.initResizable();
                    },
                    buttons: {
                        Close: function () {
                            $('.jsn-modal-overlay').remove();
                            $('.jsn-modal-indicator').remove();
                            $('#jsn_overlay').remove();
                            $("body").css({overflow: 'auto'});
                            $(this).dialog("close");
                        }
                    }
                });
            });
        },


        // Search elements in "Add Element" Box
        searchElement: function () {
            var self = this;
            $.fn.delayKeyup = function (callback, ms) {
                var timer = 0;
                var el = $(this);
                $(this).keyup(function () {
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        callback(el);
                    }, ms);
                });
                return $(this);
            };
            $('#jsn-quicksearch-field').keydown(function (e) {
                if (e.which == 13)
                    return false;
            });
            $('#jsn-quicksearch-field').delayKeyup(function (el) {
                if ($(el).val() != '') {
                    $("#reset-search-btn").show();
                } else {
                    $("#reset-search-btn").hide();
                }
                self.filterElement($(el).val(), 'value');
            }, 500);
            $('#pb-add-element .jsn-filter-button').change(function () {
                self.filterElement($(this).val(), 'type');
            });
            $("#reset-search-btn").click(function () {
                self.filterElement("all");
                $(this).hide();
                $("#jsn-quicksearch-field").val("");
            });
        },

        // Filter elements in "Add Element" Box
        filterElement: function (value, filter_data) {
            var resultsFilter = $('#pb-add-element .jsn-items-list');
            if (value != "all") {
                $(resultsFilter).find("li").hide();
                $(resultsFilter).find("li").each(function () {
                    var textField = (filter_data == 'value') ? $(this).attr("data-modal-title").toLowerCase() : $(this).attr("data-type").toLowerCase();
                    if (textField.search(value.toLowerCase()) === -1) {
                        $(this).hide();
                    } else {
                        $(this).fadeIn(500);
                    }
                });
            }
            else $(resultsFilter).find("li").show();
        }
    };
})(JoomlaShine.jQuery);
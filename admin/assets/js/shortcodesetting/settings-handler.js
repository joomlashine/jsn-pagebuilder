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
    $.ShortcodeSetting = {};
    $.ShortcodeSetting.selectModal = null;
    $.ShortcodeSetting.initSize = 1;
    /**
     * Update shortcode params to an input
     * which will be submitted.
     */
    $.ShortcodeSetting.updateShortcodeParams = function (container, curr_iframe) {
    	
    	if (container == null) {
            return;
        }
    	
        var tmp_content = [];
        var params_arr = {};
        var shortcode_name, el_type = 'element';

        shortcode_name = $('#shortcode_name', container).val();
        tmp_content.push('[' + shortcode_name);
        var sc_content = '';
        $('.control-group', container).each(function () {
            if (!$(this).hasClass('pb_hidden_depend')) {
                $(this).find("[id^='param-']").each(function () {
                    if (
                        $(this).parents(".tmce-active").length == 0 && !$(this).hasClass('tmce-active')
                        && $(this).parents(".html-active").length == 0 && !$(this).hasClass('html-active')
                        && !$(this).parents("[id^='parent-param']").hasClass('pb_hidden_depend')
                        && $(this).attr('id').indexOf('parent-') == -1
                        && $(this).parents('.jsn_tiny_mce').length == 0 && !$(this).hasClass('jsn_tiny_mce')
                    ) {
                        var id = $(this).attr('id');
                        if ($(this).attr('data-role') == 'content') {
                            sc_content = $(this).val();
                        } else {
                            if (($(this).is(":radio") || $(this).is(":checkbox")) && !$(this).is(":checked"));
                            else {
                                if (!params_arr[id.replace('param-', '')] || id.replace('param-', '') == 'title_font_face_type' || id.replace('param-', '') == 'title_font_face_value' || id.replace('param-', '') == 'font_face_type' || id.replace('param-', '') == 'font_face_value' || id.replace('param-', '') == 'image_type_post' || id.replace('param-', '') == 'image_type_page' || id.replace('param-', '') == 'image_type_category') {
                                    params_arr[id.replace('param-', '')] = $(this).val();
                                } else {
                                    params_arr[id.replace('param-', '')] += '__#__' + $(this).val();
                                }
                            }
                        }

                        // data-share
                        if ($(this).attr('data-share')) {
                            var share_element = $('#' + $(this).attr('data-share'));
                            var share_data = share_element.text();
                            if (share_data == "" || share_data == null)
                                share_element.text($(this).val());
                            else {
                                share_element.text(share_data + ',' + $(this).val());
                                var arr = share_element.text().split(',');
                                $.unique(arr);
                                share_element.text(arr.join(','));
                            }

                        }

                        // data-merge
                        if ($(this).parent().hasClass('merge-data')) {
                            var pb_merge_data = window.parent.jQuery.noConflict()('#jsn_view_modal').contents().find('#pb_merge_data');
                            pb_merge_data.text(pb_merge_data.text() + $(this).val());
                        }

                        // table
                        if ($(this).attr("data-role") == "extract") {
                            var extract_holder = window.parent.jQuery.noConflict()('#jsn_view_modal').contents().find('#pb_extract_data');
                            extract_holder.text(extract_holder.text() + $(this).attr("id") + ':' + $(this).val() + '#');
                        }
                    }

                });
            }
        });

        // update tinyMCE content
        var tinyContent = '';
        $('.jsn_tiny_mce').each(function () {
            if ($(this).hasClass('role_content')) {
                if ($(this).hasClass('tinymce') || $(this).hasClass('jce')) {
                    try {
                        //tinyContent = tinyMCE.activeEditor.getContent();
                        tinyContent = tinyMCE.get($(this).attr('data-new-id')).getContent();
                    }
                    catch (err) {
                        tinyContent = $(this).html();
                    }
                }
                else {
                    tinyContent = $(this).wysiwyg('getContent');
                }
            }
        });
        sc_content += tinyContent;

        // for shortcode which has sub-shortcode
        if (container.find('.has_submodal').length > 0 || container.find('.submodal_frame_2').length > 0) {
            var sub_sc_content = [];
            $("[name^='shortcode_content']", container).each(function () {
                if (!$(this).hasClass('exclude_gen_shortcode') && $(this).attr('id') != "shortcode_content") {
                    sub_sc_content.push($(this).text());
                }
            })
            sc_content = sub_sc_content.join('');
        }

        // wrap key, value of params to this format: key = "value"
        $.each(params_arr, function (key, value) {
            if (value) {
                if (value instanceof Array) {
                    value = value.toString();
                }
                tmp_content.push(key + '="' + value.replace(/\"/g, "&quot;") + '"');
            }
        });

        tmp_content.push(']' + sc_content + '[/' + shortcode_name + ']');
        tmp_content = tmp_content.join(' ');

        $('#shortcode_content', container).html(tmp_content);
    }

    $.ShortcodeSetting.RandomString = function()
    {
    	var text = "";
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		
		for( var i=0; i < 5; i++ )
		{	
		    text += possible.charAt(Math.floor(Math.random() * possible.length));
		}
		
		return text;    	
    }
    
    /**
     * Select element(s) in setting modal
     *
     */
    $.ShortcodeSetting.selector = function (curr_iframe, element) {
        var $selector = (curr_iframe != null && curr_iframe.contents() != null) ? curr_iframe.contents().find(element) : $(element);
        return $selector;
    }

    // Show tab in Modal Options
    $.ShortcodeSetting.tab = function () {
        $('#pb_option_tab a[href="#content"]').on('click', function () {
            if ($('#pb_previewing').val() == '1')
                return;
            $('#pb_previewing').val('1');
            $.ShortcodeSetting.shortcodePreview();
        });
        if (!$('.jsn-tabs').find("#Notab").length)
            $('.jsn-tabs').tabs();
        return true;
    }

    $.ShortcodeSetting.select2 = function () {
        $(".select2", this.container).each(function () {
            var share_element = window.parent.jQuery.noConflict()('#jsn_view_modal').contents().find('#' + $(this).attr('data-share'));
            var share_data = [];
            if (share_element && share_element.text() != "") {
                share_data = share_element.text().split(',');
                share_data = $.unique(share_data);
            }
            $(this).css('width', '300px');
            $(this).select2({
                tags: share_data,
                maximumInputLength: 10
            });
        })

        $('.select2-select', this.container).each(function () {
            var id = $(this).attr('id');
            if ($('#' + id + '_select_multi').val()) {
                var arr_select_multi = $('#' + id + '_select_multi').val().split('__#__');
                $(this).val(arr_select_multi).select2();
            } else {
                $(this).select2();
            }
        });

        $.ShortcodeSetting.select2_color();
    }

    $.ShortcodeSetting.select2_color = function () {
        function format(state) {
            if (!state.id) return state.text; // optgroup
            var type = state.id.toLowerCase();
            type = type.split('-');
            type = type[type.length - 1];
            return "<img class='color_select2_item' src='" + JSNPbParams.rootUrl + "administrator/components/com_pagebuilder/assets/images/icons-16/btn-color/" + type + ".png'/>" + state.text;
        }

        $('.color_select2').not('.hidden').each(function () {
            $(this).find('select').each(function () {
                $(this).select2({
                    formatResult: format,
                    formatSelection: format,
                    escapeMarkup: function (m) {
                        return m;
                    }
                });
            });
        });
    };

    $.ShortcodeSetting.changeDependency = function (dp_selector) {
        if (!dp_selector)
            return false;

        $('.modalOptions').each(function () {
            var modalOption = $(this);
            modalOption.delegate(dp_selector, 'change', function () {
                var this_id = $(this).attr('id');
                var this_val = $(this).val();
                $.ShortcodeSetting.toggleDependency(this_id, this_val, modalOption);
            });
        });
    };

    // Show or hide dependency params
    $.ShortcodeSetting.toggleDependency = function (this_id, this_val, modalOption) {
        if (!this_id || !this_val || !modalOption) {
            return;
        }

        modalOption.find('.pb_depend_other[data-depend-element="' + this_id + '"]').each(function () {
            var operator = $(this).attr('data-depend-operator');
            var compare_value = $(this).attr('data-depend-value');
            switch (operator) {
                case '=':
                {
                    var check_ = 0;
                    if (compare_value.indexOf('__#__') > 0) {
                        var values_ = compare_value.split('__#__');
                        check_ = ($.inArray(this_val, values_) >= 0);
                    }
                    else
                        check_ = (this_val == compare_value);
                    if (check_)
                        $(this).removeClass('pb_hidden_depend');
                    else
                        $(this).addClass('pb_hidden_depend');
                }
                    break;
                case '>':
                {
                    if (this_val > compare_value)
                        $(this).removeClass('pb_hidden_depend');
                    else
                        $(this).addClass('pb_hidden_depend');
                }
                    break;
                case '<':
                {
                    if (this_val < compare_value)
                        $(this).removeClass('pb_hidden_depend');
                    else
                        $(this).addClass('pb_hidden_depend');
                }
                    break;
                case '!=':
                {
                    if (this_val != compare_value)
                        $(this).removeClass('pb_hidden_depend');
                    else
                        $(this).addClass('pb_hidden_depend');
                }
            }
            $.ShortcodeSetting.secondDependency($(this).attr('id'), $(this).hasClass('pb_hidden_depend'), $(this).find('select').hasClass('no_plus_depend'), modalOption);

            $('body').trigger('pb_after_change_depend');
        });
    };

    $.ShortcodeSetting.secondDependency = function (this_id, hidden, allow, modalOption) {
        if (!this_id || !modalOption) {
            return;
        }
        this_id = this_id.replace('parent-', '');
        modalOption.find('.pb_depend_other[data-depend-element="' + this_id + '"]').each(function () {
            if (hidden)
                $(this).addClass('pb_hidden_depend2');
            else
                $(this).removeClass('pb_hidden_depend2');
        });
        if (!allow) {
            modalOption.find('pb_depend_other[data-depend-element="' + this_id + '"]').each(function () {
                $(this).removeClass('pb_hidden_depend2');
            });
        }
        // hide label if all options in .controls div have 'pb_hidden_depend' class
        modalOption.find('.controls').each(function () {
            var hidden_div = 0;
            $(this).children().each(function () {
                if ($(this).hasClass('pb_hidden_depend'))
                    hidden_div++;
            });
            if (hidden_div > 0 && hidden_div == $(this).children().length) {
                $(this).parent('.control-group').addClass('margin0');
                $(this).prev('.control-label').hide();
            }
            else {
                $(this).parent('.control-group').removeClass('margin0');
                $(this).prev('.control-label').show();
            }
        });
    };

    $.ShortcodeSetting.updateState = function (state) {
        if (state != null) {
            $.ShortcodeSetting.doing = state;
        }
        else {
            if ($.ShortcodeSetting.doing == null || $.ShortcodeSetting.doing)
                $.ShortcodeSetting.doing = 0;
            else
                $.ShortcodeSetting.doing = 1;
        }
    }

    $.ShortcodeSetting.renderModal = function () {
        if ($("#modalOptions").length == 0) return false;
        var params = '';

        // toggle dependency params
        var modalOptions = $('.modalOptions');
        modalOptions.each(function () {
            var modalOption = $(this);
            modalOption.find('.pb_has_depend').each(function () {
                if (($(this).is(":radio") || $(this).is(":checkbox")) && !$(this).is(":checked")) return;
                var this_id = $(this).attr('id');
                var this_val = $(this).val();
                $.ShortcodeSetting.toggleDependency(this_id, this_val, modalOption);
            });
        });
    };

    $.ShortcodeSetting.initSelectImage = function (container) {
        $('.select-media-remove', container).on('click', function () {
            var _input = $(this).closest('div').find('input[type="text"]');
            _input.attr('value', '');
            _input.trigger('change');
        });

        $('.select-media', container).on('click', function () {
            var value = $(this).prev('.select-media-text').val();
            var id = $(this).prev('.select-media-text').attr('id');
            $.ShortcodeSetting.selectModal = new $.JSNModal({
                frameId: 'jsn_select_image_modal',
                jParent: window.parent.jQuery.noConflict(),
                title: 'Choose File',
                dialogClass: 'jsn-dialog jsn-bootstrap3',
                url: JSNPbParams.rootUrl + "plugins/system/jsnframework/libraries/joomlashine/choosers/media/index.php?component=com_pagebuilder&root=images&current=" + value + "&element=" + id + "&handler=setSelectImage",
                buttons: [{
                    'text': 'Cancel',
                    'id': 'close',
                    'class': 'ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only',
                    'click': function () {
                        $.ShortcodeSetting.selectModal.close();
                    }
                }],
                width: parent.document.body.clientWidth * 0.9,
                height: parent.document.body.clientHeight * 0.75,
                loaded: function (obj, iframe) {
                    obj.container.closest('.ui-dialog').css('z-index', 10014);
                }
            });
            $.ShortcodeSetting.selectModal.show();
        });
    },

        window.parent.setSelectImage = function (value, id) {
    		$(id).val(value.replace(/^\//, ""));
            $.ShortcodeSetting.selectModal.close();
            $.HandleElement.hideLoading();
            $(id).trigger('change');
        }

    //Setup for select module elements
    $.ShortcodeSetting.selectModule = function () {
        $('#parent-param-module_name .select-module-remove').on('click', function () {
            var _input = $(this).closest('div').find('input[type="text"]');
            _input.attr('value', '');
            _input.trigger('change');
        });
        $('.select-module').on('click', function () {
            //$.HandleElement.showLoading();
            var value = $(this).prev('.select-module-text').val();
            var id = $(this).prev('select-module-text').attr('id');
            var modal_width, modal_height;
            modal_width = parent.document.body.clientWidth * 0.9;
            modal_height = parent.document.body.clientHeight * 0.75;
            $.ShortcodeSetting.selectModal = new $.JSNModal({
                frameId: 'jsn_select_image_modal',
                jParent: window.parent.jQuery.noConflict(),
                iframe: true,
                dialogClass: 'jsn-dialog jsn-bootstrap3',
                title: 'Select Module',
                url: JSNPbParams.rootUrl + "administrator/index.php?option=com_pagebuilder&view=selectmodule&tmpl=component&current=" + value + "&element=" + id + "&handler=setSelectModule",
                buttons: [{
                    'text': 'Cancel',
                    'id': 'close',
                    'class': 'ui-button ui-widget ui-state-default ui-correr-all ui-button-text-only',
                    'click': function () {
                        $.ShortcodeSetting.selectModal.close();
                        //$.HandleElement.finalize();
                    }
                }],
                width: modal_width,
                height: modal_height,
                fadeIn: 200,
                scrollable: true,
                loaded: function (obj, iframe) {
                    obj.container.closest('.ui-dialog').css('z-index', 10014);
                    var $frame = this.jParent("#" + this.frameId);
                    var $titleBar = $frame.parent().parent().find('.ui-dialog-titlebar').append('<div class="filter-search btn-group pull-right"><div class="jsn-quick-search"><input id="filter_search" name="filter_search" class="input search-query" type="text" placeholder="Search..."><a id="reset-search-btn" class="jsn-reset-search" title="Clear Search" href="javascript:void(0);"><i class="icon-remove"></i></a></div></div></div>');
                    // focus searchbox on firefox
                    //$titleBar.find('#filter_search').click(function () {
                    //  $(this).focus();
                    //})
                    this.jParent.fn.delayKeyup = function (callback, ms) {
                        var timer = 0;
                        var md = $(this);
                        $(this).keyup(function () {
                            clearTimeout(timer);
                            timer = setTimeout(function () {
                                callback(md);
                            }, ms);
                        });
                        return $(this);
                    };

                    $titleBar.find('#filter_search').keydown(function (e) {
                        if (e.which == 13) {
                            return false;
                        }
                    });

                    $titleBar.find('#filter_search').delayKeyup(function (md) {
                        if ($(md).val() != '') {
                            $titleBar.find("#reset-search-btn").show();
                        } else {
                            $titleBar.find("#reset-search-btn").hide();
                        }
                        self.filterModule($(md).val(), 'value');
                    }, 500);

                    $titleBar.find("#reset-search-btn").click(function () {
                        self.filterModule("all");
                        $(this).hide();
                        $titleBar.find("#filter_search").val("");
                    });
                    filterModule = function (value) {
                        var resultsFilter = $frame.contents().find('#jsn-module-container');
                        if (value != "all") {
                            $(resultsFilter).find(".jsn-item-type").hide();
                            $(resultsFilter).find(".jsn-item-type").each(function () {
                                var findDiv = $(this).find("div");
                                var textField = textField ? findDiv.attr("data-module-title").toLowerCase() : findDiv.attr("title").toLowerCase();
                                if (textField.search(value.toLowerCase()) === -1) {
                                    $(this).hide();
                                } else {
                                    $(this).fadeIn(500);
                                }
                            });
                        }
                        else $(resultsFilter).find(".jsn-item-type").show();
                    }
                    //$frame.contents().find('.loading-bar').hide();
                    //$.HandleElement.hideLoading();

                    var $_filterSearch = $titleBar.find('.filter-search');
                    var _c = $_filterSearch.length;
                    for (var _i = 0; _i < _c; _i++) {
                        if (_i > 0) {
                            $_filterSearch[_i].remove();
                        } else {
                            $($_filterSearch[_i]).find("input").val("");
                        }
                    }
                }
            });
            $.ShortcodeSetting.selectModal.show();
        });
    },
        window.parent.setSelectModule = function (value, id) {
            $(id).val(value);
            $.ShortcodeSetting.selectModal.close();
            //$.HandleElement.finalize();
            //$.HandleElement.hideLoading();
            //$.ShortcodeSetting.shortcodePreview();
            $(id).trigger('change');
        };

    $.ShortcodeSetting.filterModule = function (value) {
        var resultsFilter = $('#jsn-module-container');
        if (value != "all") {
            $(resultsFilter).find(".jsn-item-type").hide();
            $(resultsFilter).find(".jsn-item-type").each(function () {
                var textField = $(this).find("div").attr("data-module-title").toLowerCase();
                if (textField.search(value.toLowerCase()) === -1) {
                    $(this).hide();
                } else {
                    $(this).fadeIn(500);
                }
            });
        }
        else $(resultsFilter).find(".jsn-item-type").show();

    }
    $.ShortcodeSetting.search = function () {
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
        $('jsn-quicksearch-field').delayKeyup(function (el) {
            if ($(el).val() != '') {
                $('#reset-search-btn').show();
            } else {
                $('#reset-search-btn').hide();
            }
            self.filterModule($(el).val(), 'value');
        }, 500);
        $('.jsn-filter-button').change(function () {
            self.filterModule($(this).val(), 'type');
        });
        $('#reset-search-btn').click(function () {
            self.filterModule("all");
            $(this).hide();
            $('#jsn-quicksearch-field').val("");
        });
    },

        // Setup for tiny-mce
        $.ShortcodeSetting.setTinyMCE = function (selector) {
            $(selector).each(function () {
                var current_id = $(this).attr('id');
                if (current_id) {

                    var language = $(this).attr('data-lang');
                    var directionality = $(this).attr('data-direction');
                    var document_base_url = $(this).attr('data-url');
                    var new_id = current_id + '_' + $.ShortcodeSetting.RandomString();
                    var currentElement = $('#' + current_id);

                    if ($(this).hasClass('tinymce'))
                    {
                        currentElement.attr('data-new-id', new_id);
                        currentElement.attr('id', new_id);

                        tinymce.init({
                            selector: '#' + new_id,
                            // General
                            directionality: directionality,
                            language : language,
                            mode : "specific_textareas",
                            autosave_restore_when_empty: false,
                            skin : "lightgray",
                            theme : "modern",
                            schema: "html5",
                            // Cleanup/Output
                            inline_styles : true,
                            gecko_spellcheck : true,
                            entity_encoding : "raw",
                            valid_elements : "",
                            extended_valid_elements : "hr[id|title|alt|class|width|size|noshade]",
                            force_br_newlines : false, force_p_newlines : true, forced_root_block : 'p',
                            toolbar_items_size: "small",
                            invalid_elements : "script,applet,iframe",
                            // Plugins
                            plugins : "table link image code hr charmap autolink lists importcss",
                            // Toolbar
                            toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect | bullist numlist",
                            toolbar2: "outdent indent | undo redo | link unlink anchor image code | hr table | subscript superscript | charmap",
                            removed_menuitems: "newdocument",
                            // URL
                            relative_urls : false,
                            remove_script_host : false,
                            document_base_url : document_base_url,
                            // Layout
                            importcss_append: true,
                            // Advanced Options
                            resize: "both"
                        });
                        $('#' + new_id).attr('id', current_id);
                    }
                    else if ($(this).hasClass('jce'))
                    {
                        var token = $(this).data('token');
                        var etag = $(this).data('etag');
                        var componentId = $(this).data('component_id');

                        currentElement.attr('data-new-id', new_id);
                        currentElement.attr('id', new_id);
                        $(this).css('opacity', 0);

                        WFEditor.init({
                            editor_selector: 'jce',
                            toggle: false,
                            setup: function (ed) {
                                ed.onChange.add(function(ed, l) {
                                    currentElement.html(l.content);
                                });
                                ed.onInit.add(function(ed) {
                                    $("#param-body_tbl").css("width", "98%");
                                });
                            },
                            base_url: document_base_url,
                            language: 'en',
                            directionality: directionality,
                            token: token,
                            etag: etag,
                            theme: "advanced",
                            plugins: "autolink,cleanup,core,code,colorpicker,upload,format,charmap,contextmenu,browser,inlinepopups,media,clipboard,searchreplace,directionality,fullscreen,preview,source,table,textcase,print,style,nonbreaking,visualchars,visualblocks,xhtmlxtras,imgmanager,anchor,link,spellchecker,article,lists,formatselect,styleselect,fontselect,fontsizeselect,fontcolor,importcss,advlist,wordcount",
                            language_load: false,
                            component_id: componentId,
                            theme_advanced_buttons1: "newdocument,undo,redo,|,bold,italic,underline,strikethrough,justifyfull,justifycenter,justifyleft,justifyright,|,blockquote,formatselect,styleselect,removeformat,cleanup",
                            theme_advanced_buttons2: "fontselect,fontsizeselect,forecolor,backcolor,|,cut,copy,paste,pastetext,indent,outdent,numlist,bullist,sub,sup,textcase,charmap,hr",
                            theme_advanced_buttons3: "ltr,rtl,source,search,replace,|,table_insert,delete_table,|,row_props,cell_props,|,row_before,row_after,delete_row,|,col_before,col_after,delete_col,|,split_cells,merge_cells",
                            theme_advanced_buttons4: "visualaid,visualchars,visualblocks,nonbreaking,style,cite,abbr,acronym,del,ins,attribs,anchor,unlink,link,imgmanager,spellchecker,readmore,pagebreak",
                            theme_advanced_resizing: true,
                            content_css: "/templates/protostar/css/template.css?d9fd6ea4b9c3aab88acbafa5d60f2a6f",
                            schema: "mixed",
                            invalid_elements: "iframe,script,style,applet,body,bgsound,base,basefont,frame,frameset,head,html,id,ilayer,layer,link,meta,name,title,xml",
                            remove_script_host: false,
                            file_browser_callback: function (name, url, type, win) {
                                tinyMCE.activeEditor.plugins.browser.browse(name, url, type, win);
                            },
                            source_theme: "codemirror",
                            imgmanager_upload: {"max_size": 1024, "filetypes": ["jpg", "jpeg", "png", "gif"]},
                            spellchecker_engine: "browser",
                            formatselect_blockformats: {
                                "advanced.paragraph": "p",
                                "advanced.div": "div",
                                "advanced.div_container": "div_container",
                                "advanced.address": "address",
                                "advanced.pre": "pre",
                                "advanced.h1": "h1",
                                "advanced.h2": "h2",
                                "advanced.h3": "h3",
                                "advanced.h4": "h4",
                                "advanced.h5": "h5",
                                "advanced.h6": "h6",
                                "advanced.code": "code",
                                "advanced.samp": "samp",
                                "advanced.span": "span",
                                "advanced.section": "section",
                                "advanced.article": "article",
                                "advanced.aside": "aside",
                                "advanced.figure": "figure",
                                "advanced.dt": "dt",
                                "advanced.dd": "dd"
                            },
                            fontselect_fonts: "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
                            compress: {"javascript": 1, "css": 1}
                        });

                        currentElement.attr('id', current_id);
                    }
                    else
                    {
                        currentElement.wysiwyg({
                            controls: {
                                bold: {visible: true},
                                italic: {visible: true},
                                underline: {visible: true},
                                strikeThrough: {visible: true},

                                justifyLeft: {visible: true},
                                justifyCenter: {visible: true},
                                justifyRight: {visible: true},
                                justifyFull: {visible: true},

                                indent: {visible: true},
                                outdent: {visible: true},

                                subscript: {visible: true},
                                superscript: {visible: true},

                                undo: {visible: true},
                                redo: {visible: true},

                                insertOrderedList: {visible: true},
                                insertUnorderedList: {visible: true},
                                insertHorizontalRule: {visible: true},

                                h1: {
                                    visible: true,
                                    className: 'h1',
                                    command: ($.browser.msie || $.browser.safari || $.browser.chrome || $.browser.webkit || $.browser.mozilla || $.browser.navigation || $.browser.opera) ? 'formatBlock' : 'heading',
                                    arguments: ($.browser.msie || $.browser.safari || $.browser.chrome || $.browser.webkit || $.browser.mozilla || $.browser.navigation || $.browser.opera) ? '<h1>' : 'h1',
                                    tags: ['h1'],
                                    tooltip: 'Header 1'

                                },
                                h2: {
                                    visible: true,
                                    className: 'h2',
                                    command: ($.browser.msie || $.browser.safari || $.browser.chrome || $.browser.webkit || $.browser.mozilla || $.browser.navigation || $.browser.opera) ? 'formatBlock' : 'heading',
                                    arguments: ($.browser.msie || $.browser.safari || $.browser.chrome || $.browser.webkit || $.browser.mozilla || $.browser.navigation || $.browser.opera) ? '<h2>' : 'h2',
                                    tags: ['h2'],
                                    tooltip: 'Header 2'
                                },
                                h3: {
                                    visible: true,
                                    className: 'h3',
                                    command: ($.browser.msie || $.browser.safari || $.browser.chrome || $.browser.webkit || $.browser.mozilla || $.browser.navigation || $.browser.opera) ? 'formatBlock' : 'heading',
                                    arguments: ($.browser.msie || $.browser.safari || $.browser.chrome || $.browser.webkit || $.browser.mozilla || $.browser.navigation || $.browser.opera) ? '<h3>' : 'h3',
                                    tags: ['h3'],
                                    tooltip: 'Header 3'
                                },
                                h4: {
                                    visible: true,
                                    className: 'h4',
                                    command: ($.browser.msie || $.browser.safari || $.browser.chrome || $.browser.webkit || $.browser.mozilla || $.browser.navigation || $.browser.opera) ? 'formatBlock' : 'heading',
                                    arguments: ($.browser.msie || $.browser.safari || $.browser.chrome || $.browser.webkit || $.browser.mozilla || $.browser.navigation || $.browser.opera) ? '<h4>' : 'h4',
                                    tags: ['h4'],
                                    tooltip: 'Header 4'

                                },
                                h5: {
                                    visible: true,
                                    className: 'h5',
                                    command: ($.browser.msie || $.browser.safari || $.browser.chrome || $.browser.webkit || $.browser.mozilla || $.browser.navigation || $.browser.opera) ? 'formatBlock' : 'heading',
                                    arguments: ($.browser.msie || $.browser.safari || $.browser.chrome || $.browser.webkit || $.browser.mozilla || $.browser.navigation || $.browser.opera) ? '<h5>' : 'h5',
                                    tags: ['h5'],
                                    tooltip: 'Header 5'
                                },
                                h6: {
                                    visible: true,
                                    className: 'h6',
                                    command: ($.browser.msie || $.browser.safari || $.browser.chrome || $.browser.webkit || $.browser.mozilla || $.browser.navigation || $.browser.opera) ? 'formatBlock' : 'heading',
                                    arguments: ($.browser.msie || $.browser.safari || $.browser.chrome || $.browser.webkit || $.browser.mozilla || $.browser.navigation || $.browser.opera) ? '<h6>' : 'h6',
                                    tags: ['h6'],
                                    tooltip: 'Header 6'
                                },

                                cut: {visible: true},
                                copy: {visible: true},
                                paste: {visible: true},
                                html: {visible: true},
                                increaseFontSize: {visible: true},
                                decreaseFontSize: {visible: true},
                                initialContent: ''
                            }
                        });
                    }
                }
            });
        }

    $.ShortcodeSetting.shortcodePreview = function (container, callback, child_element, selector_group)
    {
        shortcode_name = $('#shortcode_name', container ).val();
        // Return if contain is not defined or the preview iframe does not exist
        if (typeof(container) == 'undefined' || !$('#framePreview',container).length) {
            return;
        }

        $.ShortcodeSetting.updateShortcodeParams(container);
        tmp_content = $('#shortcode_content', container ).val();

        if(callback)
            callback();
        var sc_content = '';
        var url		= JSNPbParams.rootUrl;
        url			+= 'administrator/index.php?option=com_pagebuilder&task=shortcode.preview&tmpl=component';
        url			+= '&shortcode_name=' + shortcode_name;
        // for shortcode which has sub-shortcode
        if ($(container, "#modalOptions").find('.has_submodal').length > 0 || $(container, '#modalOptions').find('.submodal_frame_2').length > 0) {
            var sub_sc_content = [];
            $(container, "#modalOptions [name^='shortcode_content']").each(function () {
                var sc_content = $(this).text();
                var obj = {sc_content: sc_content};
                $('#modalOption').trigger('pb_get_sub_sc', [obj]);
                if (obj.sc_content != '') {
                    sub_sc_content.push(obj.sc_content);
                }
            });
            sc_content += sub_sc_content.join('');
        }
        if ($('#shortcode_preview_iframe').length > 0){
            // asign value to a variable (for show/hide preview)
            $.ShortcodeSetting.previewData = {
                container: container,
                url : url,
                tmp_content: tmp_content
            };
            // load preview iframe
            $.ShortcodeSetting.loadIframe(container, url, tmp_content);
        }
        if($('#modalOptions .jsn-items-list').length > 0){
            $.HandleElement.sortableSubItem(container);
        }
        return false;
    }

    // load preview iframe
    $.ShortcodeSetting.loadIframe = function (container, url, tmp_content) {

        $('#pb_preview_data').remove();
        var imageValue = $('#param-image_file').val();

        if (typeof imageValue !== 'undefined' && imageValue !== '') {
            var file = imageValue.split('.');
            var arrEx = ["jpg", 'JPG', 'png', 'PNG', 'gif', 'GIF'];
            var lengthFile = file.length;
            if (arrEx.indexOf(file[lengthFile - 1]) == -1) {
                $('<span class="pd_errFile" style="color:red"></br><i>Error:File type is not supported.</i></span>').insertAfter($('#param-image_file').parent());
                $('#param-image_file').val('');
                $('#shortcode_preview_iframe').contents().find('.pb-element-container').each(function () {
                    $(this).find('img').remove();
                });
                return false;
            } else {
                $('.pd_errFile').remove();

            }
        }
        var tmp_form = $('<form action="' + url + '" id="pb_preview_data" name="pb_preview_data" method="post" target="shortcode_preview_iframe"><input type="hidden" id="pb_preview_params" name="params" value="' + encodeURIComponent(tmp_content) + '"></form>');
        tmp_form.appendTo($('body'));
        $('#iframeLoading', container).fadeIn('fast');
        $('#pb_preview_data').submit();
        $('#shortcode_preview_iframe', container).bind('load', function () {
            $('#iframeLoading', container).fadeOut('fast');
            $('#pb_previewing').val('0');
         	
        	$("#shortcode_preview_iframe").contents().find('body').delegate("*", "click", function (e) {
                e.preventDefault();
                
            });
        });
        tmp_form.remove();
    }

    // hide/show preview
    $.ShortcodeSetting.togglePreview = function () {
        $('#previewToggle *').click(function () {
            if ($(this).attr('id') == 'hide_preview') {
                $(this).addClass('hidden');
                $('#show_preview').removeClass('hidden');
                // remove iframe
                $('#preview_container iframe').remove();
            }
            else {
                $(this).addClass('hidden');
                $('#hide_preview').removeClass('hidden');
                $('#preview_container').append("<iframe scrolling='no' id='shortcode_preview_iframe' name='shortcode_preview_iframe' class='shortcode_preview_iframe' ></iframe>");
                if ($.ShortcodeSetting.previewData != null) {
                    var data = $.ShortcodeSetting.previewData;
                    $.ShortcodeSetting.loadIframe(data.container, data.url, data.tmp_content);
                }
            }
        });
    }

    $.ShortcodeSetting.actionHandle = function (container, modalParams) {
        $('.pb_action_btn .dropdown-menu a', container).on('click', function (e) {
            e.preventDefault();

            $.ShortcodeSetting.updateShortcodeParams(container);

            var action_type = $(this).attr('data-action-type');
            var action = $(this).attr('data-action');
            if (action_type && action) {
                if (action_type == 'convert') {
                    var arr_types = action.split('_to_');
                    var from = ( arr_types[0] ) ? arr_types[0] : '';
                    var to = ( arr_types[1] ) ? arr_types[1] : '';

                    if (from && to) {
                        var shortcode_content = $('#shortcode_content').html();
                        // Convert element
                        var regexp = new RegExp("pb_" + from, "g");
                        shortcode_content = shortcode_content.replace(regexp, "pb_" + to);
                        // Convert items
                        var regexp = new RegExp("pb_" + from + "_item", "g");
                        shortcode_content = shortcode_content.replace(regexp, "pb_" + to + "_item");
                        // Convert shortcode name in PageBuilder
                        var regexp = new RegExp($.ShortcodeSetting.capitalize(from), "g");
                        shortcode_content = shortcode_content.replace(regexp, $.ShortcodeSetting.capitalize(to));
                        // Check is add state
                        var shortcodeStr = $(this).closest('#form-container').find('#shortcode_content').text();
                        modalParams.modalTitle = $(this).text();

                        var jParent = window.parent.jQuery.noConflict();
                        if (typeof(jParent) == undefined) {
                            jParent = window.parent.jQuery.noConflict();
                        }
                        // trigger save element
                        jParent('.ui-dialog #close').trigger('click');
                        if (shortcode_content) {
                            $('body').trigger('on_after_convert', ['pb_' + to, shortcode_content, modalParams]);
                        }
                    }
                }
            }
        });
    }

    $.ShortcodeSetting.capitalize = function (text) {
        return text.charAt(0).toUpperCase()
            + text.slice(1).toLowerCase();
    },

        $.ShortcodeSetting.gradientPicker = function () {
            var gradientPicker = function () {
                $("input.jsn-grad-ex").each(function (i, e) {
                    $(e).next('.classy-gradient-box').first().ClassyGradient({
                        gradient: $(e).val(),
                        width: 218,
                        orientation: $('#param-gradient_direction').val(),
                        onChange: function (stringGradient, cssGradient, arrayGradient) {
                            $(e).val() == stringGradient || $(e).val(stringGradient);
                            $('#param-gradient_color_css').val(cssGradient);
                        }
                    });
                });
            }

            $('#param-background').change(function () {
                var val = $('#param-background').val();
                if (val == 'gradient') {
                    $(document).ready(function () {
                        setTimeout(function () {
                            gradientPicker();
                        }, 300);
                    });
                }
            });

            $('#param-background').trigger('change');

            // control orientation
            $('#param-gradient_direction').on('change', function () {
                var orientation = $(this).val();
                $('.classy-gradient-box').data('ClassyGradient').setOrientation(orientation);
                // update background gradient
                if (orientation == 'horizontal') {
                    $('#param-gradient_color_css').val($('#param-gradient_color_css').val().replace('left top, left bottom', 'left top, right top').replace(/\(top/g, '(left'));
                } else {
                    $('#param-gradient_color_css').val($('#param-gradient_color_css').val().replace('left top, right top', 'left top, left bottom').replace(/\(left/g, '(top'));
                }
            });
        }

    // check radio button when click button in btn-group
    $.ShortcodeSetting.buttonGroup = function () {
        var data_value;
        $('.pb-btn-group .btn').click(function (i) {
            data_value = $(this).attr('data-value');
            $(this).parent().next('.pb-btn-radio').find('input:radio[value="' + data_value + '"]').prop('checked', true);
            //$.HandleSetting.shortcodePreview();
        });
    }

    // Validator input field
    $.ShortcodeSetting.inputValidator = function () {
        var input_action = 'change paste';

        // positive value
        $('.positive-val').bind(input_action, function (event) {
            var this_val = $(this).val();
            if (parseInt(this_val) <= 0) {
                $(this).val(1);
            }
        });
    }

    /**
     * Function to calculation main containers' size of setting modal then resize them
     * following modal's size
     */
    $.ShortcodeSetting.resetSize = function (container) {
        // Get the modal's size information
        var modalW = container.width();
        var modalH = container.height();
        var winW = document.body.clientWidth;
        var columnW = modalW / 2;
        var columnHpx = modalH - 70;
        var column2W;
        var tabHeight = 100;

        // Check if the Content column exist
        var contentWrapper = $('#modalOptions', container).length ? $('#modalOptions', container) : null;
        // Check if the Preview column exist
        var previewWrapper = $('#framePreview', container).length ? $('#framePreview', container) : null;

        if (contentWrapper) {
            $('.tab-pane', contentWrapper).css('height', columnHpx).css('overflow-y', 'auto');
        }

        if (previewWrapper) {
            $(previewWrapper).css('height', columnHpx);
            $('#shortcode_preview_iframe', previewWrapper).css('height', columnHpx);
        }

        if (columnW >= 500) {
            $('#jsn_column1', container).css('width', '50%');
            $('#jsn_column2', container).show().css({'width': '49%', 'margin-left': '5px'});
        } else {
            column2W = modalW - 500;
            if (column2W > 200) {
                $('#jsn_column1', container).css('width', '500px');
                $('#jsn_column2', container).show().css('width', column2W + 'px');
            } else {
                $('#jsn_column1', container).css('width', '100%');
                $('#jsn_column2', container).hide();
            }
        }
    }

    $.ShortcodeSetting.init = function (container, modalParams) {
        modalParams = typeof(modalParams) == 'undefined' ? {} : modalParams;
        if (typeof(container) == 'undefined') {
            this.container = $('#modalOptions');
        } else {
            this.container = container;
        }
        var _this = this;

        $.ShortcodeSetting.actionHandle(container, modalParams);

        $.ShortcodeSetting.updateState();

        // Trigger action of element which has dependency elements
        $.ShortcodeSetting.changeDependency('.pb_has_depend');

        // Send ajax for loading shortcode html at first time
        $.ShortcodeSetting.renderModal();

        $.ShortcodeSetting.tab();

        $.ShortcodeSetting.setTinyMCE('.jsn_tiny_mce');

        $.ShortcodeSetting.inputValidator();

        // Open subshortcode modal setting
        $(_this.container).delegate(".jsn-add-more", "click", function (e) {
            e.preventDefault();
            var shortcodeName = $(this).closest('.has_submodal').attr('data-value');

            var shortcode = $(this).attr('data-shortcode-item');
            // Prepare modal parameters
            var modalParams = {};
            modalParams.modalTitle = $(this).attr('data-modal-title');
            modalParams.source = $(this);
            modalParams.sourceItemsContainer = $(this).closest('.item-container').find('#group_elements');
            // Show the setting modal
            $.HandleElement._showSettingModal(shortcode, null, true, false, modalParams, '');
        });

        $.ShortcodeSetting.select2();

        $.ShortcodeSetting.buttonGroup();


        $.ShortcodeSetting.refresh(_this.container, false);
        // ???
        if (typeof $('#param-el_table').val() !== 'undefined') {
            setTimeout(function () {
                $.ShortcodeSetting.shortcodePreview(_this.container);
            }, 500);
        }

        $.ShortcodeSetting.gradientPicker();
        // Load at first time
        $('#pb_previewing').val('1');
        $.ShortcodeSetting.resetSize(_this.container);
        $(window).resize(function () {
            $.ShortcodeSetting.resetSize(_this.container);
        });
    };
    //$(document).ready(function () {
    //
    //    $.ShortcodeSetting.actionHandle();
    //
    //    $.ShortcodeSetting.updateState();
    //
    //    // Trigger action of element which has dependency elements
    //    $.ShortcodeSetting.changeDependency('.pb_has_depend');
    //
    //    // Send ajax for loading shortcode html at first time
    //    $.ShortcodeSetting.renderModal();
    //
    //    $.ShortcodeSetting.tab();
    //
    //    $.ShortcodeSetting.setTinyMCE('.jsn_tiny_mce');
    //
    //    $.ShortcodeSetting.updateShortcodeParams();
    //
    //    $.ShortcodeSetting.inputValidator();
    //
    //    $('#modalOptions').delegate('[id^="param"]', 'change', function () {
    //        if ($(this).attr('data-role') == 'no_preview') {
    //            return false;
    //        }
    //        $.ShortcodeSetting.updateShortcodeParams();
    //    });
    //    // Open subshortcode modal setting
    //    $("#form-container").delegate(".jsn-add-more", "click", function (e) {
    //        e.preventDefault();
    //        var shortcodeName = $(this).closest('.has_submodal').attr('data-value');
    //        // Set the container for to-be-added element in to global variable.
    //        addedElementContainer = $(this).closest('.item-container').find('#group_elements');
    //        var shortcode = $(this).attr('data-shortcode-item');
    //        var modalTitle = $(this).attr('data-modal-title');
    //        $.HandleElement._showSettingModal(shortcode, true, false, modalTitle, $(this));
    //    });
    //
    //    // Open edit setting modal
    //    $("#form-container").delegate(".element-edit", "click", function (e) {
    //        e.preventDefault();
    //        if ($.PbDoing.editElement)
    //            return;
    //        $.PbDoing.editElement = 1;
    //
    //        var shortcodeItem = $(this).closest('.jsn-item').find('[name="shortcode_content[]"]');
    //        var shortcodeName = shortcodeItem.attr('shortcode-name');
    //        var params = shortcodeItem.val();
    //        addedElementContainer = $(this).closest('.jsn-item');
    //        if (typeof( shortcodeName ) != 'undefined') {
    //            var modalTitle = $(this).closest('.jsn-item').attr('data-modal-title');
    //            $.HandleElement.editElement(shortcodeName, params, modalTitle, $(this));
    //
    //        }
    //    });
    //
    //    $.ShortcodeSetting.select2();
    //
    //    $.ShortcodeSetting.buttonGroup();
    //
    //    $.HandleElement.cloneElement();
    //
    //    $.HandleElement.deleteElement();
    //
    //    // Update preview when change param in Modal Box
    //    $('#modalOptions').delegate('[id^="param"]', 'change', function () {
    //        if ($(this).attr('data-role') == 'no_preview') {
    //            return false;
    //        }
    //        $.ShortcodeSetting.shortcodePreview();
    //    });
    //    // Disable preview if using wysiwyg editor
    //    if ($('.wysiwyg').length > 0) {
    //
    //        $('.wysiwyg').on('change', function () {
    //            return false;
    //        });
    //
    //        $(document).on('click', function () {
    //            $.ShortcodeSetting.shortcodePreview();
    //        });
    //    }
    //
    //    if (typeof $('#param-el_table').val() !== 'undefined') {
    //        setTimeout(function () {
    //            $.ShortcodeSetting.shortcodePreview();
    //        }, 500);
    //    }
    //    $.ShortcodeSetting.gradientPicker();
    //    // Load at first time
    //    $('#pb_previewing').val('1');
    //    $.ShortcodeSetting.shortcodePreview();
    //    $(window).resize(function () {
    //        $.ShortcodeSetting.resetSize();
    //    });
    //});

    // Function to refresh the actions for elements in setting modal
    $.ShortcodeSetting.refresh = function (container, isSubmodal) {
        $.HandleElement.initEditElement(container);
        $.HandleElement.initCloneElement(container);
        $.HandleElement.initDeleteElement(container);

        $.ShortcodeSetting.shortcodePreview(container);
        if(isSubmodal){
            $.ShortcodeSetting.updateShortcodeParams(container);
        }
        $(container).click(function (e) {
            var eventTarget = e.target;
            if ($(container).find('textarea').hasClass('tinymce'))
            {
                if ($(eventTarget).parent().hasClass('tab-pane') || $(eventTarget).parent().hasClass('jsn-tabs'))
                {
                    $.ShortcodeSetting.shortcodePreview(container);
                }
            }
            else if ($(container).find('textarea').hasClass('jce'))
            {
                if ($(eventTarget).parent().hasClass('tab-pane') || $(eventTarget).parent().hasClass('jsn-tabs'))
                {
                    $.ShortcodeSetting.shortcodePreview(container);
                }
            }
        });
        // Update preview when change param in Modal Box
        $(container).delegate('[id^="param"]', 'change', function () {
            $.ShortcodeSetting.updateShortcodeParams(container);
            // Return if the param doesn't trigger preview
            if ($(this).attr('data-role') == 'no_preview') {
                return false;
            }
            // Return if the preview iframe does not exist
            if (!$('#framePreview', container).length) return false;
            $.ShortcodeSetting.shortcodePreview(container);
        });
    }

    //$(document).ready(function (){
    //    $.ShortcodeSetting.init();
    //});

})(JoomlaShine.jQuery);

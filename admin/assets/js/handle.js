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

var addedElementContainer;
var isAddNewElement;
(function($) {
    $.HandleElement = $.HandleElement || {};
    $.PbDoing = $.PbDoing || {};

    $.options = {
        replaces : '',
        min_column_span : 2,
        layout_span : 12,
        new_sub_element : false,
        add_item : 0,
        curr_iframe_ : null,
        clicked_column : null,
        if_childmodal : 0,
        modal_settings : {
            modalId: 'jsn_view_modal'
        },
        effect: 'easeOutCubic'
    }

    $.HandleElement.initAddElement = function (container){
        if (typeof(container) == 'undefined') {
            container = $("#form-container");
        }
        // Set column where Add element button is clicked
        container.on('jsnpb-add-more-element-click', function (event, obj){
            addedElementContainer	= $(obj).closest('.jsn-column').find('.jsn-element-container');
        });

        $('.shortcode-item').on('click', function (e){
            e.preventDefault();
            $("#pb-add-element").dialog( "close" );
            if($.PbDoing.addElement)
                return;
            $.PbDoing.addElement = 1;
            var shortcodeName	= $(this).closest('li.jsn-item').attr('data-value');
            // remove spaces between
            shortcodeName = shortcodeName.replace(' ', '');
            isAddNewElement = true;

            // Prepare modal parameters
            var modalParams = {};
            modalParams.modalTitle = $(this).closest('.jsn-item').attr('data-modal-title');
            modalParams.source = $(this);
            modalParams.sourceItemsContainer = addedElementContainer;
            $.HandleElement._showSettingModal(shortcodeName, null, false, false, modalParams, '');
        });

    };

    /**
     * Method to init event to Edit Element button
     */
    $.HandleElement.initEditElement	= function (container){
        if (typeof(container) == 'undefined') {
            container = $("#form-container");
        }
        /*
         * Asign action into delete button of elements
         */
        $(".element-edit", container).unbind('click').bind("click",function(e, restart_edit){
            //$.HandleElement.showLoading();
            // Get parameters of edited element.
            var shortcodeContenObj	= $(this).closest('.jsn-item').find('[name="shortcode_content[]"]');
            var params	= shortcodeContenObj.val();
            var shortcodeName	= shortcodeContenObj.attr('shortcode-name');
            var modalTitle = '';
            if ( $(this).closest('.jsn-item').attr('data-name') ) {
                modalTitle = $(this).closest('.jsn-item').attr('data-name');
            }
            if ( typeof( shortcodeName ) == 'undefined' && $(this).attr( 'data-shortcode' ) != '') {
                shortcodeName = $(this).attr( 'data-shortcode' );
                params = $(this).closest('.jsn-row-container').find('[name="shortcode_content[]"]').first().text();
            }
            var modalParams = {};
            modalParams.modalTitle = $(this).closest('.jsn-item').attr('data-modal-title');
            modalParams.source = $(this);
            modalParams.sourceItemsContainer = container; // Container of editted element
            $.HandleElement.editElement(shortcodeName, params, modalParams);
        });
        /*
         * Add action edit element directly on layout page without click edit element icon.
         */
        $('.item-container-content .jsn-element', container).unbind('click').on('click',  function (e, restart_edit) {
            e.stopPropagation();

            // Prevent trigger edit element when click jsn-iconbar collections
            if ( $(e.target).closest('.jsn-iconbar').length || $(e.target).hasClass('element-drag') ) {
                return false;
            }
            $(this).find('.jsn-iconbar .element-edit').trigger('click');
        });
    }

    $.HandleElement.enableProcessing = function(){
        window.parent.jQuery.noConflict()('body').addClass('jsn_processing');
    }

    $.HandleElement.disableProcessing = function(){
        window.parent.jQuery.noConflict()('body').removeClass('jsn_processing');
    }
    /**
     * Method to process params before opening setting popup
     */
    $.HandleElement.editElement = function (shortcodeName, params, modalParams, isEdit) {
        params = JSON.stringify( params );
        $.post(
            JSNPbParams.rootUrl + 'administrator/index.php?option=com_pagebuilder&task=shortcode.savesession&tmpl=component&shortcode=' + shortcodeName,
            {
                params: params,
                shortcode: shortcodeName
            },
            function (data) {
                if ( shortcodeName.search( '_item' ) > 0 ) {
                    $.HandleElement._showSettingModal(shortcodeName, params, true, isEdit, modalParams, '');
                } else {
                    $.HandleElement._showSettingModal(shortcodeName, params, false, isEdit, modalParams, '');
                }
            }
        );
    }


    /**
     * Open setting Modal
     * This modal is used for subelements also
     */
    $.HandleElement._showSettingModal	= function (shortcodeName, params , isSubmodal, isEdit, modalParams, extraAction){
        var modalTitle = modalParams.modalTitle, // Modal title
            _this      = modalParams.source, // The button that trigger modal open
            sourceItemsContainer = modalParams.sourceItemsContainer; // The item list container that this element will be appent/updated to
        if ( typeof( shortcodeName ) == 'undefined' )
            return;
        // count element items.
        var count = 0,
            editted_flag_str = '#_EDITTED';
        var parent_item, shortcode = _this.attr("data-shortcode"), el_title = '';

        if ( isEdit === false ) {
            $('.jsn-item textarea[shortcode-name="' + shortcodeName + '"]').each(function () {
                count++;
            });
        }

        // Get wrapper div & Type of current shortcode
        if (_this.hasClass('row')) {
            parent_item = _this.parent('.jsn-iconbar').parent('.jsn-row-container');
            el_type = 'element';
        }
        else {
            parent_item = _this.parent('.jsn-iconbar').parent('.jsn-item');
            el_type = parent_item.attr('data-el-type');
        }
        if (typeof(parent_item) != 'undefined' && parent_item.length > 0) {
            parent_item.addClass('active-shortcode');
            // If isEdit was not altered, set the default value
            isEdit = (typeof(isEdit) == 'undefined') ? true : isEdit;
        }

        
        if (extraAction == 'convert')
        {
        	var params = params;
        }
        else if (_this.hasClass('row')) {
        	var params = params;
		}
        else
        {	
        	// Get shortcode content
        	var params = parent_item.find("[data-sc-info^='shortcode_content']").first().text();
        }
        
        isEdit = (typeof(isEdit) == 'undefined') ? ( $('.jsn-item.active-shortcode', parentContainer).length ? true : false ) : isEdit;

        // Get custom info for the Modal : frameId, frame_url
        var title = $.HandleElement.getModalTitle(shortcodeName, parent_item.attr('data-modal-title'));
        var has_submodal = 0;
        if( $(this).parents('.has_submodal').length > 0 ){
            has_submodal = 1;
            el_title = $.HandleElement.elTitle(shortcode, clk_title_el, 1);
        }

        if (!isSubmodal) {
            //$.HandleElement.showLoading();
        }
        var modalW, modalH;
        //modalW = (parent.document.body.clientWidth > 800) ? 800 : parent.document.body.clientWidth*0.9;
        //modalW =  parent.document.body.clientWidth*0.9;
        //modalH = parent.document.body.clientHeight*0.75;

		modalW = $(window).width()*0.9;
		modalH = $(window).height()*0.8;
		
        if (typeof (JSNPBElementLang[shortcodeName.replace('pb_', '') + "_title"]) != 'undefined')
        {
	 		modalTitle =  JSNPBElementLang[shortcodeName.replace('pb_', '') + "_title"];
        }
		else
		{
	        if ( ! modalTitle && shortcodeName != '' ) {
	            modalTitle = shortcodeName.replace('pb_', '');
	            modalTitle = modalTitle.slice(0,1).toUpperCase() + modalTitle.slice(1);
	            modalTitle = modalTitle.replace( '_item', ' Item' );
	        }
		}
        var iframe_required = false;
        var frameId = $.options.modal_settings.modalId;
        var frame_url = JSNPbParams.rootUrl + 'administrator/index.php?option=com_pagebuilder&task=shortcode.settings&shortcode=' + shortcodeName;

        // Append temporary form to submit
        var form = $("<form/>").attr({
            method: "post",
            style: "display:none",
            action: frame_url
        });
        form.append($("<input/>").attr( {name : "shortcode", value : shortcode} ) );
        form.append($("<textarea/>").attr( {name : "params", value : params} ) );
        form.append($("<input/>").attr( {name : "el_type", value : el_type} ) );
        form.append($("<input/>").attr( {name : "el_title", value : el_title} ) );
        form.append($("<input/>").attr( {name : "submodal", value : has_submodal} ) );

        // Open add element Modal
        var modal = new $.JSNModal({
            iframe: iframe_required,
            frameId: frameId,
            dialogClass: 'jsn-dialog jsn-bootstrap3',
            //jParent : window.parent.jQuery.noConflict(),
            title: modalTitle + ' Settings',
            url: frame_url,
            buttons: [{
                'text'	: 'Save',
                'id'	: 'selected',
                'class' : 'ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only',
                'click'	: function () {

                    $('body').trigger('before_save_modal', [_this, isSubmodal]);
                    /*
                     * Setting form is loaded by ajax
                     */
                    $.HandleElement.enablePageScroll();
                    $.ShortcodeSetting.updateShortcodeParams(modal.container);
                    $(this).attr('disabled', 'disabled');
                    $('body').trigger('add_exclude_jsn_item_class');

                    // Reset flag for editted element
                    var cur_shortcode   = $(".active-shortcode", modalParams.sourceItemsContainer).last().find('textarea.shortcode-content:first');

                    if (cur_shortcode.length > 0) {
                        cur_shortcode.html(cur_shortcode.html().replace(new RegExp(editted_flag_str, 'g'), ''));
                    }

                    $.HandleElement.updateElement(shortcodeName, isEdit, modal.container, sourceItemsContainer, _this);
                    // Close the modal
                    $.HandleElement.finalize(modal);

                    // Scroll back to previous position
                    modal.scrollBack();

                }
            }, {
                'text'	: 'Cancel',
                'id'	: 'close',
                'class' : 'ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only',
                'click'	: function () {

                    $.HandleElement.enablePageScroll();

                    $('body').trigger('add_exclude_jsn_item_class');

                    // Get current active shortcode
                    if($.PbDoing.addElement) {
                        var active_item = $("#form-container .active-shortcode").last();
                        active_item.remove();
                    }

                    $.HandleElement.finalize(modal);

                    $('body').trigger('on_update_shortcode_widget', 'is_cancel');
                    $.ShortcodeSetting.refresh(sourceItemsContainer, false);
                    // Scroll back to previous position
                    modal.scrollBack();
                }
            }],
            fadeIn:200,
            scrollable: true,
            width: modalW,
            height: modalH
        });

        modal.show(function(modal) {
            // Request server for necessary data
            $.ajax({
                url: frame_url,
                data: 'params=' + encodeURIComponent(params),
                type: 'POST',
                dataType: 'html',
                complete: function(data, status) {
                    if (status == 'success') {
                       // if ( $('#' + $.options.modal_settings.modalId).length == 0 ) {
                        	
                           if (isSubmodal)
                        	{
                        	   modal.container.attr('id', $.options.modal_settings.modalId + '_' + shortcodeName);
                        	} 
                           else
                        	{
                        	   modal.container.attr('id', $.options.modal_settings.modalId);
                        	} 
                        	
                        //}
                        modal.container.html(data.responseText).dialog('open').dialog('moveToTop');

                        // Track shortcode content change
                        $.HandleElement.__changed = false;

                        setTimeout(function() {
                            modal.container.find('#shortcode_content').change(function() {
                                $.HandleElement.__changed = true;
                            });
                        }, 2000);

                        // Make page unscrollable
                        $.HandleElement.disablePageScroll();

                        // Setting trigger preview for elements
                        //$.HandleElement.bindShortcodePreviewElements();
                        $.ShortcodeSetting.init(modal.container, modalParams);

                        $('body').trigger('pb_modal_loaded',[modal]);
                        var role_title = '';
                        if (typeof( isEdit ) !== undefined && isEdit === false) {
                            role_title = $(modal.container).contents().find('#modalOptions input[data-role="title"]').val();
                            if (role_title) {
                                role_title = role_title.replace(/PB_INDEX_TRICK/g, count + 1);
                            }
                        } else {
                            role_title = $(modal.container).contents().find('#modalOptions input[data-role="title"]').val();
                            if (role_title) {
                                role_title = role_title.replace(/PB_INDEX_TRICK/g, 1);
                            }
                        }
                        $(modal.container).contents().find('#modalOptions input[data-role="title"]').alphanumeric({allow:" "});
                        $(modal.container).contents().find('#modalOptions input[data-role="title"]').attr('value', role_title);
                        $(modal.container).contents().find('#modalOptions input[data-role="title"]').val(role_title);
                        if ( $('.jsn-modal').last().attr('id') != $.options.modal_settings.modalId ) {
                            $('body').trigger('pb_submodal_load',[modal.container]);
                        }
                    }
                }
            });

        });
    }

    // finalize when click Save/Cancel modal
    $.HandleElement.finalize = function(modal){
        // reset adding item state
        $('.loading-pointer').removeClass('loading-pointer');

        // reset/update status
        $.options.if_childmodal = 0;
        $.PbDoing.addElement = 0;
        $.PbDoing.editElement = 0;
        $.options.current_shortcode = 0;

        // remove overlay & loading
        //$.HandleElement.removeModal(modal);
        //$.HandleElement.hideLoading();
        modal.close();
        // Do action : convert
        var action_data = ($.PbDoing.action_data !== null) ? $.PbDoing.action_data : null;

        if (action_data) {

            if (action_data.action === 'convert')
            {
                $.HandleElement.convertTo(action_data);
            }

            // Reset value of data
            $.PbDoing.action_data = null;
        }

        $("body").css({ overflow: 'auto' });
        $('body').trigger('on_update_attr_label_common');
        $('body').trigger('on_update_attr_label_setting');
    }

    /**
     * Remove Modal, Show Loading, Hide Loading
     */
    $.HandleElement.removeModal = function(modal) {

        if (typeof(modal) == undefined) {
            $('.jsn-modal').remove();
            $('.modal-backdrop').remove();
        }else{
            modal.container.remove();
            $('.modal-backdrop').remove();
        }
        $.HandleElement.enablePageScroll();
    },

    /**
     * update shortcode-content & close Modal & call preview (shortcode
     * has sub-shortcode) action_data: null (Save button) OR { 'convert' :
	 * 'tab_to_accordion'}
     */
        $.HandleElement.updateElement = function( shortcode, isEdit, current_container, parentContainer, elementAction ) {
            var isSubmodal = false;
            if ( $('.jsn-modal').last().attr('id') != $.options.modal_settings.modalId ) {
                isSubmodal = true;
            }

            // Get sub-shorcodes content
            var sub_items_content = [];
            if (typeof(current_container) == 'undefined') {
                current_container     = $('#settings-form-container');
            }

            if (typeof(parentContainer) == 'undefined') {
                parentContainer  = $('#form-container');
            }

            $("[name^='shortcode_content']", current_container).each(function() {
                sub_items_content.push($(this).text());
            });

            sub_items_content = sub_items_content.join('');

            var shortcode_content_obj = $( '#shortcode_content', current_container );
            var	shortcode_content = shortcode_content_obj.text(),
                arr = shortcode_content.split('][');

            if (arr.length >= 2) {
                // Extract name & parameters of parent shortcode
                var parent_sc_start = shortcode_content.replace('#_EDITTED', '').match(/\[[^\s"]+\s+([A-Za-z0-9_-]+=\"[^"]*\"\s*)*\s*\]/);
                var head_shortcode = parent_sc_start[0];
                head_shortcode = head_shortcode.replace(']', '');

                var data = head_shortcode + ']' + sub_items_content + '[' + arr[arr.length - 1];

                // Update shortcode content
                shortcode_content_obj.text(data);
            }

            /*
             * BEGIN Proceed item title to update to Edited element's label
             */
            // update content to current active sub-element in group elements (Accordions, Tabs...)
            var if_childmodal = $('.jsn-item-content', parentContainer).length ? true : false;
            var item_class =  if_childmodal ? ".jsn-item-content" : ".pb-plg-element";
            // if sub modal, use item_title as title. If in pagebuilder, show like this (Element Type : item_title)
            var item_title = current_container.find('[data-role="title"]').first().val();
            if (shortcode == 'pb_table_item') {
                item_title = sub_items_content;
                var regStart = new RegExp("\\[pb_table_item( \\w+=\"(\\w+|%)\")+\\s+\\]");
                var regEnd = new RegExp("\\[\\/pb_table_item\\]");
                item_title = item_title.replace(regStart, "");
                item_title = item_title.replace(regEnd, "");
            }
            /*
             * END Proceed item title to update to Edited element's label
             */
            if(item_title){
                if(item_title == ''){
                    item_title = "(Untitled)";
                }
            }
            
            if ( isEdit ) {
                // Update the editted shortcode
                var	active_shortcode = $(".active-shortcode", parentContainer).last(),
                    editted_flag_str = '#_EDITTED';

                // Proceed shortcode content if it is a row
                if (active_shortcode.hasClass('jsn-row-container')) {
                    shortcode_content = shortcode_content.replace('[/pb_row]','');
                }


                $('body').trigger('jsnpb_before_changed', [parentContainer, isSubmodal]);

                if (active_shortcode.hasClass('jsn-row-container') && shortcode == 'pb_row' && typeof (elementAction) != 'undefined')
                {
                	
                }
                else
                {	
                	active_shortcode.find("[data-sc-info^='shortcode_content']").first().text(shortcode_content);
                	active_shortcode.find("[data-sc-info^='shortcode_content']").first().val(shortcode_content);
                }
                
                if(item_class == '.jsn-item-content'){
                    active_shortcode.find(item_class).first().html(item_title);
                }else{
                    active_shortcode.find(item_class).first().children('span').html(item_title);
                }
                var element_html = active_shortcode.html();

                // If the the trigger action is convert then rebuild the shortcode HTML
                if ((typeof(this.extraAction) != 'undefined' && this.extraAction == 'convert') || isSubmodal) {
                    //  item_title = active_shortcode.find(item_class).first().html().split(':')[0];
                    $.post(
                        JSNPbParams.rootUrl + 'administrator/index.php?option=com_pagebuilder&task=shortcode.generateHolder',
                        {
                            'params': encodeURIComponent(shortcode_content),
                            'shortcode': shortcode,
                            'el_title': item_title
                        },
                        function (data){
                            element_html = data;
                            if (shortcode != 'pb_table_item') {
                                active_shortcode.replaceWith(element_html);
                            }
                            active_shortcode.removeClass('active-shortcode');
                            $('body').trigger('jsnpb_changed', [parentContainer, isSubmodal]);
                        }
                    );
                }
                else if (shortcode == 'pb_row' && typeof (elementAction) != 'undefined') {
                    $.post(
                        JSNPbParams.rootUrl + 'administrator/index.php?option=com_pagebuilder&task=shortcode.generateHolder',
                        {
                            'params': encodeURIComponent(shortcode_content),
                            'shortcode': shortcode,
                            'el_title': item_title
                        },
                        function (data) {
                            elementAction.closest('.jsn-row-container').find('[name="shortcode_content[]"]').first().text(shortcode_content);
                            $('body').trigger('jsnpb_changed', [parentContainer, isSubmodal]);
                        }
                    );
                }
                else {
                    // ...Else get HTML structure from editted element
                    if (typeof(element_html) != 'undefined') {
                        // Remove editted flag
                        element_html = element_html.replace(new RegExp(editted_flag_str, 'g'), '');
                    }
                    active_shortcode.html(element_html);
                    active_shortcode.removeClass('active-shortcode');
                    $('body').trigger('jsnpb_changed', [parentContainer, isSubmodal]);

                }
            }else{
                /*
                 * If it's adding new element, then rebuild the displaying HTML structure
                 */
                // Build the item title
                $.post(
                    JSNPbParams.rootUrl + 'administrator/index.php?option=com_pagebuilder&task=shortcode.generateHolder',
                    {
                        'params': encodeURIComponent(shortcode_content),
                        'shortcode': shortcode,
                        'el_title': item_title
                    },
                    function (data){
                        if ( shortcode == 'pb_row' && typeof( _this ) != 'undefined' ) {
                            shortcode_content = shortcode_content.replace('[/pb_row]', '');
                            _this.closest('.jsn-row-container').find('[name="shortcode_content[]"]').first().text(shortcode_content);
                        }
                        $('body').trigger('jsnpb_before_changed', [parentContainer, isSubmodal]);

                        // Append the HTML to container
                        $(parentContainer).append(data);

                        $('body').trigger('jsnpb_changed', [parentContainer, isSubmodal]);
                        $('#pb_previewing').val('1');
                    }
                );
            }
        }


    $.HandleElement.hideLoading = function() {
        var lastModal = $('.jsn-dialog', $('body')).last();
        // Remove loading overlay if there is only 1 modal
        if (lastModal.length <= 0) {
            $('.jsn-modal-overlay').remove();
            $('.jsn-modal-indicator').remove();
        }else{
            // If not just change the z-index value
            var lastModalZindez = lastModal.css('z-index');
            $('.jsn-modal-indicator').css('z-index', lastModalZindez - 1);
            $('.jsn-modal-overlay').css('z-index', $('.jsn-modal-indicator').css('z-index') - 1);

        }

    }

    /**
     * delete an element (a row OR a column OR an shortcode item)
     */
    $.HandleElement.initDeleteElement = function(container) {
        if (typeof(container) == 'undefined') {
            container = $("#form-container");
        }
        $('.element-delete', container) .unbind('click').bind('click', function(){
            var msg,is_column;
            if($(this).hasClass('row') || $(this).attr("data-target") == "row_table"){
                msg = "Are you sure you want to remove row?";
            }else if($(this).hasClass('column') || $(this).attr("data-target") == "column_table"){
                msg = "Are you sure you want to remove column?";
                is_column = 1;
            }else{
                msg = "Are you sure you want to remove element?";
            }

            var confirm_ = confirm(msg);
            if(confirm_){
                var $column = $(this).parent('.jsn-iconbar').parent('.shortcode-container');
                if(is_column == 1)
                {
                    // Delete a Column in Table element
                    if($(this).attr("data-target") == "column_table")
                    {
                        var table = new $.PBTable();
                        table.deleteColRow($(this), 'column');
                        $.ShortcodeSetting.shortcodePreview(container);
                    } else {
                        var $row = $column.parent('.row-content').parent('.row-region');
                        // if is last column of row, remove parent row
                        if($column.parent('.row-content').find('.column-region').length == 1){
                            $.HandleElement.removeElement($row, container);
                        }else{
                            $.HandleElement.removeElement($column, container);
                        }
                    }
                }
                else {
                    // Delete a Row in Table element
                    if($(this).attr("data-target") == "row_table"){
                        table = new $.PBTable();
                        table.deleteColRow($(this), 'row');
                        $.ShortcodeSetting.shortcodePreview(container);
                    } else {
                        $.HandleElement.removeElement($column, container);
                    }
                }
                $.ShortcodeSetting.shortcodePreview(container);
            }
        });
    };

    // Clone an Element
    $.HandleElement.initCloneElement = function(container) {
        if (typeof(container) == 'undefined') {
            container = $("#form-container");
        }
        $('.element-clone', container).unbind('click').bind('click', function() {
            if ( $.PbDoing.cloneElement )
                return;
            $.PbDoing.cloneElement = 1;

            var parent_item = $(this).parent('.jsn-iconbar').parent('.jsn-item');
            var height_ = parent_item.height();
            var clone_item = parent_item.clone(true);
            var item_class = $('#modalOptions').length ? '.jsn-item-content' : '.pb-plg-element';
            // Update title for clone element
            var html = clone_item.html();
            if ( item_class == '.jsn-item-content' )
                append_title_el = parent_item.find(item_class).html();
            else {
                append_title_el = parent_item.find(item_class).find('span').html();
                if (typeof( append_title_el ) == 'undefined' ) {
                    append_title_el = parent_item.find(item_class).html();
                }
            }
            var count = 0;
            var shortcodes = $(this).closest('.jsn-item').attr('data-name').toLowerCase();
            var shortcodeName = parent_item.find("[name^='shortcode_content']").attr('shortcode-name');
            $('.jsn-item textarea[shortcode-name="' + shortcodeName + '"]').each(function () {
                count++;
            });
            var regexp = new RegExp( append_title_el, "g" );
            if(item_class == '.jsn-item-content'){
                html = html.replace( regexp, append_title_el + ' ' + (count + 1));
            }else{
                if(append_title_el == shortcodes){
                    html = html.replace( /(el_title=")([^\"]+)(")/, '$1' + append_title_el + ' ' + (count + 1) + '$3');
                }else{
                    html = html.replace( regexp, append_title_el + ' ' + (count + 1));
                }

            }
            clone_item.html(html);
            var textarea_content = clone_item.find("[name^='shortcode_content']").text();
            if(shortcodeName == 'pb_pricingtableattr_item') {
                textarea_content = textarea_content.replace(/(prtbl_item_attr_id=")([^\"]+)(")/, '$1' + $.HandleElement.randomString(8) + '$3');
            }

            clone_item.find("[name^='shortcode_content']").text(textarea_content);
            // Add animation before insert
            $.HandleElement.appendElementAnimate( container, clone_item, height_, function() {
                clone_item.insertAfter( parent_item );
                if(container.hasClass('fullmode')){
                    // active iframe preview for cloned element
                    $(clone_item[0]).find('form.shortcode-preview-form').remove();
                    $(clone_item[0]).find('iframe').remove();
                }
            }, function() {
                $('body').trigger('jsnpb_changed', [container]);
                $.PbDoing.cloneElement = 0;
            } );
            $.HandleElement.refresh(container);
            $('#pb_previewing').val('1');
            $.ShortcodeSetting.shortcodePreview(container);
        } );
    }

    $.HandleElement.randomString = function(length) {
        var result 	= '';
        var chars	= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
        for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
        return result;
    }

    /**
     * Remove an element in Pagebuilder / In Modal
     */
    $.HandleElement.removeElement = function(element, container) {
        var isSubmodal = false;
        element.css({
            'min-height' : 0,
            'overflow' : 'hidden'
        });
        element.animate({
            opacity:0
        },300, 'easeOutCubic',function(){
            element.animate({
                height:0,
                'padding-top' : 0,
                'padding-bottom' : 0
            },300, 'easeOutCubic',function(){
                element.remove();
                // for shortcode which has sub-shortcode
                if ($("#modalOptions").find('.has_submodal').length > 0){
                    isSubmodal = true;
                }

                $('body').trigger('on_after_delete_element');
                $('body').trigger('jsnpb_changed', [container, isSubmodal]);

            });
        });
    }

    /**
     * For Parent Shortcode: Rescan sub-shortcodes content, call preview
     * function to regenerate preview
     */
    $.HandleElement.rescanShortcode = function(curr_iframe, callback) {
        try {
            $.ShortcodeSetting.shortcodePreview(curr_iframe, callback);
        } catch (err) {
            // Do nothing
        }
    }

    // Animation when add new element to container
    $.HandleElement.appendElementAnimate = function(container, new_el, height_, callback, finished){

        var obj_return = {
            obj_element:new_el
        };
        $('body').trigger('on_clone_element_item', [obj_return]);
        new_el = obj_return.obj_element;
        new_el.css({
            'opacity' : 0
        });
        new_el.addClass('padTB0');
        if(callback)callback();
        new_el.show();
        new_el.animate({
            height: height_
        },500,'easeOutCubic', function(){
            $(this).animate({
                opacity:1
            },300,'easeOutCubic',function(){
                new_el.removeClass('padTB0');
                new_el.css('height', 'auto');
                $('body').trigger('on_update_attr_label_common');
                $('body').trigger('on_update_attr_label_setting');
                $('body').trigger('jsnpb_changed', [container]);
                if(finished)finished();
            });
        });
    }

    $.HandleElement.sliceContent = function(text) {
        text = unescape(text);
        text = text.replace(/\+/g, ' ');

        var arr = text.split(' ');
        arr = arr.slice(0, 10);
        return arr.join(' ');
    }

    // Remove select2 active
    $.HandleElement.removeSelect2Active = function () {
        $('.select2-drop-active').remove();
    }

    // Disable page scroll
    $.HandleElement.disablePageScroll = function() {
        if ( $('body').hasClass('wp-admin') ) {
            $('body').addClass('wr-overflow-hidden');
        }
    }

    // Enable page scroll
    $.HandleElement.enablePageScroll = function() {
        if ( $('body').hasClass('wp-admin') ) {
            $('body').removeClass('wr-overflow-hidden');
        }
    }

    /**
     * Generate Title for Modal
     */
    $.HandleElement.getModalTitle = function(shortcode, modal_title) {
        var title;
        if (shortcode != '') {
            if(modal_title)
                title = modal_title;
            else{
                shortcode = shortcode.replace('pb_','').replace('_',' ');
                title = $.HandleElement.capitalize(shortcode);
            }
        }
        return title + ' Settings';
    }

    // Capitalize first character of whole string
    $.HandleElement.capitalize = function(text) {
        return text.charAt(0).toUpperCase()
            + text.slice(1).toLowerCase();
    },

        // Capitalize first character of each word
        $.HandleElement.ucwords = function(text) {
            return (text + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
                return $1.toUpperCase();
            });
        },

        // Function to refresh the actions for elements
        $.HandleElement.refresh = function(container) {
            $.HandleElement.initEditElement(container);
            $.HandleElement.initCloneElement(container);
            $.HandleElement.initDeleteElement(container);
        },

    /**
     * Traverse parameters, get theirs values
     */
        $.HandleElement.traverseParam = function( $selector, child_element ){
            var sc_content = '';
            var params_arr = {};

            $selector.each( function ()
            {

                if ( ! $(this).hasClass( 'pb_hidden_depend' ) )
                {

                    $(this).find( '[id^="param-"]' ).each(function()
                    {
                        // Bypass the Copy style group
                        if ( $(this).attr('id') == 'param-copy_style_from' ) {
                            return;
                        }

                        if(
                            $(this).parents(".tmce-active").length == 0 && ! $(this).hasClass('tmce-active')
                            && $(this).parents(".html-active").length == 0 && ! $(this).hasClass('html-active')
                            && ! $(this).parents("[id^='parent-param']").hasClass( 'pb_hidden_depend' )
                            && ( child_element || ! $(this).closest('.form-group').parent().hasClass('sub-element-settings'))
                            && $(this).attr('id').indexOf('parent-') == -1
                        )
                        {
                            var id = $(this).attr('id');
                            if($(this)){
                                sc_content =  $(this).val();//.replace(/\[/g,"&#91;").replace(/\]/g,"&#93;");
                            }else{
                                if(($(this).is(":radio") || $(this).is(":checkbox")) && !$(this).is(":checked"));
                                else{
                                    if(!params_arr[id.replace('param-','')] || id.replace('param-', '') == 'title_font_face_type' || id.replace('param-', '') == 'title_font_face_value' || id.replace('param-','') == 'font_face_type' || id.replace('param-','') == 'font_face_value' || id.replace('param-', '') == 'image_type_post' || id.replace('param-', '') == 'image_type_page' || id.replace('param-', '') == 'image_type_category' ) {
                                        params_arr[id.replace('param-','')] = $(this).val();
                                    } else {
                                        params_arr[id.replace('param-','')] += '__#__' + $(this).val();
                                    }
                                }
                            }
                        }

                    });
                }
            });

            return { sc_content : sc_content, params_arr : params_arr };
        }

    /**
     * Generate shortcode content
     */
    $.HandleElement.generateShortcodeContent = function(shortcode_name, params_arr, sc_content){
        var tmp_content = [];

        tmp_content.push('['+ shortcode_name);
        // wrap key, value of params to this format: key = "value"
        $.each(params_arr, function(key, value){
            if ( value ) {
                if ( value instanceof Array ) {
                    value = value.toString();
                }
                tmp_content.push(key + '="' + value.replace(/\"/g,"&quot;").replace(/\[/g,"").replace(/\]/g,"") + '"');

            }
        });
        // step_to_track(6,tmp_content);
        tmp_content.push(']' + sc_content + '[/' + shortcode_name + ']');
        tmp_content	= tmp_content.join( ' ' );

        return tmp_content;
    }

    $.HandleElement.customCss = function () {
        //show modal
        setTimeout(function () {
            var modalw = $(window.parent).width() * 0.9;
            var modalh = $(window.parent).height() * 0.9;
            var framId = 'custom-css-modal';
            var modal;
            var content_id = $('#top-btn-actions').find('[name="pb_content_id"]').val();

            var frame_url = JSNPbParams.rootUrl + 'administrator/index.php?option=com_pagebuilder&view=builder&tmpl=component&pb_custom_css=1&id=' + content_id;
            $('button.page-custom-css').on('click', function (e) {

                if ($(this).find('.btn').hasClass('disabled')) {
                    return;
                }

                modal = new $.JSNModal({
                    frameId: framId,
                    jParent: window.parent.jQuery.noConflict(),
                    title: 'Custom Css',
                    url: frame_url,
                    buttons: [{
                        'text': 'Save',
                        'id': 'selected',
                        'class': 'ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only',
                        'click': function () {

                            var jParent = window.parent.jQuery.noConflict();
                            // get css file (link + checked status), save custom css
                            var iframe_content = jParent('#' + framId).contents();
                            var css_files = [];
                            iframe_content.find('#pb-custom-css-box').find('.jsn-items-list').find('li').each(function (i) {
                                var input = $(this).find('input');
                                var checked = input.is(':checked');
                                var url = input.val();
                                var item = {
                                    'checked': checked,
                                    'url': url
                                };
                                css_files.push(item);
                            });
                            var css_files = JSON.stringify({data: css_files});

                            //get custom css code
                            var custom_css = iframe_content.find('#custom-css').val();

                            //save data
                            $.post(
                                JSNPbParams.rootUrl + 'administrator/index.php?option=com_pagebuilder&task=builder.save_css_custom',
                                {
                                    action: 'save_css_custom',
                                    content_id: content_id,
                                    css_files: css_files,
                                    css_custom: custom_css
                                },
                                function (data) {

                                    //close loading
                                    $.HandleElement.hideLoading();
                                });
                            //close modal
                            $.HandleElement.finalize(0);
                            //show loading
                            //$.HandleElement.showLoading();
                        }
                    }, {
                        'text': 'Cancel',
                        'id': 'close',
                        'class': 'ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only',
                        'click': function () {

                            $.HandleElement.hideLoading();
                            $.HandleElement.removeModal();
                            $('body').css({overflow: 'auto'});
                        }
                    }],
                    loaded: function () {

                    },
                    fadeIn: 200,
                    scrollable: true,
                    width: modalw,
                    height: modalh
                });
                modal.show();
            });

        }, 200);

    },

    /**
     * Sortable Sub Item Element
     */
    $.HandleElement.sortableSubItem = function (container) {
        $('#group_elements.jsn-items-list').each(function () {
            $(this).sortable({
                axis: 'y',
                placeholder: "ui-state-highlight",
                handle: '.element-drag',
                tolerance: 'pointer',
                change: function (e, ui) {
                    var width = ui.placeholder.width();
                    ui.helper.width(width);
                    JSNallowChange = true;
                },
                stop: function () {
                    $.ShortcodeSetting.shortcodePreview(container)
                }
            });
        });
        $(".jsn-item-content").disableSelection();
    },

    /**
     *
     */
    $(document).ready(function (){
        /*
         * Reinit all actions after element is saved
         */
        $('body').on('jsnpb_changed', function (event, container, isSubmodal) {
            if (typeof(container) == 'undefined') {
                container = $('#form-container');
            }
            if(isSubmodal) {
                // Reinit actions for only elements in setting modal
                $.ShortcodeSetting.refresh(container.closest('.jsn-modal'), isSubmodal);
            }else{
                // Reinit actions for elements in pagebuilder editor
                $.HandleElement.refresh(container.closest('.jsn-pb-builder-wrapper'), false);
            }
        });

        $('body').unbind('pb_modal_loaded').bind('pb_modal_loaded', function (event, modal){
            $("body").css({ overflow: 'hidden' });
            $(window).resize(function(){
                modalW = document.body.clientWidth*0.9;
                modalH = document.body.clientHeight*0.75;
                modal.setSize(modalW,modalH);
                winW = parent.document.body.clientWidth;
                $('.jsn-master .jsn-elementselector .jsn-items-list').css('overflow', 'auto').css('height', modalH - 220);
                $("#pb-add-element").css('height', modalH - 200);
                $('.ui-dialog').css('width', modalW);
                $('.ui-dialog').css('left', winW / 2 - modalW / 2);
            });
        });
    });
})(JoomlaShine.jQuery);

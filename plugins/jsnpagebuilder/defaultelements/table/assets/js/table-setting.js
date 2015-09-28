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

( function ($)
{
	"use strict";

	$.PBTable	= $.PBTable || {};
    $.PbDoing = $.PbDoing || {};

	$.PBTable = function () {};

	$.PBTable.prototype = {
        init: function(active_shortcode, isSubmodal){
            // get current th/td
        	var parent_wrap = active_shortcode.closest('.jsn-item').parent();
            if (!isSubmodal)
            {	
            	var parent_parent_wrap = $('#jsn_view_modal'); 
            }
            else
            {
            	var parent_parent_wrap = $('#jsn_view_modal_pb_table_item');
            }	
            
            this.updateColWidth(active_shortcode, parent_wrap, parent_parent_wrap, isSubmodal);
            this.spanProcess(parent_wrap, parent_parent_wrap, isSubmodal);
        },
        
        // update column width
        updateColWidth: function (active_shortcode, parent_wrap, parent_parent_wrap, isSubmodal) {
            //var cell_content = window.parent.jQuery.noConflict()( '#jsn_view_modal').contents().find('#pb_share_data').text();
            //active_shortcode.find(".jsn-item-content").first().html($.HandleElement.sliceContent(cell_content));
            // get updated width value
            //var merge_data = window.parent.jQuery.noConflict()( '#jsn_view_modal').contents().find('#pb_merge_data');
        	//var parentIframe = window.parent.jQuery.noConflict()('iframe');
        	var merge_data = parent_parent_wrap.find('#param-width_value').val() + parent_parent_wrap.find('#param-width_type option:selected').val();
            // update width
            parent_wrap.css('width', (merge_data == '%' || merge_data == 'px') ? '' : merge_data);
        },
        // extract colspan, rowspan of cell. then update table structure
        spanProcess: function(parent_wrap, parent_parent_wrap, isSubmodal){
            var self = this;
            var data = {};
            var data_old = {};
            var error = 0;
    		var extract_data = '';
    		
    		parent_parent_wrap.each(function () {
            	if ( $(this).contents().find('#shortcode_name').val() == 'pb_table_item' ) {
            		var data_str = 'param-rowspan:';
            		data_str += parseInt($(this).contents().find('#param-rowspan').val());
            		data_str += '#param-colspan:';
            		data_str += parseInt($(this).contents().find('#param-colspan').val());
            		$(this).contents().find('#pb_extract_data').val(data_str);
            		extract_data = $(this).contents().find('#pb_extract_data').val();
        		}
            });

            // extract data, sample data: param-rowspan:2#param-colspan:2#
            $.each(extract_data.split('#'), function(key, value){
                if(value){
                    value = value.split(':');
                    var attr_value = parseInt(value[1]);
                    if(attr_value <= 0){
                        alert(JSNPbTableParams.pbstrings.ROW_SPAN);
                        error = 1;
                    }
                    var attr_name = value[0].replace('param-', '');
                    data[attr_name] = attr_value;
                }
            });
            if(error)
                return;
            
            $('body').bind('jsnpb_before_changed', function() {
              
	        	// Open edit setting modal
	    		$("#form-container .element-edit").on("click", function (e){
	    			e.preventDefault();
	    			
	    			if($.PbDoing.editElement)
	    	            return;
	    	        $.PbDoing.editElement = 1;
	    			var shortcodeItem	= $(this).closest('.jsn-item').find('[name="shortcode_content[]"]');
	    			var shortcodeName	= shortcodeItem.attr('shortcode-name');
	    			var params			= shortcodeItem.val();
	    			if ( typeof( shortcodeName ) != 'undefined' ) {
	    				var modalTitle = $(this).closest('.jsn-item').attr('data-modal-title');
	    				$.HandleElement.editElement(shortcodeName, params, modalTitle, $(this));
	    			}
	    		});      
	            
            });

            //$('body').bind('jsnpb_changed', function() {
	            // get info
	            var $shortcode = 'pb_table_item';
	            var table = parent_wrap.parents("#table_content");
	            var parent_row = parent_wrap.parent("tr");
	            var self_index = parseInt(parent_wrap.attr('data-cell-index'));
	            var self_content = parent_wrap.find(".jsn-item-content").first();
	            if (isSubmodal == false)
	            {
	            	var self_textarea = parent_parent_wrap.find("textarea").first();
	            }	
	            else
	            {
	            	var self_textarea = parent_wrap.find("textarea").first();
	            }	
	            
	            var row_idx = parent_row.index();
	            var child = (row_idx == 0) ? 'th' : 'td';
	            var col_idx = parent_row.find(child).attr('data-cell-index');
	            var max_col = table.find('tr.pb-row-of-delete').find('td').length;
	            var max_row = table.find('tr').length - 1;
	            
	            // validate if rowspan/colspan exceed the limit
				data['rowspan'] = ((row_idx + data['rowspan']) > max_row) ? max_row - row_idx : data['rowspan'];
	            data['colspan'] = ((col_idx + data['colspan']) > max_col) ? max_col - col_idx : data['colspan'];
	          
	            // update colspan, rowspan
	            $.each(data, function(attr_name, attr_value){
		            // get old attributes value
	                data_old[attr_name] = parseInt(parent_wrap.attr(attr_name));
	                // update attributes
	                parent_wrap.attr(attr_name, attr_value);
	                // update textarea
	                var regexp = new RegExp(attr_name+'="[0-9]"', "g");
	              
	                $(self_textarea).text(self_textarea.val().replace(regexp,attr_name+'="'+attr_value+'"'));
	            })
	            // do nothing
	            if(data['rowspan'] == 1 && data['colspan'] == 1) return;
	
	            
	           
	            // get related rows
	            var related_rows;
	            if(data['rowspan'] > 1){
	                if(data['colspan'] > 1)
	                    related_rows = table.find('tr').slice(row_idx, row_idx + data['rowspan']);
	                else
	                    related_rows = table.find('tr').slice(row_idx + 1, row_idx + data['rowspan']);
	            }else
	                related_rows = parent_row;
	
	            // get content of cells & add Remove flag
	            $(related_rows).each(function(i){
	                var row_idx_ = table.find("tr").index($(this));
	                var child_ = (row_idx_ == 0) ? 'th' : 'td';
	                var related_columns = $(this).find(child_+'[data-cell-index="'+self_index+'"]');
	                if(data['colspan'] > 1){
	                    if(data['rowspan'] > 1 && i > 0){
	                        self.cellProcess(related_columns, self_content);
	                    }
	                    related_columns = $(this).find(child_).filter(function(){return  parseInt($(this).attr("data-cell-index")) > self_index && parseInt($(this).attr("data-cell-index")) < (self_index + data['colspan'])});
	                }
	                $(related_columns).each(function(){
	                    self.cellProcess($(this), self_content);
	                })
	            })
	            // update Textarea
	            $(self_textarea).text(self_textarea.val().replace(/].*\[/,"]"+(self_content.html())+"["));
	            // remove cells have Remove flag
	            table.find('.pb-remove-cell').each(function(){
	                $(this).remove();
	            })
	            // reset #pb_extract_data value
	            //extract_data.text('');
	            self.reindexTable();
	            self.cleanDeleteBtn();
           // });
        },
        // get cell content and add 'pb-remove-cell' class
        cellProcess:function(this_, self_content){
            var cell_content = this_.find(".jsn-item-content").first().html();
            
            if(cell_content != "" && cell_content != null)
                self_content.html(self_content.html()+'<br>'+cell_content);
            this_.addClass('pb-remove-cell');
        },
        // add texteare of tr_start, tr_end to row of table
        appendTextarea : function(row, first_child){
            if(first_child == null)
                first_child = row.find('td').first();
            first_child.before(tr_start);
            row.append(tr_end);
        },
        // clean table: remove rows which only have delete button
        cleanDeleteBtn:function(){
            $("#table_content tr").each(function(row_idx){
                var child = (row_idx==0) ? 'th' : 'td';
                // if empty, remove delete button, not remove whole row
                if($(this).find(child).length == 1)
                    $(this).find('.pb-delete-column-td').empty();
            })
        },
        // reindex cell in table
        reindexTable:function(){
            // reset index
            $("#table_content tr").each(function(row_idx){
                var child = (row_idx==0) ? 'th' : 'td';
                $(this).find(child).each(function(){
                    $(this).removeAttr('data-cell-index');
                })
            })
            $("#table_content tr").each(function(row_idx){
                var child = (row_idx==0) ? 'th' : 'td';
                var row = $(this);
                $(this).find(child).each(function(cell_idx){
                    var colspan = parseInt($(this).attr("colspan"));
                    var rowspan = parseInt($(this).attr("rowspan"));
                    if($(this).attr('data-cell-index') == null){
                        if(cell_idx == 0)
                            $(this).attr('data-cell-index', 0);
                        else{
                            var prev_cell = row.find(child).eq(cell_idx - 1);
                            var prev_cell_colspan = (prev_cell.attr('colspan') != null) ? prev_cell.attr('colspan') : 1;
                            $(this).attr('data-cell-index', parseInt(prev_cell.attr('data-cell-index')) + parseInt(prev_cell_colspan));
                        }
                    }
                    var self_index = $(this).attr('data-cell-index');
                    // update index for related cell
                    if(rowspan > 1){
                        var related_rows = $("#table_content").find('tr').slice(row_idx + 1, row_idx + rowspan);
                        $(related_rows).each(function(){
                            var row_idx_ = $("#table_content").find("tr").index($(this));
                            var child_ = (row_idx_ == 0) ? 'th' : 'td';
                            var related_cell = $(this).find(child_+':eq('+cell_idx+')');
                            related_cell.attr('data-cell-index', parseInt(self_index));
                        })
                    }

                })
            })
            $.PbDoing.addRowCol = 0;
        },

        preDeleteCol:function(idx_delete){
            $("#table_content tr").each(function(row_idx){
                var child = (row_idx==0) ? 'th' : 'td';
                $(this).find(child).each(function(){
                    var colspan = parseInt($(this).attr("colspan"));
                    var cell_idx = parseInt($(this).attr("data-cell-index"));
                    if(colspan > 1){
                        if(cell_idx <= idx_delete && idx_delete <= (cell_idx + colspan -1)){
                            $(this).attr("colspan", colspan - 1);
                            // update Textarea
                            var self_textarea = $(this).find("textarea").first();
                            var regexp = new RegExp('colspan="[0-9]"', "g");
                            $(self_textarea).text(self_textarea.val().replace(regexp,'colspan="'+ (colspan - 1)+'"'));
                            if(cell_idx == idx_delete){
                                $(this).attr('data-cell-index', parseInt($(this).attr('data-cell-index')) + 1);
                            }
                        }
                    }
                })
            })
        },
        preDeleteRow:function(idx_delete){
            $("#table_content tr").each(function(row_idx){
                var child = (row_idx==0) ? 'th' : 'td';
                $(this).find(child).each(function(cell_idx){
                    var rowspan = parseInt($(this).attr("rowspan"));
                    if(rowspan > 1){
                        if(row_idx <= idx_delete && idx_delete <= (row_idx + rowspan -1)){
                            $(this).attr("rowspan", parseInt(rowspan) - 1);
                            // update Textarea content
                            var self_textarea = $(this).find("textarea").first();
                            var regexp = new RegExp('rowspan="[0-9]"', "g");
                            $(self_textarea).text(self_textarea.val().replace(regexp,'rowspan="'+ (parseInt(rowspan) - 1)+'"'));

                            if(row_idx == idx_delete){
                                // insert a cell in same index to next row
                                var below_cell = $("#table_content tr").eq(row_idx + 1).find("td").eq(cell_idx);
                                below_cell.before($("<div />").append($(this).clone()).html());
                            }
                        }
                    }
                })
            })
        },
        // Handle Delete row, column
        deleteColRow:function(item, type){
            var self = this;
            switch (type) {
                    case 'column':
                        var idx_delete = parseInt(item.parents('td').attr('data-cell-index'));
                        if($("#table_content tr").last().find('td').length == 1){
                            alert(JSNPbTableParams.pbstrings.TABLE_COLUMNS);
                            return true;
                        }
                        self.preDeleteCol(idx_delete);
                        $("#table_content tr").each(function(i){
                            var child = (i==0) ? 'th' : 'td';
                            var cell = $(this).find(child+'[data-cell-index="'+idx_delete+'"]');
                            cell.remove();
                        })
                        $("#bottom_row tr").each(function(){
                            var cell = $(this).find('td[data-cell-index="'+idx_delete+'"]');
                            cell.remove();
                        })
                        self.reindexTable();
                        break;
                    case 'row':
                        var row_idx = item.parents('tr').index();
                        if($("#table_content tr").length == 3){
                            alert(JSNPbTableParams.pbstrings.TABLE_ROWS);
                            return true;
                        }
                        self.preDeleteRow(row_idx);
                        $("#table_content tr").eq(row_idx).remove();
                        $("#right_column tr").eq(row_idx).remove();
                        self.reindexTable();
                        break;
                }
            return true;

        },
        // Handle Add row,column
        addRowCol:function(modal){
            var self = this;
            // Handle "Add Row", "Add Column" button
           // $("#modalOptions").delegate(".table_action", "click", function(e){
            $('#modalOptions .table_action').unbind('click').bind('click', function(e) {
                e.preventDefault();

                if($.PbDoing.addRowCol)
                    return;
                $.PbDoing.addRowCol = 1;

                var data_target = $(this).attr('data-target');
                var $shortcode = 'pb_table_item';
                if($("#tmpl-"+$shortcode).length == 0){
            		$('body').append("<script type='text/html' id='tmpl-pb_table_item'>" + JSNPbTableParams.tpml_table_item + "</script>");
                    self.addRowColFinish(data_target, $shortcode, self, modal);
                }
                else{
                    self.addRowColFinish(data_target, $shortcode, self, modal);
                }
            });
        },
        // do action after have tmpl-pb_item_table HTML structure
        addRowColFinish:function(data_target, $shortcode, self, modal){
            if(data_target == "table_row"){
                // Add New Row
            	
                var row = [];
                row.push('<tr>');
                var countColumn = $("#table_content tr").last().find('td').length;

                for(var i = 0 ; i < countColumn; i++){
                	
                    row.push('<td>' + $("#tmpl-"+$shortcode).html() + '</td>');
                }
                // add right delete row button
                row.push($("<div />").append($("#table_content tr").eq(1).find('td').last().clone()).html());

                row.push('</tr>');
                $("#table_content tr").last().before(row.join(""));
                // append Textarea To Added Row
                self.appendTextarea($("#table_content").find("tr").eq($("#table_content").find("tr").length - 2), null, tr_start, tr_end);
            }
            else if(data_target == "table_column"){
                // Add New Column
                $("#table_content tr").each(function(i){
                    var cell_wrapper = (i==0) ? 'th' : 'td';
                    var content = $("#tmpl-"+$shortcode).html();
                    
                    if(i+1 == $("#table_content tr").length)
                        content = $("#table_content tr").last().find('td').first().html();
                    $(this).find(cell_wrapper).last().before('<'+cell_wrapper+'>' + content + '</'+cell_wrapper+'>');
                })
            }
            $.HandleElement.initEditElement(modal.container);
            $.HandleElement.initDeleteElement(modal.container);
            self.reindexTable();
        }
    }

    var tr_start = "<textarea class='hidden' data-sc-info='shortcode_content' name='shortcode_content[]'>[pb_table_item tagname=\"tr_start\" ][/pb_table_item]</textarea>";
    var tr_end = "<textarea class='hidden' data-sc-info='shortcode_content' name='shortcode_content[]'>[pb_table_item tagname=\"tr_end\" ][/pb_table_item]</textarea>";
    $(document).ready(function(){
    	var tableModal = null;
        var colspan = $("#param-colspan").val();
        var rowspan = $("#param-rowspan").val();
        var Pb_Table = new $.PBTable();
        
        // append Textarea To Rows
        $("#table_content").find("tr").slice(0, $("#table_content").find("tr").length - 1).each(function(i){
            var first_child = (i==0) ? $(this).find('th').first() : $(this).find('td').first();
            Pb_Table.appendTextarea($(this), first_child);
        })

        
        Pb_Table.reindexTable();
       
        $('body').bind('before_save_modal', function(e, shortcode, isSubmodal) {
        	var table = new $.PBTable();
            //table.init(shortcode, isSubmodal);
        });
        
        $('body').bind('pb_modal_loaded', function(e, modal) {
        	if (tableModal == null)
        	{	
        		Pb_Table.addRowCol(modal);
        		tableModal = modal;
        	}
        });
        $('body').bind('pb_submodal_load', function(e, modal) {
        	$('#param-cell_content').trigger('change')
        	
        });
       
    })

})(JoomlaShine.jQuery);
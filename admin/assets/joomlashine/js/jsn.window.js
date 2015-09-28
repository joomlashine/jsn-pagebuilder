/**
* 
* Window plugin support open multi-window with variables 
*
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2011 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html

Descriptions:
	1. Required files/libs:
		- jQuery lib
		- jQuery UI
**/

(function($){
	var _internalCheck,
		_uiwIndex  = 0,
		_uiWindowInstances = [];
	$.extend({
		/**
		* 
		* Add popup window. An customize of jQuery UI dialog.
		*
		* @param: (string) (link) is url page you want to open in popup
		* @param: (string) (array) is setting for that window and jQueryUI dialog
		* @return: POPUP page and jQuery object		 
		*/
		JSNUIWindow: function(link, options){
			var ops = $.extend({
				width        : 800,
				height       : 500,
				autoOpen     : true,
				closeOnEscape: false,
				draggable    : true,
				hide         : false,
				modal        : true,
				position     : 'center',
				resizable    : false,
				show         : true,
				buttons      : {},
				open         : {},
				dialogClass  : 'ui-window-site-manager',
				close        : undefined,
				search       : undefined,
				switchMode   : undefined,
				scrollContent: true,
				tmplComponent: /tmpl=component/.test( link ),
				title		 : ''
			}, options);

			_uiwIndex++;
			var $this = this;
			var $id = 'uiwindow'+'-'+_uiwIndex;
			if (_uiWindowInstances[$id] !== undefined) return _uiWindowInstances[$id];
			if (ops.modal){
				$('.jsnui-window-iframe').parent().remove();
			}
			var $iframeId    = 'iframe-'+$id;
			var imgLoadingId = 'img-loading-'+$id;
			var $w  =  $('<div/>', {
				'id'   : $id,
				'style': 'overflow:hidden;'
			});
			
			var $el = $('<iframe />', {
				'id'          : $iframeId,
				'frameborder' : '0',
				'name'        : 'jsnuiwindow',
				'class'       : 'jsnui-window-iframe',
				'scrolling'	  : (options.scrollContent == false) ? 'no' : 'yes'
			})
			.appendTo($w)
			.css({
				'width'       : ops.width-30,
				'height'      : ops.height-70,
				'overflow-y'  : (options.scrollContent == false) ? 'hidden' : 'scroll',
				'overflow-x'  : 'hidden'
			});
			
			if ((/^([a-z]([a-z]|\d|\+|-|\.)*):(\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?((\[(|(v[\da-f]{1,}\.(([a-z]|\d|-|\.|_|~)|[!\$&'\(\)\*\+,;=]|:)+))\])|((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=])*)(:\d*)?)(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*|(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)){0})(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(link))){
				$el.attr('src', link);
			}
			
			
			/**
			 * Call dialog of jQuery UI
			 */
			if (ops.modal){
				_uiWindowInstances = new Array();
				$('.ui-window-site-manager').remove();
			}
			
			if ( ops.close == undefined ){
				ops.close = function(event, e){
					$w.remove();
				};
			}
			$w.appendTo('header');
			
			$w.dialog({
				width        : ops.width,
				height       : ops.height,
				autoOpen     : ops.autoOpen,
				closeOnEscape: ops.closeOnEscape,
				draggable    : false,
				hide         : ops.hide,
				modal        : ops.modal,
				position     : ops.position,
				resizable    : ops.resizable,
				//show         : ops.show,
				buttons      : ops.buttons,
				open         : ops.open,
				dialogClass  : ops.dialogClass,
				resizeStop   : function(event, e){
					$w.trigger("jsn.window.resize");
					$el.animate({
						width : $w.parent().width()-30,
						height: $w.parent().height()-70
					}, 500 );
				},
				close        : ops.close,
				title		 : ops.title
			});
			
			$w.css('padding-left','5px');
						
			//Swich mode
			if ( ops.switchMode && ops.switchMode.modes.length > 0){
				var switchMode = $('<button />', {
					'id'    : 'ui-window-switchmode',
					'class' : 'ui-window-switchmode'
				}).appendTo($('.ui-window-toolbar-button'));

				var opSwitchMode = $.extend({
					modes : [
							    {
									name   : 'Switch to text mode',
									action : function(event, obj){
										
									},
									defaultMode : false  
								},
								{
									name   : 'Switch to visual mode',
									action : function(event, obj){
										
									},
									defaultMode : true
								}
							]
				}, ops.switchMode);
				
				switchMode.text(opSwitchMode.modes[0].name);
				switchMode.currentMode = 0;
				switchMode.modeAction  = opSwitchMode.modes[0].action;
				switchMode.css('left', (ops.width / 2) - (switchMode.width() / 2) - 15);
				$w.unbind("jsn.window.resize").bind("jsn.window.resize", function(){
					switchMode.animate({
						'left' : (ops.width / 2) - (switchMode.width() / 2) - 15
					});
				});
				switchMode.unbind("click").click(function(e){
					switchMode.modeAction(e, this, $this);
					opSwitchMode.changeMode();
				});
				/**
				 * 
				 * Change modes. Can to change multi modes
				 */
				opSwitchMode.changeMode = function(){
					if (switchMode.currentMode == opSwitchMode.modes.length - 1){
						switchMode.currentMode = 0;
					}else{
						switchMode.currentMode += 1;
					}
					switchMode.html(opSwitchMode.modes[switchMode.currentMode].name);
					switchMode.modeAction  = opSwitchMode.modes[switchMode.currentMode].action;
				};
				/**
				 * Get default mode
				 */
				for(var i = 0; i < opSwitchMode.modes.length; i++){
					if ( opSwitchMode.modes[i].defaultMode ){
						switchMode.html(opSwitchMode.modes[i].name);
						switchMode.currentMode = i;
						switchMode.modeAction  = opSwitchMode.modes[i].action;
						switchMode.unbind("click").click(function(e){
							switchMode.modeAction(e, this, $this);
							opSwitchMode.changeMode();
						});
						switchMode.css('left', (ops.width / 2) - (switchMode.width() / 2) - 15);
					}
				}
			}
			
			//Search
			if (ops.search){
				var search   = $('<input type="text"/>');
				var searchWrapper = $('<span class="jsn-bootstrap ui-window-searchbar-wrapper"/>');
				searchWrapper.appendTo($('.ui-dialog-titlebar'));
				search.appendTo(searchWrapper);
				
				var opSearch = $.extend({
					text             : '',
					css              : {},
					classSet         : '',
					attrs            : {},
					onChange         : undefined,
					onKeyup          : undefined,
					onKeydown        : undefined,
					onBlur           : undefined,
					onFocus          : undefined,
					onClick          : undefined,
					ondblClick       : undefined,
					onKeypress       : undefined,
					closeTextKeyword : false,
					defaultText      : '',
					afterAddTextCloseSearch: function(obj){},
					closeTextClick   : function(obj, searchbox){}
				}, ops.search);
				
				search.val(opSearch.text);
				search.css(opSearch.css);
				search.addClass(opSearch.classSet);
				for(name in opSearch.attrs){
					search.attr(name, opSearch.attrs[name]);
				}
				search.change(opSearch.onChange);
				search.keyup(opSearch.onKeyup);
				search.keydown(opSearch.onKeydown);
				search.blur(opSearch.onBlur);
				search.focus(opSearch.onFocus);
				search.click(opSearch.onClick);
				search.dblclick(opSearch.ondblClick);
				search.keypress(opSearch.onKeypress);
				search.mouseup(function (){
					search.focus();
				});
				if ( opSearch.closeTextKeyword ){
					var closeTextKeyword = $('<a />', {
						'class'  : 'ui-window-closetext-keyword',
						'id'     : 'ui-window-closetext-keyword',
						'href'   : 'javascript:void(0);'
					}).click(function(){
						opSearch.closeTextClick( $(this), search );
					});
					opSearch.afterAddTextCloseSearch(closeTextKeyword);
					search.after(closeTextKeyword);
					search.change(function(){
						if ($(this).val().trim() == opSearch.defaultText || $(this).val().trim() == ''){
							closeTextKeyword.hide();
						}else{
							closeTextKeyword.show();
						}
					});					
				}
			}
		    
			//Add image loading
			if ($('.ui-widget-overlay').find('.img-box-loading').length){
				$('.ui-widget-overlay').find('.img-box-loading').remove();
			}
			
			var imgBoxLoading = $('<div />', {
				'class' : 'img-box-loading'
			})
			.appendTo($('.ui-widget-overlay'))
			.css({
				'top'     : $(window).height()/2-12+'px',
				'left'    : $(document).width()/2-12+'px',
				'z-index' : $.topZIndex()
			});

			$('<img />', {
				'id'    : imgLoadingId,
				'class' : 'imgLoading',
				'src'   : baseUrl+'plugins/system/jsnframework/assets/joomlashine/images/icons-24/icon-24-dark-loading-circle.gif'
			})
			.appendTo(imgBoxLoading);

			if ($w.parent()[0].tagName != 'body'){
				$w.parent().css('left','-900000px');
				//$w.parent().hide();
			}

			if ($.browser.msie && $.browser.version < 9){
				var subHeight = 60;
			}else{
				var subHeight = 59;
			}
			
			$el.load(function(){
				if ($.checkResponse !== undefined) {
					$.checkResponse($el.contents());
				}

				if ( ops.tmplComponent ){
					var form = $(this).contents().find('form[name="adminForm"]');
					if ( !/tmpl=component/.test( form.attr('action') ) ){
						form.attr('action', form.attr('action') + '&tmpl=component');
					}
				}
				
				if ( $.browser.msie && $.browser.version < 8 ){
					$el.css({
						'width' : ops.width + 18,
						'height': ops.height
					});
				}else{
					$el.css({
						'width' : ops.width,
						'height': ops.height - subHeight
					});
				}
				
				$w.css({
					'height':  ops.height - subHeight
					
				})
				.parent()				
				.css({
					'top': $(window).height() / 2 - $w.parent().height()/2,
					'left': $(window).width() / 2 - $w.parent().width()/2
				});
				
				// Make iframe scrollable on ios devices
				// (Ios version equal or below 5.1.1 render not well)
				if (/iPhone|iPod|iPad/.test(navigator.userAgent)) {
					$w.css({
						'height': $el.css('height'),
						'overflow': 'auto',
						'-webkit-overflow-scrolling': 'touch'					
					});
				}
				
				$el.trigger("uiwindow.iframeloaded");
			});
			
			/**
			 * 
			 * Resize window
			 *
			 * @param: (Array) (options) is array store options new size
			 */
			this.resize = function(options)
			{
				var _newSize = $.extend({
					width    : ops.width,
					height   : ops.height,
					complete : function(){
						
					}
				}, options);
				ops.width  = _newSize.width;
				ops.height = _newSize.height;

				$w.trigger("jsn.window.resize");
				$el.css('visibility', 'hidden');
				$w.parent().animate({
					width  : _newSize.width,
					height : _newSize.height,
					left   : $(window).width()/2 - _newSize.width/2,
					top    : $(window).height()/2 - _newSize.height/2
				}, 500, function(){
					$w.dialog("option", _newSize);
					$w.css({
						'width'  : $w.parent().width(),
						'height' : $w.parent().height()-65,
						'padding-left': '5px'
					});
					$el.css({
						'width'  : $w.parent().width(),
						'height' : $w.parent().height()-65
					});
					$el.css('visibility', 'visible');
					_newSize.complete();
				});
			};
			/**
			 * 
			 * Change SRC of iframe content
			 *
			 * @param: (string) (src) is link of page need to show
			 */
			this.changeIframeSrc = function(newSrc)
			{
				if ((/^([a-z]([a-z]|\d|\+|-|\.)*):(\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?((\[(|(v[\da-f]{1,}\.(([a-z]|\d|-|\.|_|~)|[!\$&'\(\)\*\+,;=]|:)+))\])|((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=])*)(:\d*)?)(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*|(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)){0})(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(newSrc))){
					var loading = $('<div />', {
						'class' : 'ui-widget-overlay-changemode'
					});
					$('<img />', {
						'id'  : 'img-loading',
						'src' : baseUrl+'plugins/system/jsnframework/assets/joomlashine/images/icons-24/icon-24-loading-circle.gif'
					})
					.appendTo(loading)
					.css({
						'position': 'relative',
						'top'     : $w.height()/2-12+'px',
						'left'    : $w.width()/2-12+'px'
					});
					$w.resize(function(){
						loading.css({
							'width' : 0,
							'height': 0
						}).css({
							'width' : $w.width(),
							'height': $w.height()
						});
						loading.find('img#img-loading').css
						(
							{
								'position': 'relative',
								'top'     : $(this).height()/2-12+'px',
								'left'    : $(this).width()/2-12+'px'
						    }
						);
					});
					$w.append(loading);
					$el.attr('src', newSrc);
					$el.css('visibility', 'hidden').unbind("uiwindow.iframeloaded").bind("uiwindow.iframeloaded", function(){
						$el.css('visibility', 'visible');
						$w.find('div.ui-widget-overlay-changemode').remove();
					});
				}
			};
			/**
			 * 
			 * Get Iframe of content window
			 *
			 * @return: jQuery Element
			 */
			this.getDialogBox = function(){
				return $w;
			};
			/**
			 * 
			 * Get Iframe of content window
			 *
			 * @return: jQuery Element
			 */
			this.getIframe = function(){
				return $el;
			};
			/**
			* 
			* Show function
			*
			* @return: None/show dialog
			*/
			this.show = function(){
				$w.dialog("open");
			};
			
			/**
			* 
			* Close
			*
			* @return: None/Close dialog 
			*/
			this.close = function(){
				$w.dialog("close");
			};
			
			/**
			* 
			* Option (overwrite)
			*
			* @param: (jQuery object) (option)
			* @return: None
			*/
			this.option = function(option){
				$w.dialog("option", option);
			};

			/**
			* 
			* Submit form
			*
			* @param: (string) (task) is joomla task name submit
			* @return: Submit page
			*/
			this.submitForm = function(task, submitType, __stateReadyCallback){
				submitType = ( submitType == undefined ) ? 'Save' : submitType;
				var completed_require_fields = true;
				if ($el.contents().find('form[name="adminForm"]').length == 0) return false;
				$el.contents().find('input[class~="required"]').each(function(){
					if ($(this).val() == ''){
						$(this).removeClass('invalid').addClass('invalid');
						$(this).attr('aria-invalid', 'true');
						completed_require_fields = false;
						if ($(this).prev().hasClass('required')){
							$(this).prev().removeClass('invalid').addClass('invalid');
							$(this).prev().attr('aria-invalid', 'true');
						}
					}
				});

				if (completed_require_fields){
					if ( submitType.trim().toLowerCase() == 'save & close' ){
						$w.parent().fadeOut(300, function(){
							$el.load(function(){
								if(!$(this).contents().find('#system-message-container dd.error>ul>li,dd.warning>ul>li').text()){
									$w.dialog("close");	
								}else{
									$w.parent().fadeIn(200);									
								}
							});
						});
					}
					
					$el[0].onload = function(){
						if ($.isFunction(__stateReadyCallback)){
							__stateReadyCallback();
						}						
						
					};
					$el.contents().find('input[name="task"]').val(task);
					$el.contents().find('form[name="adminForm"]').submit();
					return true;
				}
				return false;
			};

			//Somthing jqueryui dialog hidden body page
			clearInterval(_internalCheck);
			_internalCheck = setInterval(function(){
				if ($('body').css('display') == 'none'){
					$('body').show();
				}
				
				if ( parseFloat($w.parent().css('top')) < 0){
					$w.parent().css({
						'top': $(window).height() / 2 - $w.parent().height()/2
					});
				}
			}, 100);
			
			//reassignment
			$this = this;
			_uiWindowInstances[$id] = this;
			return this;
		},
		
		
		/**
		* 
		* Add an popup window message. An customize of jQuery UI dialog.
		*
		* @param: (string) (msg) is text of message
		* @param: 9string) (delay) is (number of time delay)
		* @return: Add jQuery element
		*/
		JSNUIMessage: function(msg, delay){
			if ( $('#jsn-poweradmin-message').length > 0 ){
				$('#jsn-poweradmin-message').dialog("close");
				$('#jsn-poweradmin-message').remove();
			}

			$('body').append('<div id="jsn-poweradmin-message">'+msg+'</div>');
			var w_msg = $('#jsn-poweradmin-message');
			
			w_msg.dialog({
				title       : JSNLang.translate( 'JSN_MESSAGE_TITLE' ),
				width       : 500,
				height      : 60,
				resizable   : false,
				modal       : false,
				draggable   : false,
				dialogClass : 'ui-window-site-manager jsn-poweradmin-message',
				close       : function(){
					w_msg.remove();
				}
			}).prev().hide();

			w_msg.css('height', 'auto')
				.parent()
				.css({
					'height'   : 0,
					'bottom'   : 20,
					'left'     : parseInt($(window).width())/2 - parseInt(w_msg.width())/2,
					'top'      : '',
					'position' : 'fixed'
				});

			w_msg.parent().animate({
				"height": w_msg.height()
			}, "slow");
			
			setTimeout(function(){
				if (w_msg.length){
					w_msg.parent().animate({
						"height" : "toggle", 
						"opacity": "toggle"
					}, { duration: "slow" }, function(){
						w_msg.dialog("close");
						$('#jsn-poweradmin-message').remove();
					});
				}
			}, delay);
		}
	});
	
	/**
	* 
	* Get Instances JSNUIWindow
	* 
	* @return: Close all windows
	*/
	$.closeAllJSNWindow = function(){
		try{
			$('.jsnui-window-iframe').parent().dialog("close");
		}catch(e){
			console.log(e.message);
		}
	};
})(JoomlaShine.jQuery);
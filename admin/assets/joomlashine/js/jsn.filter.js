/**
 * 
 * To help filter elements on page and support sorting. Thank to jQueryUI
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
	/**
	 * 
	 * Filter setting for page
	 * 
	 * @param: (jQuery object) (options) to setting 
	 * @return: jQuery object element
	 */
	$.jsnFilter = function(options) 
	{
		//Interval value
		this.intervalFilter = null;
		//Time tick to filter 
		this.timeShowResult = 0;
		//Array elements 
		this.animates;
		//prev keywork
		this.prevKeyword = '';
		//Options
		this.ops = {
			frameElement: $({}),
			totalItems  : 0,
			category    : false,
			itemClass   : '',
			mPosLeft    : 10,
			mPosTop     : 0,
			marginOffset: {
				  right : 10,
				  bottom: 20
			},
			eventClick: function(){
				 //TOTO: your command lines
			},
			defaultText: 'Search...'
		};
		  /**
		   *
		   * Save setting or edit setting
		   *
		   * @param: (jQuery element) (options) to setting
		   * @return: Save setting
		   */
		this.config = function(options){
				$.extend(this.ops, options);
				// custom css expression for a case-insensitive contains()
				$.expr[':'].contains = function(a,i,m){
				    return (a.textContent || a.innerText || "").toLowerCase().indexOf(m[3].toLowerCase())>=0;
				};
				this.initEvents();	
				this.resetItems();
				return this;
		};
		/**
		   * 
		   * Filter result
		   *
		   * @param: (string) (filter) is keyword 
		   * @return: None/Change attr of HTML elements
		   */
		  this.filterResults = function(filter){
				var $this = this;
				if ($this.prevKeyword == filter) return;
				$this.timeShowResult = 0;
				clearInterval( $this.intervalFilter );
				$this.intervalFilter = setInterval(function(){
					$this.timeShowResult++;
					if ($this.timeShowResult == 60){
						var positions_listing = $this.ops.frameElement;
						if (filter.trim() == $this.ops.defaultText) filter = '';
						positions_listing.find("div" + $this.ops.itemClass).removeClass('mark_hidden').removeClass('hidden_item');
						if (filter){
							//Hide all position not contain the input
							positions_listing.find("div:not(div:contains("+filter+"))").parent().addClass('mark_hidden');
							//Show all position contain the input
							positions_listing.find("div:contains("+filter+")").parent().removeClass('mark_hidden').removeClass('hidden_item').end().fadeIn(0);
						}else{
							if ($this.ops.category){
								$this.ops.frameElement.removeClass('mark_hidden').removeClass('hidden_item').fadeIn(0);
							}
							positions_listing.find("div" + $this.ops.itemClass)
												.removeClass('mark_hidden')
												.removeClass('hidden_item')
												.children()
												.fadeIn(0);
							$this.resetItems();
						}
						$this.prevKeyword = filter;
						$this.initEvents();
						$this.animateShows();
						clearInterval($this.intervalFilter);
					}
				}, 1);
				
				return false;
		};
		/**
		*
		* Show all result found using jQueryUI animate
		*
		* @return: None/Change position of HTML elements
		*/
		this.animateShows = function(){	
			var i = 0,
				j = 0,
				$this = this,
				posLeft = $this.ops.mPosLeft,
				posTop  = $this.ops.mPosTop;

			$('.mark_hidden div').fadeOut(300, function(){
				 $(this).parent().addClass('hidden_item').fadeOut(0);
			});

			$this.ops.frameElement.each(function(){
				var frame = $(this), totalItems = 0, i = 0, _left;

				$($this.ops.itemClass, frame).each(function(){
					if (!$(this).hasClass('mark_hidden')){
						totalItems++;
					}
				});

				if (totalItems > 0){
					frame.css('width',  $this.ops.totalColumn*($this.ops.marginOffset.right + $this.ops.itemWidth) - $this.ops.marginOffset.right);
					frame.fadeIn(300).css('height', Math.ceil(totalItems / $this.ops.totalColumn)*($this.ops.itemHeight + $this.ops.marginOffset.bottom) );
				}else{
					frame.fadeOut(300);
				}
				
				if (frame.find('.mark_hidden').length == frame.find($this.ops.itemClass).length && frame.prev()[0].tagName == 'H3'){
					frame.prev().hide();
				}else{
					frame.prev().show();
				}

				//if (!$.browser.mozilla && $this.ops.category){
				//	posTop  = $this.ops.mPosTop + 20;
				//	_left = $this.ops.mPosLeft + 5;
				//}else{
					posTop  = $this.ops.mPosTop;
					_left = $this.ops.mPosLeft;
				//}
				posLeft = _left;

				$($this.ops.itemClass, frame).each(function(){
					if (!$(this).hasClass('mark_hidden')){
						if (i % $this.ops.totalColumn == 0 && i != 0){
							posLeft  = _left;
							posTop  += $this.ops.itemHeight + $this.ops.marginOffset.bottom;
						}else if(i > 0){
							posLeft += $this.ops.itemWidth  + $this.ops.marginOffset.right;
						}
						$(this).animate({
							left: posLeft,
							top : posTop
						}, 300, function(){
							$(this).fadeIn(300);
						});
						i++;
					}
				});
			});
		};
		/**
		   *		   
		   * Add function execute when click item 
		   */
		this.initEvents = function(){
			$(this.ops.itemClass).click(this.ops.eventClick);
			$('.hidden_item').unbind("click");
		};
		/**
		   *
		   * Reset position of items and resize all
		   *
		   * @return: None/Change HTML elements
		   */
		this.resetItems = function(){
				var posLeft, posTop, $this = this;
				 
				if (this.ops.category){
					var $this = this;
					this.ops.frameElement.each(function(){
						var totalItems = $($this.ops.itemClass, this).length;
						$(this).css('width',  $this.ops.totalColumn*($this.ops.marginOffset.right + $this.ops.itemWidth) - $this.ops.marginOffset.right);
						$(this).css('height', Math.ceil(totalItems / $this.ops.totalColumn)*($this.ops.itemHeight + $this.ops.marginOffset.bottom) );
					});
				}else{
					this.ops.frameElement.css('width', this.ops.totalColumn*(this.ops.marginOffset.right + this.ops.itemWidth));
					this.ops.frameElement.css('height', Math.ceil(this.ops.totalItems / this.ops.totalColumn)*(this.ops.itemHeight+this.ops.marginOffset.bottom));
				}

				$this.ops.frameElement.each(function(){
					var frame = $(this);
					var _left = 0;
					//if (!$.browser.mozilla && $this.ops.category){
					//	posTop = $this.ops.mPosTop + 20;
					//	_left  = $this.ops.mPosLeft + 5;
					//}else{
						posTop = $this.ops.mPosTop;
						_left  = $this.ops.mPosLeft;
					//}
					posLeft = _left;
					var i = 0;
					$($this.ops.itemClass, frame).each(function(){
						if (i % $this.ops.totalColumn == 0 && i != 0){
							posLeft  = _left;
							posTop  += $this.ops.itemHeight + $this.ops.marginOffset.bottom;
						}else if(i > 0){
							posLeft += $this.ops.itemWidth  + $this.ops.marginOffset.right;
						}
						$(this).css({
						  	'left': posLeft,
						  	'top' : posTop
						}).fadeIn(300);
						i++;
					});
				});
		};
		return this.config(options);
	};
	
	/**
	 * spolight module filter for module panel in rawmode
	 * @param: (Jquery Object)input  (filter input object) 
	 * @param: (array) options
	 */
	$.JSNSpotligthModuleFilter = function (input, options) 
	{		
		//options
		this.ops = $.extend({
			//element item container class name
			elementContainer: 'jsn-element-container',
			//module text wrapper class name
			moduleTextWrapper: 'poweradmin-module-item-inner-text',
			//element item container class name
			moduleContainer: 'poweradmin-module-item',
			//text hightlight class name
			textHighlight: 'jsn-filter-highlight',
			//module highlight clasname
			moduleHighlight: 'poweradmin-module-item-highlight'
		}, options);
		
		_root = this;		
		inputContainer = input.parent();
		
		var typewatch = (function(){
		  var timer = 0;
		  return function(callback, ms){
		    clearTimeout (timer);
		    timer = setTimeout(callback, ms);
		  }  
		})();

		
		this.initFilter	= function ()
		{
			var placeHolder = '<span class="placeholder">' + _root.ops.defaultText + '</span>';		
			input.after(placeHolder);	
			
			inputContainer.mouseup( function (){
				if( !input.value ){
					$('.placeholder',this).hide();
					input.focus();
					input.removeClass('blur').addClass('focus');
				}	
			});
			
			input.blur(function (){			
				if( !this.value ){
					$('.placeholder',inputContainer).show();				
				}else{
					$('.placeholder',inputContainer).hide();
				}
				input.removeClass('focus').addClass('blur');
			});
			
			var _oldValue = '';
			input.unbind('keydown').keydown( function (){	
				typewatch(function() {
					var inputValue = input.attr('value');
					if(inputValue && inputValue.length >= 3){
						inputContainer.children('.close').show();		
						_root.resetFilter();
						_root.doFilter();
						_oldValue = inputValue;					
					}else if(!inputValue){
						_root.resetFilter();
						inputContainer.children('.close').hide();
						_oldValue = '';
					}else{
						inputContainer.children('.close').show();
					}
				}, 500);							
			});
			
			inputContainer.children('.close').unbind('click').bind('click',function (){
				var curValueLength = input.attr('value').length;
				input.attr('value','');
				input.blur();
				this.hide();
				if(curValueLength>=3){
					_root.resetFilter();
				}				
			});
		}
		
		this.initFilter();	
		
		this.doFilter	= function ()
		{			
			var filterValue = input.attr('value');
			if(filterValue.length < 3) return;
			this.collapseAllPosition();
			$('.' + this.ops.elementContainer + ' .' + _root.ops.moduleTextWrapper + ':ciContains("' + filterValue + '")').each(function (){				
				if( $(this).parents('.' + _root.ops.moduleContainer).hasClass('history-dipslayed')){					
					$(this).parents('.' + _root.ops.elementContainer).find('.' + _root.ops.moduleContainer+'.history-dipslayed').show();
					$(this).parents('.' + _root.ops.moduleContainer).addClass(_root.ops.moduleHighlight);
					$(this).html($(this).text());
					$(this).highlight(filterValue,_root.ops.textHighlight);
				}				
			});			
			
		}
		
		this.resetFilter = function ()
		{			
			$('.' + this.ops.moduleTextWrapper).each(function (){
				$(this).html($(this).text());
			});
			$('.' + this.ops.moduleHighlight).removeClass(this.ops.moduleHighlight);			
			$('.' + this.ops.moduleContainer).each(function (){				
				if( $(this).hasClass('history-dipslayed')){
					$(this).show();
				}
			});
			JSNGrid.initLayout();
		}
		
		this.collapseAllPosition = function ()
		{		
			$('.'+this.ops.elementContainer + ' .' + this.ops.moduleContainer).each( function (){
				if( $(this).css('display') != 'none') {				
					$(this).removeClass('history-dipslayed').addClass('history-dipslayed');
				}
			}) .hide();
			JSNGrid.initLayout();			
		}		
		
		this.expandPosition = function (el)
		{
			el.children('.' + this.ops.moduleContainer).show();
			JSNGrid.initLayout();
		}
		
	};
})(JoomlaShine.jQuery);

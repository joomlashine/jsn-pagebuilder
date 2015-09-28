<?php
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


// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');

?>

<form action="<?php echo JRoute::_('index.php?option=com_pagebuilder&view=selectmodule&tmpl=component'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="jsn-bootstrap">
        <div class="jscroll-inner">
        <div id="jsn-module-container" class="module-scroll-fade" style="min-height:300px">

        </div>

        </div>
    </div>
</form>
<script type="text/javascript">
    (function($) {

	$.fn.scrollPagination = function(options) {

		var settings = {
			nop     : 10, // The number of posts per scroll to be loaded
			offset  : 0, // Initial offset, begins at 0 in this case
			error   : 'No More Posts!', // When the user reaches the end this is the message that is
			                            // displayed. You can change this if you want.
			delay   : 300, // When you scroll down the posts will load after a delayed amount of time.
			               // This is mainly for usability concerns. You can alter this as you see fit
			scroll  : true // The main bit, if set to false posts will not load as the user scrolls.
			               // but will still load if the user clicks.
		}

		// Extend the options so they work with the plugin
		if(options) {
			$.extend(settings, options);
		}

		// For each so that we keep chainability.
		return this.each(function() {

			// Some variables
			$this = $(this);
			$settings = settings;
			var offset = $settings.offset;
			var busy = false; // Checks if the scroll action is happening
			                  // so we don't run it multiple times

			// Custom messages based on settings
			if($settings.scroll == true) $initmessage = 'Scroll for more or click here';
			else $initmessage = 'Click for more';

			// Append custom messages and extra UI
			$this.append('<div style="clear:both"></div><div class="loading-bar"></div>');

			function getData() {

				// Post data to ajax
				$.post('index.php?option=com_pagebuilder&view=selectmodule&layout=data', {

                                    action        : 'scrollpagination',
				    number        : $settings.nop,
				    offset        : offset,

				}, function(data) {
                                        var $content =  $(data).find(".jsnpbd-module-content");
                                        if ($content.html().trim() == "") {
                                            $this.find('.loading-bar').remove();
                                        }
                                        else {
                                          $("#jsn-module-container").append($content.html());
                                          $this.find('.loading-bar').hide();
                                        }
                                        busy = false;
                                        offset += $settings.nop;
                                        return;

					// Change loading bar content (it may have been altered)
					$this.find('.loading-bar').html($initmessage);
				});

			}

			getData(); // Run function initially

			// If scrolling is enabled
			if($settings.scroll == true) {
				// .. and the user is scrolling
				$(window).scroll(function() {

					// Check the user is at the bottom of the element
					if($(window).scrollTop() + $(window).height() > $this.height() && !busy) {

						// Now we are working, so busy is true
						busy = true;

						// Tell the user we're loading posts
						//$this.find('.loading-bar').html('Loading Posts');

						// Run the function to fetch the data inside a delay
						// This is useful if you have content in a footer you
						// want the user to see.
						setTimeout(function() {

							getData();
							$this.find('.loading-bar').show();
						}, $settings.delay);

					}
				});
			}

			// Also content can be loaded by clicking the loading bar/
			$this.find('.loading-bar').click(function() {

				if(busy == false) {
					busy = true;
					getData();
				}

			});

		});
	}

})(jQuery);
</script>
<script type="text/javascript">
    $(document).ready(function() {

	$('.jscroll-inner').scrollPagination({

		nop     : 10, // The number of posts per scroll to be loaded
		offset  : 0, // Initial offset, begins at 0 in this case
		error   : 'No More Posts!', // When the user reaches the end this is the message that is
		                            // displayed. You can change this if you want.
		delay   : 300, // When you scroll down the posts will load after a delayed amount of time.
		               // This is mainly for usability concerns. You can alter this as you see fit
		scroll  : true // The main bit, if set to false posts will not load as the user scrolls.
		               // but will still load if the user clicks.

	});

});
</script>
<script type="text/javascript">
(function($){
    $(window).ready(function(){
        $("#jsn-module-container").delegate('.jsn-item-type', "click", function(e) {
            var id = $(this).attr('id');
            var selected = $(this).find('div, div.editlinktip').attr('title');
            if (window.parent && typeof window.parent['setSelectModule'] == 'function') {
                window.parent['setSelectModule'](id + '-' +selected, '#param-module_name');
            }
        });
        });
  })(JoomlaShine.jQuery);
</script>
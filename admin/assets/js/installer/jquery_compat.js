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

var JSNInstaller = (function($) {
	var JSNInstaller = function() {
		this.installer = $('#jsn-installer');
		this.schedules = $('#jsn-installer-list');
		this.loginForm = $('#jsn-installer-login');

		if (this.schedules) {
			this.loginForm.length ? this.init() : this.install();
		} else {
			this.finish();
		}
	};

	JSNInstaller.prototype = {
		init: function() {
			this.loginForm.find('button.btn').click($.proxy(this.install, this));
		},

		install: function() {
			if (this.loginForm.length) {
				this.loginForm.hide();
				this.schedules.show();
			}
			this.schedules = this.schedules.children('li');
			this.current = 0;
			this.downloadPackage();
		},

		downloadPackage: function() {
			if (this.current < this.schedules.length) {
				// Display status
				this.schedules.eq(this.current).find('ul').show();
				this.schedules.eq(this.current).find('.jsn-installer-downloading').css('display', 'list-item');

				// Set task
				this.installer[0].task.value = 'installer.download';

				// Request server to download dependency package
				$.ajax({
					url: this.installer.attr('action') + '?' + this.schedules.eq(this.current).attr('ref'),
					data: this.installer.serialize(),
					type: this.installer.attr('method'),
					context: this,
					complete: function(jqXHR, textStatus) {
						var	status = this.schedules.eq(this.current).find('.jsn-installer-downloading .jsn-installer-status').removeClass('jsn-icon-loading'),
							parsed;

						this.clearTimer(this.schedules.eq(this.current).find('.jsn-installer-downloading .jsn-installer-processing'));

						if (parsed = jqXHR.responseText.match(/^FAIL:([^\|]+)\|(https?:\/\/.+)$/)) {
							// Switch status
							status.addClass('jsn-icon-warning');

							// Show error message
							this.schedules.eq(this.current).find('.jsn-installer-downloading .jsn-installer-error').show().html(parsed[1]);

							// Force manual download
							this.manualDownload(parsed[2]);
						} else if (jqXHR.responseText.match(/^jsn_[^\s]+_install\.zip$/)) {
							// Switch status
							status.addClass('jsn-icon-ok');

							// Request server to install recently downloaded dependency package
							this.installPackage(jqXHR.responseText);
						} else {
							// Switch status
							status.addClass('jsn-icon-fail');

							// Show error message
							this.schedules.eq(this.current).find('.jsn-installer-downloading .jsn-installer-error').show().html(jqXHR.responseText);

							// Request server to download next dependency package
							this.current++;
							this.downloadPackage();
						}
					}
				});
			} else {
				this.finish();
			}

			this.setTimer(this.schedules.eq(this.current).find('.jsn-installer-downloading .jsn-installer-processing'));
		},

		manualDownload: function(link) {
			this.downloader = this.downloader || $('#jsn-installer-manual-download');
			this.downloader.show().appendTo(this.schedules.eq(this.current).find('.jsn-installer-downloading .jsn-installer-error').show());
			this.downloader.find('ol li a').attr('href', link);
			this.downloader.find('button').unbind('click').click($.proxy(function() {
				this.installPackage();
				return false;
			}, this));
		},

		installPackage: function(data) {
			// Update download status
			if (!data && this.downloader.css('display') == 'block') {
				this.schedules.eq(this.current).find('.jsn-installer-downloading .jsn-installer-status').removeClass('jsn-icon-warning').addClass('jsn-icon-ok');
				this.downloader.parent().hide();
			}

			// Display status
			this.schedules.eq(this.current).find('.jsn-installer-installing').css('display', 'list-item');

			// Set task
			this.installer[0].task.value = 'installer.install';

			// Request server to install dependency package
			if (data) {
				$.ajax({
					url: this.installer.attr('action') + '?package=' + data + '&' + this.schedules.eq(this.current).attr('ref'),
					data: this.installer.serialize(),
					type: this.installer.attr('method'),
					context: this,
					complete: this.finalizeInstall
				});
			} else {
				var id = 'jsn_installer_iframe_' + this.current,
				toElement = (function() {
					var div = document.createElement('div');
					return function(html) {
						div.innerHTML = html;
						var el = div.firstChild;
						return div.removeChild(el);
					};
				})();

				// Create iframe for submitting form
				this.iframe = toElement('<iframe src="javascript:false;" name="' + id + '" />');

				this.iframe.setAttribute('id', id);
				this.iframe.style.display = 'none';

				document.body.appendChild(this.iframe);

				// Clone the original form
				this.form = toElement('<form method="post" enctype="multipart/form-data"></form>');

				this.form.setAttribute('action', this.installer.attr('action') + '?' + this.schedules.eq(this.current).attr('ref'));
				this.form.setAttribute('target', this.iframe.name);								   
				this.form.style.display = 'none';

				document.body.appendChild(this.form);

				// Pass input field to cloned form
				for (var field in this.installer[0]) {
					if (
						this.installer[0][field] && this.installer[0][field].nodeName
						&&
						this.installer[0][field].nodeName.toLowerCase() == 'input'
					) {
						if (this.installer[0][field].getAttribute('type') == 'hidden') {
							var el = document.createElement('input');
							el.setAttribute('type', 'hidden');
							el.setAttribute('name', field);
							el.setAttribute('value', this.installer[0][field].value);
							this.form.appendChild(el);
						} else if (this.installer[0][field].getAttribute('type') == 'file') {
							this.form.appendChild(this.installer[0][field]);
						}
					}
				}

				// Submit the form
				this.form.submit();

				// Request sent, clean up
				this.form.parentNode.removeChild(this.form);
				this.form = null;

				// Re-create package selector
				$('#jsn-installer-package-selector').append(toElement('<input type="file" name="package" size="40" />'));

				// Get response from iframe
				this.getResponse();
			}

			this.setTimer(this.schedules.eq(this.current).find('.jsn-installer-installing .jsn-installer-processing'));
		},

		getResponse: function() {
			var toDeleteFlag = false;   
			   
			// Set handler to get response of form submit action
			this.iframe.onload = function() {				
				if (
					// For Safari 
					this.iframe.src == "javascript:'%3Chtml%3E%3C/html%3E';"
					||
					// For FF, IE
					this.iframe.src == "javascript:'<html></html>';"
				) {																		
					// First time around, do not delete.
					// We reload to blank page, so that reloading main page does not re-submit the post.
					if (toDeleteFlag) {
						// Fix busy state in FF3
						setTimeout($.proxy(function() {
							this.iframe.parentNode.removeChild(this.iframe);
							this.iframe = null;
						}, this), 0);
					}

					return;
				}

				var doc = this.iframe.contentDocument ? this.iframe.contentDocument : window.frames[this.iframe.id].document;

				// Fixing Opera 9.26,10.00
				if (doc.readyState && doc.readyState != 'complete') {
				   // Opera fires load event multiple times even when the DOM is not ready yet.
				   // This fix should not affect other browsers.
				   return;
				}

				// Fixing Opera 9.64
				if (doc.body && doc.body.innerHTML == 'false') {
					// In Opera 9.64 event was fired second time when body.innerHTML changed from false to server response approx. after 1 sec
					return;
				}

				this.finalizeInstall(doc.body.innerHTML);

				// Reload blank page, so that reloading main page does not re-submit the post.
				// Also, remember to delete the frame.
				toDeleteFlag = true;

				// Fix IE mixed content issue
				this.iframe.src = "javascript:'<html></html>';";
			}.bind(this);
		},

		finalizeInstall: function(data) {
			var	data = typeof data == 'object' ? data.responseText : data,
				status = this.schedules.eq(this.current).find('.jsn-installer-installing .jsn-installer-status').removeClass('jsn-icon-loading'),
				parsed;

			this.clearTimer(this.schedules.eq(this.current).find('.jsn-installer-installing .jsn-installer-processing'));

			if (parsed = data.match(/^FAIL:([^\|]+)\|(https?:\/\/.+)$/)) {
				// Switch status
				status.addClass('jsn-icon-warning');

				// Show error message
				this.schedules.eq(this.current).find('.jsn-installer-installing .jsn-installer-error').show().html(parsed[1]);

				// Force manual download
				this.manualDownload(parsed[2]);
			} else {
				if (data == 'SUCCESS') {
					// Switch status
					status.addClass('jsn-icon-ok');
				} else {
					// Switch status
					status.addClass('jsn-icon-fail');

					// Show error message
					this.schedules.eq(this.current).find('.jsn-installer-installing .jsn-installer-error').show().html(data);
				}

				// Request server to download next dependency package
				this.current++;
				this.downloadPackage();
			}
		},

		finish: function() {
			// Set task
			this.installer[0].task.value = 'installer.finalize';

			// Show the finalization form
			$('#jsn-installer-finalization').show();
		},

		setTimer: function(element) {
			this.processing = element;

			// Schedule processing notice
			this.timer = setInterval($.proxy(function() {
				var msg = this.processing.html();

				if (msg == 'Still in progress...') {
					this.processing.html('Please wait...');
				} else {
					this.processing.html('Still in progress...');
				}
			}, this), 3000);
		},

		clearTimer: function(element) {
			clearInterval(this.timer);
			element.hide();
		}
	};

	return JSNInstaller;
})(jQuery);

new JSNInstaller();

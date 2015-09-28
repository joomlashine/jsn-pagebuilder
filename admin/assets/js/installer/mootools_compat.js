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

var JSNInstaller = function() {
	this.installer = document.id('jsn-installer');
	this.schedules = document.id('jsn-installer-list');
	this.loginForm = document.id('jsn-installer-login');

	if (this.schedules) {
		this.loginForm ? this.init() : this.install();
	} else {
		this.finish();
	}
};

JSNInstaller.prototype = {
	init: function() {
		this.loginForm.getElement('button.btn').addEvent('click', this.install.bind(this));
	},

	install: function() {
		if (this.loginForm) {
			this.loginForm.hide();
			this.schedules.show();
		}
		this.schedules = this.schedules.getChildren('li');
		this.current = 0;
		this.downloadPackage();
	},

	downloadPackage: function() {
		if (this.current < this.schedules.length) {
			// Display status
			this.schedules[this.current].getElement('ul').show();
			this.schedules[this.current].getElement('.jsn-installer-downloading').setStyle('display', 'list-item');

			// Set task
			this.installer.task.value = 'installer.download';

			// Request server to download dependency package
			new Request({
				url: this.installer.getAttribute('action') + '?' + this.schedules[this.current].getAttribute('ref'),
				data: this.installer,
				onComplete: function(data) {
					var	status = this.schedules[this.current].getElement('.jsn-installer-downloading .jsn-installer-status').removeClass('jsn-icon-loading'),
						parsed;

					this.clearTimer(this.schedules[this.current].getElement('.jsn-installer-downloading .jsn-installer-processing'));

					if (parsed = data.match(/^FAIL:([^\|]+)\|(https?:\/\/.+)$/)) {
						// Switch status
						status.addClass('jsn-icon-warning');

						// Show error message
						this.schedules[this.current].getElement('.jsn-installer-downloading .jsn-installer-error').show().innerHTML = parsed[1];

						// Force manual download
						this.manualDownload(parsed[2]);
					} else if (data.match(/^jsn_[^\s]+_install\.zip$/)) {
						// Switch status
						status.addClass('jsn-icon-ok');

						// Request server to install recently downloaded dependency package
						this.installPackage(data);
					} else {
						// Switch status
						status.addClass('jsn-icon-fail');

						// Show error message
						this.schedules[this.current].getElement('.jsn-installer-downloading .jsn-installer-error').show().innerHTML = data;

						// Request server to download next dependency package
						this.current++;
						this.downloadPackage();
					}
				}.bind(this)
			}).send();

			this.setTimer(this.schedules[this.current].getElement('.jsn-installer-downloading .jsn-installer-processing'));
		} else {
			this.finish();
		}
	},

	manualDownload: function(link) {
		this.downloader = this.downloader || document.id('jsn-installer-manual-download');
		this.downloader.show().injectInside(this.schedules[this.current].getElement('.jsn-installer-downloading .jsn-installer-error').show());
		this.downloader.getElement('ol li a').setProperty('href', link);
		this.downloader.getElement('button').onclick = function() {
			this.installPackage();
			return false;
		}.bind(this);
	},

	installPackage: function(data) {
		// Update download status
		if (!data && this.downloader.getStyle('display') == 'block') {
			this.schedules[this.current].getElement('.jsn-installer-downloading .jsn-installer-status').removeClass('jsn-icon-warning').addClass('jsn-icon-ok');
			this.downloader.getParent().hide();
		}

		// Display status
		this.schedules[this.current].getElement('.jsn-installer-installing').setStyle('display', 'list-item');

		// Set task
		this.installer.task.value = 'installer.install';

		// Request server to install dependency package
		if (data) {
			new Request({
				url: this.installer.getAttribute('action') + '?package=' + data + '&' + this.schedules[this.current].getAttribute('ref'),
				data: this.installer,
				onComplete: this.finalizeInstall.bind(this)
			}).send();
		} else {
			var id = 'jsn_installer_iframe_' + this.current,
			toElement = (function(){
				var div = document.createElement('div');
				return function(html){
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

			this.form.setAttribute('action', this.installer.action + '?' + this.schedules[this.current].getAttribute('ref'));
			this.form.setAttribute('target', this.iframe.name);								   
			this.form.style.display = 'none';

			document.body.appendChild(this.form);

			// Pass input field to cloned form
			for (var field in this.installer) {
				if (
					this.installer[field] && this.installer[field].nodeName
					&&
					this.installer[field].nodeName.toLowerCase() == 'input'
				) {
					if (this.installer[field].getAttribute('type') == 'hidden') {
						var el = document.createElement('input');
						el.setAttribute('type', 'hidden');
						el.setAttribute('name', field);
						el.setAttribute('value', this.installer[field].value);
						this.form.appendChild(el);
					} else if (this.installer[field].getAttribute('type') == 'file') {
						this.form.appendChild(this.installer[field]);
					}
				}
			}

			// Submit the form
			this.form.submit();

			// Request sent, clean up
			this.form.parentNode.removeChild(this.form);
			this.form = null;

			// Re-create package selector
			document.id('jsn-installer-package-selector').appendChild(toElement('<input type="file" name="package" size="40" />'));

			// Get response from iframe
			this.getResponse();
		}

		this.setTimer(this.schedules[this.current].getElement('.jsn-installer-installing .jsn-installer-processing'));
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
					setTimeout(function() {
						this.iframe.parentNode.removeChild(this.iframe);
						this.iframe = null;
					}.bind(this), 0);
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
		var	status = this.schedules[this.current].getElement('.jsn-installer-installing .jsn-installer-status').removeClass('jsn-icon-loading'),
			parsed;

		this.clearTimer(this.schedules[this.current].getElement('.jsn-installer-installing .jsn-installer-processing'));

		if (parsed = data.match(/^FAIL:([^\|]+)\|(https?:\/\/.+)$/)) {
			// Switch status
			status.addClass('jsn-icon-warning');

			// Show error message
			this.schedules[this.current].getElement('.jsn-installer-installing .jsn-installer-error').show().innerHTML = parsed[1];

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
				this.schedules[this.current].getElement('.jsn-installer-installing .jsn-installer-error').show().innerHTML = data;
			}

			// Request server to download next dependency package
			this.current++;
			this.downloadPackage();
		}
	},

	finish: function() {
		// Set task
		this.installer.task.value = 'installer.finalize';

		// Show the finalization form
		document.id('jsn-installer-finalization').show();
	},

	setTimer: function(element) {
		this.processing = element;

		// Schedule processing notice
		this.timer = setInterval(function() {
			var msg = this.processing.innerHTML;

			if (msg == 'Still in progress...') {
				this.processing.innerHTML = 'Please wait...';
			} else {
				this.processing.innerHTML = 'Still in progress...';
			}
		}.bind(this), 3000);
	},

	clearTimer: function(element) {
		clearInterval(this.timer);
		element.hide();
	}
};

new JSNInstaller();

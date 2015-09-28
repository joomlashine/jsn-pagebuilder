<?php
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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<div class="jsn-bootstrap">
	<div class="jsn-installer-container">
		<form id="jsn-installer" name="JSNInstaller" method="POST" class="form-horizontal" action="<?php echo JRoute::_('index.php'); ?>">
<?php
if ($this->missingDependency)
{
?>
			<h1><?php echo JText::sprintf('JSN_EXTFW_INSTALLER_SUBTITLE', preg_replace('/^JSN\s*/i', '', JText::_((string) $this->xml->name))); ?></h1>
			<div class="jsn-installer-content">
				<p><?php echo JText::_('JSN_EXTFW_INSTALLER_MESSAGE'); ?></p>
<?php
	if (isset($this->errors) AND count($this->errors = array_unique($this->errors)))
	{
?>
				<div class="alert alert-error">
					<p><span class="label label-important"><?php echo JText::_('ERROR'); ?></span></p>
					<ul><li><?php echo implode('</li><li>', $this->errors); ?></li></ul>
				</div>
<?php
	}

	if (isset($this->authentication) AND $this->authentication)
	{
?>
				<div id="jsn-installer-login">
					<h2><?php echo JText::_('JSN_EXTFW_INSTALLER_LOGIN_TITLE'); ?></h2>
					<p><?php echo JText::_('JSN_EXTFW_INSTALLER_LOGIN_MESSAGE'); ?></p>
					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="inline" for="username"><?php echo JText::_('JGLOBAL_USERNAME'); ?>:</label>
								<input type="text" name="customer_username" id="jsn-customer-username" class="input-xlarge" value="" />
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<label class="inline" for="password"><?php echo JText::_('JGLOBAL_PASSWORD'); ?>:</label>
								<input type="password" name="customer_password" id="jsn-customer-password" class="input-xlarge" value="" />
							</div>
						</div>
					</div>
					<hr />
					<div class="form-actions">
						<button class="btn btn-primary"><?php echo JText::_('JLOGIN'); ?></button>
					</div>
				</div>
<?php
	}
?>
				<ul id="jsn-installer-list">
<?php
	foreach ($this->dependencies AS $dependency)
	{
		if ( ! isset($dependency->upToDate) AND isset($dependency->identified_name))
		{
			$qs = array();

			foreach ((array) $dependency AS $k => $v) {
				$qs[] = "{$k}=" . str_replace(' ', '+', $v);
			}
?>
					<li ref="<?php echo implode('&', $qs); ?>">
						<?php echo JText::_($dependency->title); ?>
						<ul>
							<li class="jsn-installer-downloading">
								<?php echo JText::_('JSN_EXTFW_INSTALLER_DOWNLOADING'); ?>
								<span class="jsn-installer-status jsn-icon16 jsn-icon-loading"></span>
								<span class="jsn-installer-processing"></span>
								<p class="jsn-installer-error"></p>
							</li>
							<li class="jsn-installer-installing">
								<?php echo JText::_('JSN_EXTFW_INSTALLER_INSTALLING'); ?>
								<span class="jsn-installer-status jsn-icon16 jsn-icon-loading"></span>
								<span class="jsn-installer-processing"></span>
								<p class="jsn-installer-error"></p>
							</li>
						</ul>
					</li>
<?php
		}
	}
?>
				</ul>
				<br />
				<div id="jsn-installer-manual-download">
					<p><?php echo JText::_('JSN_EXTFW_INSTALLER_MANUAL_DOWNLOAD'); ?></p>
					<ol>
						<li><a href="javascript:void(0)"><?php echo JText::_('JSN_EXTFW_INSTALLER_MANUAL_DOWNLOAD_STEP_1'); ?></a></li>
						<li id="jsn-installer-package-selector"><?php echo JText::_('JSN_EXTFW_INSTALLER_MANUAL_DOWNLOAD_STEP_2'); ?>: <input type="file" name="package" size="40" /></li>
						<li><button class="btn"><?php echo JText::_('JSN_EXTFW_INSTALLER_INSTALLING'); ?></button></li>
					</ol>
				</div>
			</div>
<?php
}
else
{
?>
			<h1><?php echo JText::_('JSN_EXTFW_INSTALLER_NO_MISSING_DEPENDENCY'); ?></h1>
<?php
}
?>
			<div id="jsn-installer-finalization">
				<div class="control-group">
					<label class="checkbox" for="jsn-installer-live-update-notification">
						<input type="checkbox" value="1" name="live_update_notification" id="jsn-installer-live-update-notification" />
						<strong><?php echo JText::sprintf('JSN_EXTFW_INSTALLER_LIVE_UPDATE_NOTIFICATION_LABEL', preg_replace('/^JSN\s*/i', '', JText::_((string) $this->xml->name))); ?></strong>
					</label>
					<p><?php echo str_replace('__PRODUCT__', JText::_((string) $this->xml->name), JText::_('JSN_EXTFW_INSTALLER_LIVE_UPDATE_NOTIFICATION_DESC')); ?></p>
				</div>
				<div class="button-holder">
					<button class="btn btn-primary" type="submit">
						<?php echo JText::_('JSN_EXTFW_INSTALLER_CLICK_TO_FINALIZE'); ?>
					</button>
				</div>
				<div class="clr"></div>
			</div>

			<input type="hidden" name="option" value="<?php echo $this->input->getCmd('option'); ?>" />
			<input type="hidden" name="view" value="<?php echo $this->input->getCmd('view'); ?>" />
			<input type="hidden" name="task" value="" />
		</form>
		<script src="<?php echo $this->script; ?>" type="text/javascript"></script>
	</div>
</div>

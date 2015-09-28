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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/helpers/html');
JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_modules/helpers/html');
$user   = JFactory::getUser();
$userId = $user->get('id');
include_once(JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/extensions.php');
$config = JSNConfigHelper::get('com_pagebuilder');

$extSupport = array('com_content', 'com_modules', 'com_advancedmodules');
if (isset($config->extension_support))
{
	if ($config->extension_support != '')
	{
		$extSupport = json_decode($config->extension_support);
	}
}
if (is_dir(JPATH_ADMINISTRATOR . '/components/com_advancedmodules'))
{
	$com_modules = 'com_advancedmodules';
}
else
{
	$com_modules = 'com_modules';
}

?>
<?php if (!count($extSupport))
{ ?>

	<div class="jsn-bootstrap3">
		<div class="pb-element-container">
			<p class="jsn-bglabel"><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_MSG_ENABLE_EXT'); ?></p>

			<div style="text-align: center">
				<a href="index.php?option=com_pagebuilder&view=configuration" target=""
				   class="btn-primary btn-large btn"> <?php echo JText::_('JSN_PAGEBUILDER_BUILDER_SETTING_CONFIG'); ?> </a>
			</div>
		</div>
	</div>

	<?php return false;
} ?>

<div class="jsn-page-manager">
	<div
	="clear:both"></div>
<h2 class="jsn-section-header"><?php echo JText::_('COM_PAGEBUILDER_PAGE_MANAGER');?></h2>
<div id="jsn-item-list" class="jsn-page-list">

	<div class="tabbable " id="content_manager">
		<ul class="nav nav-tabs">

			<?php if (in_array('com_content', $extSupport))
			{ ?>
				<li class="item active" id="com_content">
					<a href="#pane_com_content" data-toggle="tab">
						<?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_CONTENT_ARTICLES'); ?>
					</a>
				</li>
			<?php } ?>
			<?php if (in_array($com_modules, $extSupport))
			{ ?>
				<li class="item" id="com_modules">
					<a href="#pane_com_modules" data-toggle="tab">
						<?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_MODULES'); ?>
					</a>
				</li>
			<?php } ?>
			<?php
			if (is_dir(JPATH_ADMINISTRATOR . '/components/com_k2'))
			{
				if (in_array('com_k2', $extSupport))
				{
					?>
					<li class="item" id="com_k2">
						<a href="#pane_com_k2" data-toggle="tab">
							<?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_COMPONENT_K2');?>
						</a>
					</li>
				<?php }
			} ?>
			<?php
			if (is_dir(JPATH_ADMINISTRATOR . '/components/com_easyblog'))
			{
				if (in_array('com_easyblog', $extSupport))
				{
					?>
					<li class="item" id="com_easyblog">
						<a href="#pane_com_easyblog" data-toggle="tab">
							<?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_COMPONENT_EASYBLOG');?>
						</a>
					</li>
				<?php }
			} ?>
		</ul>

		<div class="tab-content">
			<?php if (in_array('com_content', $extSupport))
			{ ?>
				<div id="pane_com_content" class="tab-pane active " style="">
					<div class="">
						<div class="pull-right">
							<a href="index.php?option=com_content&task=article.add&action=pbnew"
							   class="btn btn-success"> <i class="icon-plus"></i> <?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_NEW'); ?></a>

							<div style="clear:both"></div>
						</div>
					</div>

					<div class="span12">
						<fieldset class="abc">
							<table class="table table-striped" id="articleList" style="border: 1px solid #cccccc;">
								<thead>
								<th width="1%" class="nowrap center hidden-phone">
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_ID') ?></strong>
								</th>
								<th>
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_CONTENT_TITLE') ?></strong>

								</th>
								<th>
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_CATEGORY') ?></strong>

								</th>
								<th width="1%" style="min-width:55px" class="nowrap center">
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_STATUS') ?></strong>
								</th>
								</thead>
								<tbody>
								<?php foreach ($this->articles as &$article) :
									$canChange = $user->authorise('core.edit.state', 'com_content.article.' . $article->id) && @$canCheckin;

									?>
									<?php
									$link   = 'index.php?option=com_content&task=article.edit&id=' . $article->id;
									$string = $this->escape($article->title);
									$title  = JHTML::_('string.truncate', $string, 30);
									?>
									<tr class="row<?php echo $article->id; ?>">
										<td class="center">
											<?php echo $this->escape($article->id); ?>
										</td>
										<td>
											<a href="<?php echo $link;?>"> <?php echo $this->escape($article->title); ?></a>
										</td>
										<td>
											<?php echo $this->escape($article->category); ?></a>
										</td>
										<td class="center">
											<?php echo JHtml::_('jgrid.published', $article->state, $article, 'articles.', $canChange, 'cb', $article->publish_up, $article->publish_down); ?>
										</td>

									</tr>
								<?php endforeach; ?>
								</tbody>
							</table>

						</fieldset>
					</div>

				</div>
			<?php } ?>
			<?php if (in_array($com_modules, $extSupport))
			{ ?>
				<div id="pane_com_modules" class="tab-pane  " style="">

					<div class="">
						<div class="pull-right">
							<a href="index.php?option=com_modules&view=select" class="btn btn-success"> <i
									class="icon-plus"></i> <?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_NEW'); ?></a>

							<div style="clear:both"></div>
						</div>
					</div>
					<div class="span12">
						<fieldset class="abc">
							<table class="table table-striped" id="articleList" style="border: 1px solid #cccccc;">
								<thead>
								<th width="1%" class="nowrap center hidden-phone">
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_ID') ?></strong>
								</th>
								<th>
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_CONTENT_TITLE') ?></strong>

								</th>
								<th>
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_POSITION') ?></strong>

								</th>
								<th width="1%" style="min-width:55px" class="nowrap center">
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_STATUS') ?></strong>
								</th>
								</thead>
								<tbody>
								<?php foreach ($this->modules as &$module) : ?>
									<?php
									$link   = 'index.php?option=' . $com_modules . '&task=module.edit&id=' . $module->id;
									$string = $this->escape($module->title);
									$title  = JHTML::_('string.truncate', $string, 30);
									?>
									<tr class="row<?php echo $module->id; ?>">
										<td>
											<?php echo $this->escape($module->id); ?>
										</td>
										<td>
											<a href="<?php echo $link; ?>"> <?php echo $this->escape($module->title); ?></a>
										</td>
										<td>
											<?php echo $this->escape($module->position); ?>
										</td>
										<td>
											<?php echo JHtml::_('modules.state', $module->published, $module, $canChange, 'cb'); ?>
										</td>
									</tr>
								<?php endforeach; ?>
								</tbody>
							</table>

						</fieldset>
					</div>

				</div>
			<?php } ?>
			<?php if (in_array('com_k2', $extSupport))
			{ ?>
				<div id="pane_com_k2" class="tab-pane" style="">

					<div class="">
						<div class="pull-right">
							<a href="index.php?option=com_k2&view=item" class="btn btn-success"> <i
									class="icon-plus"></i> <?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_NEW'); ?></a>

							<div style="clear:both"></div>
						</div>
					</div>
					<div class="span12">
						<fieldset class="abc">
							<table class="table table-striped" id="articleList" style="border: 1px solid #cccccc;">
								<thead>
								<th width="1%" class="nowrap center hidden-phone">
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_ID') ?></strong>
								</th>
								<th>
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_CONTENT_TITLE') ?></strong>

								</th>
								<th>
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_CATEGORY') ?></strong>

								</th>
								<th width="1%" style="min-width:55px" class="nowrap center">
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_STATUS') ?></strong>
								</th>
								</thead>
								<tbody>
								<?php
								if (JSNPagebuilderHelpersExtensions::enableExt('k2', 'pagebuilder', true) == true)
								{
									foreach ($this->k2 as &$k2) :
										$canChange = $user->authorise('core.edit.state', 'com_content.article.' . $k2->id) && @$canCheckin;

										?>
										<?php
										$link   = 'index.php?option=com_k2&view=item&cid=' . $k2->id;
										$string = $this->escape($k2->title);
										$title  = JHTML::_('string.truncate', $string, 30);
										?>
										<tr class="row<?php echo $k2->id; ?>">
											<td class="center">
												<?php echo $this->escape($k2->id); ?>
											</td>
											<td>
												<a href="<?php echo $link;?>"> <?php echo $this->escape($k2->title); ?></a>
											</td>
											<td>
												<?php echo $this->escape($k2->category); ?></a>
											</td>
											<td class="center">
												<?php echo JHtml::_('jgrid.published', $k2->published, $k2, 'articles.', $canChange, 'cb', $k2->publish_up, $k2->publish_down); ?>
											</td>

										</tr>
									<?php
									endforeach;
								}
								?>
								</tbody>
							</table>

						</fieldset>
					</div>

				</div>
			<?php } ?>
			<?php if (in_array('com_easyblog', $extSupport))
			{ ?>
				<div id="pane_com_easyblog" class="tab-pane" style="">

					<div class="">
						<div class="pull-right">
							<a href="index.php?option=com_easyblog&view=blog" class="btn btn-success"> <i
									class="icon-plus"></i> <?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_NEW'); ?></a>

							<div style="clear:both"></div>
						</div>
					</div>
					<div class="span12">
						<fieldset class="abc">
							<table class="table table-striped" id="articleList" style="border: 1px solid #cccccc;">
								<thead>
								<th width="1%" class="nowrap center hidden-phone">
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_ID') ?></strong>
								</th>
								<th>
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_CONTENT_TITLE') ?></strong>

								</th>
								<th>
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_CATEGORY') ?></strong>

								</th>
								<th width="1%" style="min-width:55px" class="nowrap center">
									<strong><?php echo JText::_('JSN_PAGEBUILDER_BUILDER_MANAGER_STATUS') ?></strong>
								</th>
								</thead>
								<tbody>
								<?php
								if (JSNPagebuilderHelpersExtensions::enableExt('easyBlog', 'pagebuilder', true) == true)
								{
									foreach ($this->easyBlog as &$easyBlog) :
										$canChange = $user->authorise('core.edit.state', 'com_content.article.' . $easyBlog->id) && @$canCheckin;

										?>
										<?php
										$link   = 'index.php?option=com_easyblog&view=blog&blogid=' . $easyBlog->id;
										$string = $this->escape($easyBlog->title);
										$title  = JHTML::_('string.truncate', $string, 30);
										?>
										<tr class="row<?php echo $easyBlog->id; ?>">
											<td class="center">
												<?php echo $this->escape($easyBlog->id); ?>
											</td>
											<td>
												<a href="<?php echo $link; ?>"> <?php echo $this->escape($easyBlog->title); ?></a>
											</td>
											<td>
												<?php echo $this->escape($easyBlog->category); ?></a>
											</td>
											<td class="center">
												<?php echo JHtml::_('jgrid.published', $easyBlog->published, $easyBlog, 'articles.', $canChange, 'cb', $easyBlog->publish_up, $easyBlog->publish_down); ?>
											</td>

										</tr>
									<?php
									endforeach;
								}
								?>
								</tbody>
							</table>

						</fieldset>
					</div>

				</div>
			<?php } ?>
		</div>

	</div>
</div>

</div>
<div>
	<?php
	$products = JSNPagebuilderHelpersExtensions::getDependentExtensions();

	JSNHtmlGenerate::footer($products);
	?>
</div>
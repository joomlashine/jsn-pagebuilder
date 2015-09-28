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

// No direct access to this file.
defined('_JEXEC') || die('Restricted access');

$content	= isset($this->content) ? $this->content : '';
$params		= isset($this->params) ? $this->params : '';

$submodal   = ! empty( $submodal ) ? 'submodal_frame' : '';
$showPreview = $this->showPreview;
?>
<?php if($showPreview) echo '<div id="jsn_column1">';?>
	<div class="jsn-bootstrap" id="form-container">
		<div id="modalOptions" class="form-horizontal modalOptions <?php echo $submodal; ?>">
			<?php
				echo $content;
			?>
			<div id="modalAction"></div>
		</div>
		<textarea class="hidden" id="shortcode_content" name="shortcode_content"><?php echo $params; ?></textarea>
		<textarea class="hidden" id="pb_share_data"  ></textarea>
		<textarea class="hidden" id="pb_merge_data"  ></textarea>
		<textarea class="hidden" id="pb_extract_data"  ></textarea>
		<input type="hidden" id="pb_previewing" value="0" />
		<input id="shortcode_name" name="shortcode_name" type="hidden" value="<?php echo $this->shortcodeName?>" />
	</div>
<?php if($showPreview) echo '</div>';?>
<?php if($showPreview){?>
<div id="jsn_column2">
	<div class="preview_title"><?php echo JText::_("JSN_PAGEBUILDER_CONTROLLERS_SHORTCODE_PREVIEW");?></div>
	<div id="framePreview" class="preview_border">
		<div id="iframeLoading" class="iframe_loading_border"><div class="iframe_loading_image"><img src="components/com_pagebuilder/assets/images/icons-32/ajax-loader.gif"></div></div>
		<div class="control-group">
			<div id="preview_container">
				<iframe id="shortcode_preview_iframe" scrolling="auto" name="shortcode_preview_iframe" class="shortcode_preview_iframe" width="100%"></iframe>
				<div id="preview"></div>
			</div>
		</div>
	</div>
</div>
<?php }?>

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

$custom_css_item = '<li class="jsn-item ui-state-default"><label class="checkbox"><input type="checkbox" name="item-list" value="VALUE" CHECKED>VALUE</label></li>';

if ( empty ( $_GET['id'] ) ) {
    exit;
}
if($_POST){
    die();
}
$content_id = $_GET['id'];

// get custom css data
$custom_css_data = JSNPagebuilderHelpersFunctions::custom_css_data( isset ( $content_id ) ? $content_id : NULL );
$css_files  = ! empty( $custom_css_data['css_files'] ) ?  $custom_css_data['css_files']  : '';
$css_custom = ! empty( $custom_css_data['css_custom'] ) ?  $custom_css_data['css_custom'] : '';
$_css_files_tooltip = 'Insert path to your CSS files, each line for each file.
						<br>The path can be relative like:
						<br> <i><u>assets/css/yourfile.css</u></i>
						<br>or absolute like:
						<br> <i><u>http://yourwebsite.com/assets/css/yourfile.css</u></i>
						';

JSNHtmlAsset::addScript('http://code.jquery.com/jquery-2.1.0.min.js');
JSNHtmlAsset::addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/jquery-ui/js/jquery-ui-1.10.3.custom.js');
JSNHtmlAsset::addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/jquery-livequery/jquery.livequery.min.js');
JSNHtmlAsset::addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . 'js/joomlashine.noconflict.js');
JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-modal.js');
JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/js/custom_css.js');
//JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/3rd-party/codemirror/codemirror.js');
JSNHtmlAsset::addStyle(JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/pagebuilder.css');
//JSNHtmlAsset::addStyle(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/codemirror/codemirror.css');
$document = JFactory::getDocument();
$script = '
			var JSNPbParams	= JSNPbParams || {};
		';
$document->addScriptDeclaration( $script, 'text/javascript' );
$_style    = '.tooltip-inner { min-width: 550px !important; font-weight: 100 !important; }';
JSNHtmlAsset::addInlineStyle($_style,'text/css');
?>
<div class="jsn-master" id="pb-custom-css-box">
    <div class="jsn-bootstrap3">

        <!-- CSS files -->
        <div
            class="form-group control-group jsn-items-list-container modal-content">
            <label for="option-items-itemlist" class="control-label top-cut"><?php echo JText::_( 'JSN_PAGEBUILDER_BUILDER_CSS_FILE' ); ?><i
                    class=" icon-question-sign tooltip-toggle" data-html="true"
                    data-original-title="<?php  $_css_files_tooltip ; ?>"></i> </label>
            <div class="controls">
                <div class="jsn-buttonbar">
                    <button id="items-list-edit" class="btn btn-default btn-sm">
                        <i class="icon-pencil"></i>
                        <?php echo JText::_( 'JSN_PAGEBUILDER_BUILDER_CSS_EDIT' ); ?>
                    </button>
                    <button id="items-list-save"
                            class="btn btn-default btn-sm btn-primary hidden">
                        <i class="icon-ok"></i>
                        <?php echo JText::_( 'JSN_PAGEBUILDER_BUILDER_CSS_DONE' ); ?>
                    </button>
                </div>
                <ul class="jsn-items-list ui-sortable css-files-container">
                    <?php
                    if ( ! empty( $css_files ) ) {
                        $css_files = json_decode( $css_files );
                        $data      = $css_files->data;
                        foreach ( $data as $file_info ) {
                            $checked = $file_info->checked;
                            $url     = $file_info->url;

                            $item = str_replace( 'VALUE', $url, $custom_css_item );
                            $item = str_replace( 'CHECKED', $checked ? 'checked' : '', $item );
                            echo $item ;
                        }
                    }
                    ?>
                </ul>
                <div class="items-list-edit-content hidden">
                    <textarea class="col-xs-12" rows="5" style="width: 99%; height: 120px;"></textarea>
                </div>
            </div>
        </div>

        <!-- Custom CSS code -->
        <div class="control-group jsn-items-list-container modal-content">
            <label for="option-items-itemlist" class="control-label"><?php echo JText::_( 'JSN_PAGEBUILDER_BUILDER_CSS_CODE' ); ?><i
                    class="icon-question-sign tooltip-toggle" data-html="true"
                    data-original-title="<?php JText::_( 'JSN_PAGEBUILDER_BUILDER_CSS_CODE_DES' ); ?>"></i>
            </label>

            <div class="controls">
                <textarea id="custom-css" class="input-sm css-code" rows="10"><?php echo $css_custom; ?></textarea>
            </div>
        </div>

    </div>
</div>

<style>
    body {overflow: hidden;}
</style>
<script type='text/html' id='tmpl-custom-css-item'>
    <?php echo $custom_css_item;?>
</script>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $('.tooltip-toggle').each(function () {
                $(this).on('mouseover', function () {
                    $(this).tooltip('toggle');
                });
                $(this).tooltip({
                    placement: 'bottom'
                });
            });
        });
    })(jQuery);
</script>
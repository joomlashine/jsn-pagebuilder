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
(function ($){
    $(function(){
        $('#jsnconfig-extension-support-field .sortable').sortable({
            axis: 'y',
            items: '.item',
            placeholder: 'ui-state-highlight',
            helper: function(event, ui){
                return ui;
            },
            handle: '.sortable-handle',
            stop: function(event, ui){
                var trackButtons = $('.form-actions button[track-change="yes"]');
                trackButtons.removeAttr('disabled');
                ui.item.show();
                var values = [];
                $('input[type="checkbox"][name*=extension_support]').each(function(){
                    values.push($(this).val());
                });
                $('#params_extension_support_order').val(values.join(','));
            }
        });
    });

})(JoomlaShine.jQuery);
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

//include_once JSNPB_ADMIN_ROOT . '/helpers/builder.php';
//error_reporting(0);

/**
 * Controller for process builder JSN Pagebuilder
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPagebuilderControllerBuilder extends JSNBaseController
{
	/**
	 * Generate html for PageBuilder layout.
	 * 
	 * @return string
	 */
	public function html()
	{
		$helper				= new JSNPagebuilderHelpersBuilder();
		JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/js/elements-lang.js');
		JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/js/handle.js');
		JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/js/shortcodesetting/settings-handler.js');
                // Genrate pagebuilder element template.
		$helper->generateElementColumnTemplate();
		$helper->generateElementRowTemplate();
		
		$source_content		= '';
		$data	= array();
		$source_content	= $_POST['form_data'];
			
		// Remove all p tags which auto added by the editor
		$source_content	= JSNPagebuilderHelpersShortcode::removeAutop($source_content);
		$source_content = html_entity_decode($source_content, ENT_COMPAT, 'UTF-8');
		$html[]		= '<link rel="stylesheet" href="'.JSNPB_ADMIN_URL . '/assets/css/jsn-element-font.css'.'" type="text/css" />';
        $html[]		= '<link rel="stylesheet" href="'.JSNPB_ADMIN_URL . '/assets/css/pb-layout-font.css'.'" type="text/css" />';
        $html[]		= '<div id="form-container" class="jsn-layout jsn-section-content">';
                
		if ($source_content) {
			//$shortcodeTags		= $helper->getShortcodeTags();		
			$html[]	= $helper->generateShortCode($source_content);			
		}else{
			$html[]	= $helper->getRowStructure();
		}
		
		$html[]	= '<a href="javascript:void(0);" id="jsn-add-container"
						class="jsn-add-more jsn-add-more-row"><i class="icon-plus"></i> Add Row
					</a>';
                
                /**
                 * Show thumbnail for default layouts
                 */
        $html[] = '<div class="row-fluid pb-layout-thumbs">';
        $layouts = JSNPBShortcodeRow::$layouts;

        foreach ( $layouts as $columns ) {
            $columns_name = implode( 'x', $columns );
            $icon_class = implode( '-', $columns );
            $data_columns = implode(',', $columns);
            $icon_class = 'pb-layout-' . $icon_class;
            $icon = '<i class="'.$icon_class.'"></i>';
            $html[] = '<div class="thumb-wrapper" data-columns="'.$data_columns.'" title="'.$columns_name.'">'.$icon.'</div>';

        }
        $html[] = '</div>';
		$html[] = JSNHtmlGenerate::footer(array(), false);
		$html[]	= '</div>';
		$html[]	= $helper->getAddShortcodesPopup();
        if(defined ("JSN_PAGEBUILDER_EDITION")){
            if(strtolower(JSN_PAGEBUILDER_EDITION) == "free"){

                if(file_exists(JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/articles.php')){
                    include_once (JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/articles.php');            
                    $pbTotal = JSNPagebuilderHelpersArticles::getCountArticleUsedPageBuilderFromPlugin();
              
                    if($pbTotal >= 5 && !JFactory::getApplication()->input->getInt('article_id') && JFactory::getApplication()->input->getInt('is_com_modules') != 1){
                        $html = array();
                        $html [] = '<div class="jsn-bootstrap3"><div class="pb-element-container"><p class="jsn-bglabel">You have reached 5 pages limit of using JSN PageBuilder.</p><p style="font-size: 20px;text-align: center;color: #d3d3d3;">Please  to upgrade <a target=\'_blank\' href=\''.JSN_PAGEBUILDER_INFO_LINK.'\'>Pro version</a> or remove your old pages that used JSN PageBuilder.</p><div style="text-align: center"><a href="index.php?option=com_pagebuilder&view=upgrade" target="_blank" class="btn-primary btn-large btn"> Upgrade </a></div></div></div>';
                    }
                }
            }
        }
		print_r(implode("\n", $html));
                exit();
	}

    /**
     * Save custom css information: file, code
     * @return void
     *
     **/

    public function save_css_custom(){
        $content_id = $_POST['content_id'];
        JSNPagebuilderHelpersFunctions::custom_css($content_id, 'css_files', 'put', $_POST['css_files']);
        JSNPagebuilderHelpersFunctions::custom_css($content_id, 'css_custom', 'put', $_POST['css_custom']);
        exit;
    }
        
       
}
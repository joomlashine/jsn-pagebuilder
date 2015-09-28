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

/**
 * Table Item shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeTableItem extends IG_Pb_Child {

	/**
	 * Constructor
	 *
	 * @return type
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Include admin scripts
	 * 
	 * @return type
	 */
	public function backend_element_assets() {
		$document = JFactory::getDocument();
		$document->addScript( JSNPB_ELEMENT_URL.'/table/assets/js/table-setting.js', 'text/javascript' );
	}
	
	/**
	 * DEFINE configuration information of shortcode
	 * 
	 * @return type
	 */
	public function element_config() {
		$this->config['shortcode'] = 'pb_table_item';
		$this->config['exception'] = array(
			'item_text'        => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TABLE_ITEM_TEXT' ),
			'data-modal-title' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TABLE_ITEM_LABEL' ),
			'item_wrapper'     => 'div',
			'action_btn'       => 'edit',
		);
	}

    /**
     * DEFINE setting options of shortcode in backend
     */
    public function backend_element_items()
    {
        $this->frontend_element_items();
    }

    /**
     * DEFINE setting options of shortcode in frontend
     */
    public function frontend_element_items()
    {
		
		//Disabled Row Span and Column Span temporary
		$this->items = array(
			'Notab' => array(
				array(
					'name' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TABLE_ITEM_WIDTH' ),
					'type' => array(
						array(
							'id'           => 'width_value',
							'type'         => 'text_number',
							'std'          => '',
							'class'        => 'input-mini',
							'validate'     => 'number',
							'parent_class' => 'combo-item merge-data',
						),
						array(
							'id'           => 'width_type',
							'type'         => 'select',
							'class'        => 'input-mini',
							'options'      => array( '%' => '%', 'px' => 'px' ),
							'std'          => '%',
							'parent_class' => 'combo-item merge-data',
						),
					),
					'container_class' => 'combo-group',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TABLE_ITEM_WIDTH_DES' ),
				),
				array(
					'name'            => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TABLE_ITEM_TAG_NAME' ),
					'id'              => 'tagname',
					'type'            => 'text_field',
					'std'             => 'td',
					'input-type'      => 'hidden',
					'container_class' => 'hidden',
				),
				array(
					'name'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TABLE_ITEM_ROW_SPAN' ),
					'id'       => 'rowspan',
					'type'     => 'text_number',
					'std'      => '1',
					'class'    => 'input-mini positive-val',
					'input-type'      => 'hidden',
					'validate' => 'number',
					'role'     => 'extract',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TABLE_ITEM_ROW_SPAN_DES' ),
                    'container_class' => 'hidden',
				),
				array(
					'name'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TABLE_ITEM_COLUMN_SPAN' ),
					'id'       => 'colspan',
					'type'     => 'text_number',
					'std'      => '1',
					'class'    => 'input-mini positive-val',
					'input-type'      => 'hidden',
					'validate' => 'number',
					'role'     => 'extract',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TABLE_ITEM_COLUMN_SPAN_DES' ),
                    'container_class' => 'hidden',
				),
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TABLE_ITEM_ROW_STYLE' ),
					'id'      => 'rowstyle',
					'type'    => 'select',
					'std'     => JSNPagebuilderHelpersType::getFirstOption( JSNPagebuilderHelpersType::getTableRowColor() ),
					'options' => JSNPagebuilderHelpersType::getTableRowColor(),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TABLE_ITEM_ROW_STYLE_DES' )
				),
				array(
					'name'   => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TABLE_ITEM_CONTENT' ),
					'id'     => 'cell_content',
					'role'   => 'content',
					'role_2' => 'title',
					'type'   => 'tiny_mce',
					'std'    => '',
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_TABLE_ITEM_CONTENT_DES' )
				),
			)
		);
	}

	/**
	 * DEFINE shortcode content
	 *
	 * @param type $atts
	 * @param type $content
	 */
	public function element_shortcode( $atts = null, $content = null ) {
		extract( JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts ) );
		$content = JSNPagebuilderHelpersShortcode::removeAutop( $content );
		$rowstyle       = ( ! $rowstyle || strtolower( $rowstyle ) == 'default' ) ? '' : $rowstyle;
		if ( in_array( $tagname, array( 'tr_start', 'tr_end' ) ) ) {
			return "$tagname<!--seperate-->";
		}
		$width = ! empty( $width_value ) ? "width='$width_value$width_type'" : '';
		$empty = empty( $content ) ? '<!--empty-->' : '';
		return "<CELL_WRAPPER class='$rowstyle' rowspan='$rowspan' colspan='$colspan' $width>" . JSNPagebuilderHelpersFunctions::add_absolute_path_to_image_url($content) . "</CELL_WRAPPER>$empty<!--seperate-->";
	}

}

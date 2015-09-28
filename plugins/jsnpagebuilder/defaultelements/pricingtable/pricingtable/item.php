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

	class JSNPBShortcodePricingtableItemItem extends IG_Pb_Child {

		/**
		 * element constructor
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * element config
		 * @see    WR_Element::element_config()
		 * @access public
		 * @return void
		 */
		public function element_config() {
			$this->config['shortcode'] = 'pb_pricingtable_item_item';
			$this->config['exception'] = array(
				'item_text'                 => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_ITEM'),
				'data-modal-title'          => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_ITEM'),
				'disable_preview_container' => '1',
				'action_btn'                => 'edit',
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
			//$random_id = WR_Pb_Utils_Common::random_string( 8, true );
			$this->items = array(
				'Notab' => array(
					array(
						'id'              => 'prtbl_item_attr_id',
						'type'            => 'text_field',
						'std'             => '__default_id__',
						'input-type'      => 'hidden',
						'container_class' => 'hidden',
					),
					array(
						'name'            => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_TITLE'),
						'id'              => 'prtbl_item_attr_title',
						'type'            => 'text_field',
						'class'           => 'jsn-input-xxlarge-fluid',
						'std'             => '',
						'container_class' => 'hidden',
						'tooltip'         => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_TITLE')
					),
					array(
						'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_ITEM_VALUE'),
						'id'      => 'prtbl_item_attr_value',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'role'    => 'title',
						'std'     => '',
						'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_ITEM_VALUE')
					),
					array(
						'name'    => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_DESCRIPTION'),
						'id'      => 'prtbl_item_attr_desc',
						'type'    => 'text_area',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => '',
						'tooltip' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_DESCRIPTION'),
					),
					array(
						'name'            => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_ITEM_TYPE'),
						'id'              => 'prtbl_item_attr_type',
						'type'            => 'select',
						'std'             => '',
						'class'           => 'input-sm',
						'options'         => JSNPagebuilderHelpersType::getSubItemPricingType(),
						'container_class' => 'hidden',
						'tooltip'         => JText::_( 'JSN_PAGEBUILDER_ELEMENT_PRICINGTABLE_ITEM_TYPE')
					),
				)
			);
		}

		/**
		 * element shortcode
		 *
		 * @see    WR_Element::element_shortcode( $atts, $content )
		 * @access public
		 * @return string
		 */
		public function element_shortcode( $atts = null, $content = null ) {
			$arr_params = ( JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts ) );
			extract( $arr_params );

			if ( is_array( @$pricingtable_attrs ) ) {
				if ( isset( $arr_params['prtbl_item_attr_id'] ) && in_array( $arr_params['prtbl_item_attr_id'], $pricingtable_attrs ) ) {
					return '';
				}
			}

			switch( $prtbl_item_attr_type ) {
				case 'text':
					$html_element = '<li><label data-original-title="' . $prtbl_item_attr_desc . '" class="pb-prtbl-tipsy">' . $prtbl_item_attr_value . '</label></li>';
					break;
				case 'checkbox':
					$html_element = ( $prtbl_item_attr_value == 'yes' ) ? '<li><i class="icon-checkmark"></i></li>' : '<li></li>';
					break;
			}

			return $html_element;
		}

		/**
		 * Filter Content when output HTML for shortcode inside PageBuilder Admin
		 *
		 * @param string $content
		 *
		 * @return string
		 */
		public function content_in_pagebuilder( $content, $shortcode_data, $shortcode ) {
			if ( $shortcode == $this->config['shortcode'] ) {
				$params = shortcode_parse_atts( $shortcode_data );
				if ( isset ( $params['prtbl_item_attr_type'] ) && $params['prtbl_item_attr_type'] == 'checkbox' ) {
					$check_value = isset( $params['prtbl_item_attr_value'] ) ? $params['prtbl_item_attr_value'] : 'no';
					$option                              = array(
						'id'      => 'prtbl_item_attr_type_' . $params['prtbl_item_attr_id'],
						'type'    => 'radio',
						'std'     => $check_value,
						'options' => array( 'yes' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_YES'), 'no' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_NO') ),
						'parent_class'   => 'no-hover-subitem prtbl_item_attr_type'
					);
					$content = WR_Pb_Helper_Shortcode::render_parameter( 'radio', $option );
				}

				if ( $content == '(Untitled)' ) {
					$content = '';
				}
			}

			return $content;
		}

		/**
		 * Filter button in HTML output of shortcode inside PageBuilder Admin
		 *
		 * @param string $button
		 *
		 * @return string
		 */
		public function button_in_pagebuilder( $buttons, $shortcode_data, $shortcode ) {
			if ( $shortcode == $this->config['shortcode'] ) {
				$params = shortcode_parse_atts( $shortcode_data );
				if ( isset ( $params['prtbl_item_attr_type'] ) && $params['prtbl_item_attr_type'] == 'checkbox' ) {
					$buttons = '';
				}
			}

			return $buttons;

		}

		public function filter_edit_btn_class( $class, $shortcode ) {
			return ($shortcode == $this->config['shortcode']) ? 'element-edit-ct' : $class;
		}

	}


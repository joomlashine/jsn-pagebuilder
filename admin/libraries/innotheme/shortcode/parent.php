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
/*
 * Parent class for parent elements
 */

class IG_Pb_Parent extends IG_Pb_Element {
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * get params & structure of shortcode
	 * OVERWRIGE parent function
	 */
	public function shortcode_data() {
		$params                 = JSNPagebuilderHelpersShortcode::generateShortcodeParams( $this->items );

		$this->config['params'] = array_merge( array( 'div_margin_top' => '', 'div_margin_bottom' => '', 'disabled_el' => 'no', 'css_suffix' => '' ), $params );

		// get content of sub-shortcode
		$sub_items_content = array();
		$sub_items         = isset($this->config['params']['sub_sc_content']) ? $this->config['params']['sub_sc_content'] : array();

		foreach ( $sub_items as $sub_item_type => &$sub_shortcodes ) {
			foreach ( $sub_shortcodes as $sub_shortcode ) {

				$sub_sc = new $sub_item_type();
//				$sub_sc->init_element();
				// empty std
				if ( empty( $sub_shortcode['std'] ) ) {

					// only empty 'std'
					if ( count( $sub_shortcode ) == 1 ) {
						// get default shortcode structure of sub-shortcode
						$sub_sc->config['params'] = JSNPagebuilderHelpersShortcode::generateShortcodeParams( $sub_sc->items, null, null, false, true );

						// re-generate shortcode structure
						$sub_shortcode['std'] = JSNPagebuilderHelpersShortcode::generateShortcodeStructure( $sub_sc->config['shortcode'], $sub_sc->config['params'] );
					} // array of empty 'std' & pre-defined std for other items
					else {
						// MODIFY $instance->items
						JSNPagebuilderHelpersShortcode::generateShortcodeParams( $sub_sc->items, NULL, $sub_shortcode, TRUE );

						// re-generate shortcode structure
						$sub_sc->shortcode_data();

						// get updated std of sub-shortcode
						$sub_shortcode['std'] = $sub_sc->config['shortcode_structure'];

					}
				} // std is set
				else {

					// if std of sub-shortcode is predefined ( such as GoogleMap )
					$params         = stripslashes( $sub_shortcode['std'] );
					$extract_params = JSNPagebuilderHelpersShortcode::generateShortcodeParams( urldecode( $params ) );

					// MODIFY $instance->items
					JSNPagebuilderHelpersShortcode::generateShortcodeParams( $sub_sc->items, NULL, $extract_params, TRUE );

					// re-generate shortcode structure
					$sub_sc->shortcode_data();
				}

				$sub_items_content[] = $sub_shortcode['std'];
			}
		}

		$sub_items_content = implode( '', $sub_items_content );
		// END get content of sub-shortcode

		$this->config['shortcode_structure'] = JSNPagebuilderHelpersShortcode::generateShortcodeStructure( $this->config['shortcode'], $this->config['params'], $sub_items_content );

	}

	/**
	 * Method to call neccessary functions for initialyzing the backend
	 */
	public function init_element()
	{
		$this->element_items();
		$this->element_items_extra();
		$this->shortcode_data();

//		do_action( 'wr_pb_element_init' );

		parent::__construct();

		// enqueue assets for current element in backend (modal setting iframe)
//		if ( WR_Pb_Helper_Functions::is_modal_of_element( $this->config['shortcode'] ) ) {
//			add_action( 'pb_admin_enqueue_scripts', array( &$this, 'enqueue_assets_modal' ) );
//		}

		// enqueue assets for current element in backend (preview iframe)
//		if ( WR_Pb_Helper_Functions::is_preview() ) {
//			add_action( 'pb_admin_enqueue_scripts', array( &$this, 'enqueue_assets_frontend' ) );
//		}
	}

}
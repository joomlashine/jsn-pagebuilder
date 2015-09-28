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

defined('_JEXEC') or die('Restricted access');

/**
 * SocialIcon Item shortcode element
 *
 * @package JSN_Pagebuilder
 * @since   1.0.7
 */

class JSNPBShortcodeSocialiconItem extends IG_Pb_Child
{
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
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-iconsocialselector.js', 'js' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-general.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/3rd-party/font-awesome/css/font-awesome.css', 'css' );
		JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ADMIN_URL . '/assets/css/jsn-fontawesome.css', 'css' );
	}

	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config() {
		$this->config['shortcode'] = 'pb_socialicon_item';
		$this->config['exception'] = array(
			'data-modal-title' => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_ITEM' )
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
		$this->items = array(
			'Notab' => array(
				array(
					'name'    => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_HEADING' ),
					'id'      => 'heading',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'role'    => 'title',
					'std'     => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_ITEM_STD' ),
					'tooltip' => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_HEADING_DES' )
				),
				array(
					'name'      => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_URL' ),
					'id'        => 'link_url',
					'type'      => 'text_field',
					'std'       => '',
					'tooltip'   => JText::_( 'JSN_PAGEBUILDER_DEFAULT_ELEMENT_URL_DES' )
				),
				array(
					'name'      => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON' ),
					'id'        => 'icon',
					'type'      => 'icons',
					'std'       => '',
					'role'      => 'title_prepend',
					'role_type' => 'icon',
					'tooltip'   => JText::_( 'JSN_PAGEBUILDER_ELEMENT_SOCIALICON_DES' )
				),

			)
		);
	}

	/**
	 * DEFINE shortcode content
	 *
	 * @param type $atts
	 * @param type $content
	 *
	 * @return string
	 */
	public function element_shortcode( $atts = null, $content = null ) {
		extract( JSNPagebuilderHelpersShortcode::shortcodeAtts( $this->config['params'], $atts ) );
		return "
		<li>
			<a href='{$link_url}' target='_blank'>
				<div class='pb-brand-icons {icon_size}' STYLE>
					<i class='$icon {icon_size}'></i>
				</div>
			</a>
		</li><!--seperate-->";
	}
}
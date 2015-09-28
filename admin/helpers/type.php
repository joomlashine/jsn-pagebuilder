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
 * Helper type
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPagebuilderHelpersType {

    const PB_HELPER_DIRECTION_LEFT = 'left';
    const PB_HELPER_DIRECTION_RIGHT = 'right';
    const PB_HELPER_DIRECTION_TOP = 'top';
    const PB_HELPER_DIRECTION_BOTTOM = 'bottom';

    const PB_HELPER_ANSWER_YES = 'yes';
    const PB_HELPER_ANSWER_NO = 'no';

	/**
	 * Google map type options
	 *
	 * @return array
	 */
	static function getGmapType() {
		return array(
			'HYBRID'    => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_HYBRID' ),
			'ROADMAP'   => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_ROADMAP' ),
			'SATELLITE' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_SATELLITE' ),
			'TERRAIN'   => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_TERRAIN' ),
		);
	}

	/**
	 * Zoom level options for google element
	 *
	 * @return array
	 */
	static function getZoomLevel() {
		return array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
			'7' => '7',
			'8' => '8',
			'9' => '9',
			'10' => '10',
			'11' => '11',
			'12' => '12',
			'13' => '13',
			'14' => '14',
		);
	}

	/**
	 * Container style options
	 *
	 * @return array
	 */
	static function getContainerStyle() {
		return array(
			'no-styling'    => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_NO_STYLING' ),
			'img-rounded'   => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_ROUNDED' ),
			'img-circle'    => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_CIRCLE' ),
			'img-thumbnail' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_THUMBNAIL' )
		);
	}

	/**
	 * Zoom level options for QRCode element
	 *
	 * @return array
	 */
	static function getQRContainerStyle() {
		return array(
			'no-styling'    => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_NO_STYLING' ),
			'img-thumbnail' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_THUMBNAIL' )
		);
	}

	/**
	 * Pricing table design options
	 *
	 * @return array
	 */
	static function getPRTBLDesignOptions() {
		return array(
			'table-style-one'	=> JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_DESIGN_OPTION_1' ),
			'table-style-two'	=> JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_DESIGN_OPTION_2' ),
		);
	}

	/**
	 * Table design options
	 *
	 * @return array
	 */
	static function getTableRowColor() {
		return array(
			'default' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_DEFAULT' ),
			'active'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_ROW_COLOR_ACTIVE' ),
			'success' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_ROW_COLOR_SUCCESS' ),
			'warning' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_ROW_COLOR_WARNING' ),
			'danger'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_ROW_COLOR_DANGER' ),
		);
	}

	/**
	 * Alert type options
	 *
	 * @return array
	 */
	static function getAlertType() {
		return array(
			'alert-warning' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_DEFAULT' ),
			'alert-success' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_SUCCESS' ),
			'alert-info'    => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_INFO' ),
			'alert-danger'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_DANGER' ),
		);
	}

	/**
	 * Progress bar color options
	 *
	 * @return array
	 */
	static function getProgressBarColor() {
		return array(
			'default'              => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_DEFAULT' ),
			'progress-bar-info'    => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_ROW_COLOR_INFO' ),
			'progress-bar-success' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_ROW_COLOR_SUCCESS' ),
			'progress-bar-warning' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_ROW_COLOR_WARNING' ),
			'progress-bar-danger'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_ROW_COLOR_DANGER' ),
		);
	}

	/**
	 * Progress bar style options
	 *
	 * @return array
	 */
	static function getProgressBarStyle() {
		return array(
			'multiple-bars' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_MULTIPLE_BARS' ),
			'stacked' 		=> JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_STACKED' ),
		);
	}

	/**
	 * Progress bar item options
	 *
	 * @return array
	 */
	static function getProgressBarItemStyle() {
		return array(
			'solid'   => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_SOLID' ),
			'striped' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_STRIPED' ),
		);
	}


	/**
	 * Static function to get button color Options
	 *
	 * @return array
	 */
	static function getButtonColor() {
		return array(
			'btn-default' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_DEFAULT' ),
			'btn-primary' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_BUTTON_COLOR_PRIMARY' ),
			'btn-info'    => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_BUTTON_COLOR_INFO' ),
			'btn-success' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_BUTTON_COLOR_SUCCESS' ),
			'btn-warning' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_BUTTON_COLOR_WARNING' ),
			'btn-danger'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_BUTTON_COLOR_DANGER' ),
			'btn-link'    => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_BUTTON_COLOR_LINK' )
		);
	}

	/**
	 * Button size options
	 *
	 * @return array
	 */
	static function getButtonSize() {
		return array(
			'default' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_DEFAULT' ),
			'btn-xs'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_BUTTON_SIZE_MINI' ),
			'btn-sm'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_BUTTON_SIZE_SMALL' ),
			'btn-lg'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_BUTTON_SIZE_LARGE' )
		);
	}

	/**
	 * "Open in" option for anchor
	 *
	 * @return array
	 */
	static function getOpenInOptions() {
		return array(
			'current_browser' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_OPENIN_OPTION_CURRENT_BROWSER' ),
			'new_browser' 	  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_OPENIN_OPTION_NEW_BROWSER' ),
			'lightbox' 		  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_OPENIN_OPTION_LIGHTBOX' ),
		);
	}

	/**
	 * Icon position for List shortcode
	 *
	 * @return array
	 */
	static function getIconPosition() {
		return array(
			'left'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_LEFT' ),
			'right' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_RIGHT' ),
            'center' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_CENTER' ),
		);
	}

	/**
	 * Position options
	 *
	 * @return array
	 */
	static function getFullPositions() {
		return array(
			self::PB_HELPER_DIRECTION_TOP => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_TOP' ),
			self::PB_HELPER_DIRECTION_BOTTOM => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_BOTTOM' ),
			self::PB_HELPER_DIRECTION_LEFT => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_LEFT' ),
			self::PB_HELPER_DIRECTION_RIGHT => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_RIGHT' ),
		);
	}

	/**
	 * Icon size options
	 *
	 * @return array
	 */
	static function getIconSizes() {
		return array(
			'16' => '16',
			'24' => '24',
			'32' => '32',
			'48' => '48',
			'64' => '64',
		);
	}

	/**
	 * Icon style for List shortcode
	 *
	 * @return array
	 */
	static function getIconBackground() {
		return array(
			'circle' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_CIRCLE' ),
			'square' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_SQUARE' )
		);
	}

	/**
	 * Font options
	 *
	 * @return array
	 */
	static function getFonts() {
		return array(
			'standard fonts' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_STANDARD_FONTS' ),
			'google fonts'   => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_GOOGLE_FONTS' )
		);
	}

	/**
	 * Text align options
	 *
	 * @return array
	 */
	static function getTextAlign() {
		return array(
			'inherit' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_INHERIT' ),
			'left'    => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_LEFT' ),
			'center'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_CENTER' ),
			'right'   => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_RIGHT' )
		);
	}

	/**
	 * Font size options
	 *
	 * @return array
	 */
	static function getFontSizeTypes() {
		return array(
			'px'   => 'px',
			'em'   => 'em',
			'inch' => 'inch',
		);
	}

	/**
	 * Border style options
	 *
	 * @return array
	 */
	static function getBorderStyles() {
		return array(
			'solid'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_SOLID' ),
			'dotted' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_DOTTED' ),
			'dashed' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_DASHED' ),
			'double' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_DOUBLE' ),
			'groove' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_GROOVE' ),
			'ridge'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_RIDGE' ),
			'inset'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_INSET' ),
			'outset' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_OUTSET' )
		);
	}

	/**
	 * Font style options
	 *
	 * @return array
	 */
	static function getFontStyles() {
		return array(
			'inherit' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_INHERIT' ),
			'italic'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_ITALIC' ),
			'normal'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_NORMAL' ),
			'bold'    => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_BOLD' )
		);
	}

	/**
	 * Dummy content
	 *
	 * @return type
	 */
	static function loremText( $wordcount = 50 ) {
		$lorem = new JSNPagebuilderHelpersLorem;
		$str   = $lorem->get_text( $wordcount, false );
		return ucfirst( ltrim( $str ) );
	}

	/**
	 * Link type options
	 *
	 * @return array
	 */
	static function getLinkTypes() {
		$arr = array(
			'no_link' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_NO_LINK' ),
			'url' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_URL' ),
		);
		return $arr;
	}

	/**
	 * Image link type options
	 *
	 * @return array
	 */
	static function getImageLinkTypes() {
		$imageLinkType                = array();
		$linkTypes                    = self::getLinkTypes();
		$imageLinkType                = array_slice( $linkTypes, 0, 1 );
		//$imageLinkType['large_image'] = JText::_( 'Large Image' );
		$imageLinkType                = array_merge( $imageLinkType, array_slice( $linkTypes, 1 ) );
		return $imageLinkType;
	}

	/**
	 * Get 1st option of array
	 *
	 * @param array $arr
	 *
	 * @return mixed
	 */
	static function getFirstOption( $arr ) {
		foreach ( $arr as $key => $value ) {
			if ( ! is_array( $key ) )
				return $key;
		}
	}

	/**
	 * Static function to get pricing type of sub items
	 *
	 * @return array
	 */
	static function getSubItemPricingType() {
		return array(
			'text' 		=> JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_FREE_TEXT' ),
			'checkbox' 	=> JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_YES_NO' )
		);
	}

	/**
	 * Image Size options
	 *
	 * @return array
	 */
	static function getImageSize() {
		return array(
			'thumbnail' => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_THUMBNAIL' ),
			'medium'    => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_MEDIUM' ),
			'large'		=> JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_LARGE' ),
			'fullsize'  => JText::_( 'JSN_PAGEBUILDER_HELPER_TYPE_FULLSIZE' )
		);
	}

    /**
     * Animation options
     *
     * @return array
     */
    static function getAnimation() {
        return array(
            '0'                 => JText::_('JSN_PAGEBUILDER_HELPER_TYPE_NONE'),
            'slide_from_top'    => JText::_('JSN_PAGEBUILDER_HELPER_TYPE_SLIDE_FROM_TOP'),
            'slide_from_right'  => JText::_('JSN_PAGEBUILDER_HELPER_TYPE_SLIDE_FROM_RIGHT'),
            'slide_from_bottom' => JText::_('JSN_PAGEBUILDER_HELPER_TYPE_SLIDE_FROM_BOTTOM'),
            'slide_from_left'   => JText::_('JSN_PAGEBUILDER_HELPER_TYPE_SLIDE_FROM_LEFT'),
            'fade_in'           => JText::_('JSN_PAGEBUILDER_HELPER_TYPE_FADE'),
        );
    }

    /**
     *
     */
    static function getYesNoQuestion() {
        return array(
            self::PB_HELPER_ANSWER_YES => JText::_('JSN_PAGEBUILDER_HELPER_BUILDER_YES'),
            self::PB_HELPER_ANSWER_NO  => JText::_('JSN_PAGEBUILDER_HELPER_BUILDER_NO'),
        );
    }
}
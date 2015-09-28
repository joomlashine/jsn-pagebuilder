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
 * Helper class for video element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbVideoHelper {
	
	/**
	 * Constructor
	 *
	 * @return type
	 */
	public function __construct() { }
	
	/**
	 * Method to get video info from Youtube
	 * 
	 * @param string $file_url
	 * 
	 * @return type
	 */
	public static function getYoutubeVideoInfo( $file_url ) {
		if ( empty( $file_url ) )
			return NULL;
		$api_url = 'http://www.youtube.com/oembed?url=' . $file_url . '&format=json';
		$html    = @JSNUtilsHttp::get( $api_url );
		if ( $html ) {
			return $html['body'];
		}
		return false;
	}
	
	/**
	 * Method to get video info from Youtube
	 * 
	 * @param string $file_url
	 * 
	 * @return type
	 */
	public static function getVimeoVideoInfo( $file_url ) {
		if ( empty( $file_url ) )
			return NULL;
		$api_url = 'http://vimeo.com/api/oembed.json?url=' . $file_url;
		$html    = @JSNUtilsHttp::get( $api_url );
		if ( $html ) {
			return $html['body'];
		}
		return false;
	}

}

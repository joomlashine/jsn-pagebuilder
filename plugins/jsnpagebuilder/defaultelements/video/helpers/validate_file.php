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

include_once 'helper.php';

/**
 * Class validate video file 
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbVideoValidate {
	
	/**
	 * Constructor
	 *
	 * @return type
	 */
	public function __construct() {
		$this->validateFile();
	}
	
	/**
	 * Function to validate video file
	 *
	 * @return string
	 */
	public function validateFile() {
		$file_url  = isset( $_POST['file_url'] ) ? $_POST['file_url'] : '';
		$file_type = isset( $_POST['file_type'] ) ? $_POST['file_type'] : '';
		if ( $file_type == 'youtube' ) {
			$content = JSNPbVideoHelper::getYoutubeVideoInfo( $file_url );
			$info    = json_decode( $content );
			if ( count( $info ) ) {
				$data    = array();
				$content = '';
				$content .= 'Title' . ': <b>' . (string) $info->title . '</b><br>';
				$content .= 'Author Name' . ': <b>' . (string) $info->author_name . '</b><br>';
		
				$info->description = isset( $info->description ) ? JSNPagebuilderHelpersShortcode::pbTrimWords( (string) $info->description, 20 ) : '';
				$content           .= 'Description' . ': <b>' . (string) $info->description . '</b><br>';
				$data['content']   = $content;
				$data['type']      = 'video';
		
				// Check if url had this format "list=SJHkjhlKJHSA".
				$pattern = '#list=[A-Za-z0-9^/]*#i';
				if ( preg_match( $pattern, $file_url ) && stripos( $info->html, 'videoseries?' ) === false ) {
					$data['type'] = 'list';
				}
				exit( json_encode( $data ) );
			}
		} else if ( $file_type == 'vimeo' ) {
			$content = JSNPbVideoHelper::getVimeoVideoInfo( $file_url );
			$info    = json_decode( $content );
			if ( count( $info ) ) {
				$data    = array();
				$content = '';
				$content .= 'Title' . ': <b>' . (string) $info->title . '</b><br>';
				$content .= 'Author Name' . ': <b>' . (string) $info->author_name . '</b><br>';
		
				$info->description = isset( $info->description ) ? JSNPagebuilderHelpersShortcode::pbTrimWords( (string) $info->description, 20 ) : '';
				$content           .= 'Description' . ': <b>' . (string) $info->description . '</b><br>';
				$data['content']   = $content;
				exit( json_encode( $data ) );
			}
		}
		
		exit('false');
	}

}
$_videoValidate = new JSNPbVideoValidate();

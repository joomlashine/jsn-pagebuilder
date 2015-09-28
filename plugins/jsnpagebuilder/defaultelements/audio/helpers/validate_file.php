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
 * Helper class for audio element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbAudioHelper
{

	/**
	 * Constructor
	 *
	 * @return type
	 */
	public function __construct($init = true)
	{
		if ($init)
		{
			$this->validateFile();
		}

	}

	/**
	 * Function to validate audio file
	 *
	 * @return string
	 */
	public function validateFile($url = null, $isAjax = true)
	{
		if (isset($url) && !empty($url))
		{
			$file_url = $url;
		}
		else
		{
			$file_url = isset($_POST['file_url']) ? $_POST['file_url'] : '';
		}
		// Default : weather
		$config = JSNConfigHelper::get('com_pagebuilder');
		$soundCloudKey = $config->get('soundcloud_client_id');
		if ($soundCloudKey == "") {
			if ($isAjax) {
				return false;
			} else {
				return array(
					"valid"   => false,
					"message" => "JSN_PAGEBUILDER_SOUNDCLOUD_CLIENT_ID_DOES_NOT_EXIST",
				);
			}
		}
//		$soundCloudKey = "0b237e55b145e6967548c14f1ec7d494";
		$api_url      = 'http://api.soundcloud.com/resolve.json?url=' . $file_url . '&client_id=' . $soundCloudKey;
		$get_contents = JSNUtilsHttp::get($api_url);
		$html         = $get_contents['body'];

		if ($html && strpos($html, 'error') === false)
		{
			$data         = array();
			$data['type'] = '';
			$content      = '';
			$res          = @simplexml_load_string($html);

			if ((string ) @$res->kind === 'user')
			{
				$content .= 'Username' . ': <b>' . ( string ) $res->username . '</b><br>';
				$content .= 'Country' . ': <b>' . ( string ) $res->country . '</b><br>';
				$content .= 'Full Name' . ' : <b>' . ( string ) $res->{'full-name'} . '</b><br>';
				$content .= 'Description' . ' : <b>' . ( string ) $res->description . '</b><br>';
				$data['type'] = 'list';
			}
			else if ((string ) @$res->kind === 'track')
			{
				// Render Duration displaying
				$_duration = $res->duration;
				$_seconds  = round($_duration / 1000);
				$_minutes  = round($_seconds / 60);
				$_hours    = round($_seconds / 3600);
				$_odd_sec  = ($_seconds - $_minutes * 60);

				$_duration_str = '';
				if ($_hours >= 1 && $_hours < 10)
				{
					$_duration_str .= '0' . $_hours . ':';
				}
				else if ($_hours >= 10)
				{
					$_duration_str .= $_hours . ':';
				}

				if ($_minutes >= 1 && $_minutes < 10)
				{
					$_duration_str .= '0' . $_minutes . ':';
				}
				else if ($_minutes >= 10)
				{
					$_duration_str .= $_minutes . ':';
				}
				else
				{
					$_duration_str .= '00:';
				}

				if ($_odd_sec >= 1 && $_odd_sec < 10)
				{
					$_duration_str .= '0' . $_odd_sec;
				}
				else if ($_minutes >= 10)
				{
					$_duration_str .= $_odd_sec;
				}
				else
				{
					$_duration_str .= '00';
				}

				$content .= 'Title' . ': <b>' . ( string ) $res->title . '</b><br>';
				$content .= 'Genre' . ': <b>' . ( string ) $res->genre . '</b><br>';
				$content .= 'User' . ' : <b>' . ( string ) $res->user->username . '</b><br>';
				$content .= 'Format' . ' : <b>' . ( string ) $res->{'original-format'} . '</b><br>';
				$content .= 'Duration' . ' : <b>' . ( string ) $_duration_str . '</b><br>';
			}
			else if ((string ) @$res->kind === 'playlist')
			{
				$_duration = $res->duration;
				$_seconds  = round($_duration / 1000);
				$_minutes  = round($_seconds / 60);
				$_hours    = round($_seconds / 3600);
				$_odd_sec  = ($_seconds - $_minutes * 60);

				$_duration_str = '';
				if ($_hours >= 1 && $_hours < 10)
				{
					$_duration_str .= '0' . $_hours . ':';
				}
				else if ($_hours >= 10)
				{
					$_duration_str .= $_hours . ':';
				}

				if ($_minutes >= 1 && $_minutes < 10)
				{
					$_duration_str .= '0' . $_minutes . ':';
				}
				else if ($_minutes >= 10)
				{
					$_duration_str .= $_minutes . ':';
				}
				else
				{
					$_duration_str .= '00:';
				}

				if ($_odd_sec >= 1 && $_odd_sec < 10)
				{
					$_duration_str .= '0' . $_odd_sec;
				}
				else if ($_minutes >= 10)
				{
					$_duration_str .= $_odd_sec;
				}
				else
				{
					$_duration_str .= '00';
				}

				$content .= 'Title' . ': <b>' . ( string ) $res->title . '</b><br>';
				$content .= 'Username' . ' : <b>' . ( string ) $res->user->username . '</b><br>';
				$content .= 'Duration' . ' : <b>' . ( string ) $_duration_str . '</b><br>';

				$res->description = JSNPagebuilderHelpersShortcode::pbTrimWords((string ) $res->description, 20);
				$content .= 'Description' . ' : <b>' . $res->description . '</b><br>';
				$data['type'] = 'list';
			}

			$data['content'] = $content;


			if ($isAjax)
			{
				exit(json_encode($data));
			}
			else
			{
				return array(
					"valid" => true,
					"message" => "Success",
				);
			}
		}
		if ($isAjax)
		{
			exit('false');
		}
		else
		{
			return array(
				"valid" => true,
				"message" => "Invalid URL",
			);
		}
	}

}

if (isset($_initJSNPbAudioHelper) && $_initJSNPbAudioHelper == false)
{
	$_audioHelper = new JSNPbAudioHelper(false);
}
else
{
	$_audioHelper = new JSNPbAudioHelper();
} 

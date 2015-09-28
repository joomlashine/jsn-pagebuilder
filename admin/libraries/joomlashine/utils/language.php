<?php
/**
 * @version    $Id$
 * @package    JSN_Framework
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import necessary Joomla library
jimport('joomla.filesystem.file');

/**
 * Helper class for working with language.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNUtilsLanguageTmp
{
	/**
	 * Check if a language is installable.
	 *
	 * @param   string   $code       Language code.
	 * @param   boolean  $frontend   TRUE for frontend, FALSE for backend.
	 * @param   string   $component  Component folder name.
	 *
	 * @return  boolean
	 */
	public static function installable($code, $frontend = false, $component = '')
	{
		// Get component
		$component = empty($component) ? JFactory::getApplication()->input->getCmd('option') : $component;

		// Initialize variables
		$sourcePath	= JPATH_ROOT . "/administrator/components/{$component}/language/"
					. ($frontend ? 'site' : 'admin')
					. "/{$code}/{$code}.{$component}.ini";
		$destPath   = ($frontend ? JPATH_SITE : JPATH_ADMINISTRATOR) . "/language/{$code}";

		// Check if language is installable
		return (is_dir($destPath) && is_writable($destPath) && is_file($sourcePath));
	}

	/**
	 * Check if a language is already installed.
	 *
	 * @param   string   $code       Language code.
	 * @param   boolean  $frontend   TRUE for frontend, FALSE for backend.
	 * @param   string   $component  Component folder name.
	 *
	 * @return  boolean
	 */
	public static function installed($code, $frontend = false, $component = '')
	{
		// Get component
		$component = empty($component) ? JFactory::getApplication()->input->getCmd('option') : $component;

		// Initialize variable
		$destPath = ($frontend ? JPATH_SITE : JPATH_ADMINISTRATOR) . "/language/{$code}";

		if ( ! is_dir($destPath))
		{
			// Language folder does not exists
			return false;
		}

		// Check if language is already installed
		return count(glob("{$destPath}/{$code}.{$component}.*"));
	}

	/**
	 * Check if user has manually edited a installed language.
	 *
	 * @param   string   $code       Language code.
	 * @param   boolean  $frontend   TRUE for frontend, FALSE for backend.
	 * @param   string   $component  Component folder name.
	 *
	 * @return  boolean
	 */
	public static function edited($code, $frontend = false, $component = '')
	{
		// Do checking for installed language only
		if ( ! self::installed($code, $frontend, $component))
		{
			return false;
		}

		// Get component
		$component = empty($component) ? JFactory::getApplication()->input->getCmd('option') : $component;

		// Initialize variable
		$destPath = ($frontend ? JPATH_SITE : JPATH_ADMINISTRATOR) . "/language/{$code}/";

		// Check if user has edited any language file
		$files = glob(JPATH_ROOT . "/administrator/components/{$component}/language/" . ($frontend ? 'site' : 'admin') . "/{$code}/{$code}.*.ini");

		foreach ($files AS $file)
		{
			// Clean all possible new-line character left by 'glob' function
			$file = preg_replace('/(\r|\n)/', '', $file);

			// Generate path to language file in Joomla's language folder
			$f = $destPath . basename($file);

			if (filemtime($file) != filemtime($f) OR filesize($file) != filesize($f))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if a language is supported by Joomla!.
	 *
	 * @param   string   $code       Language code.
	 * @param   boolean  $frontend   TRUE for frontend, FALSE for backend.
	 * @param   string   $component  Component folder name.
	 *
	 * @return  boolean
	 */
	public static function supported($code, $frontend = false, $component = '')
	{
		// Get component
		$component = empty($component) ? JFactory::getApplication()->input->getCmd('option') : $component;

		// Get language folder
		$destPath = ($frontend ? JPATH_SITE : JPATH_ADMINISTRATOR) . "/language/{$code}";

		// Check if language is supported by Joomla!
		return is_dir($destPath);
	}

	/**
	 * Install languages to Joomla's language folder.
	 *
	 * @param   array    $codes      Array of language code need to be installed
	 * @param   boolean  $frontend   TRUE for frontend, FALSE for backend.
	 * @param   boolean  $overwrite  Set to TRUE to force language file installation event if already installed.
	 * @param   string   $component  Component folder name.
	 *
	 * @return  void
	 */
	public static function install($codes, $frontend = false, $overwrite = false, $component = '')
	{
		// Get component
		$component = empty($component) ? JFactory::getApplication()->input->getCmd('option') : $component;

		// Initialize variables
		$sourcePath = JPATH_ROOT . "/administrator/components/{$component}/language/" . ($frontend ? 'site' : 'admin');
		$destPath   = ($frontend ? JPATH_SITE : JPATH_ADMINISTRATOR) . '/language';

		foreach ($codes AS $code)
		{
			// Check if language should be installed
			if (self::supported($code, $frontend, $component) AND ($overwrite OR ! self::installed($code, $frontend, $component)) AND self::installable($code, $frontend, $component))
			{
				// Get language files need to be installed
				$files = glob("{$sourcePath}/{$code}/{$code}.*");

				foreach ($files AS $file)
				{
					// Generate destination file path
					$path = "{$destPath}/{$code}/" . basename($file);

					// Copy language files to Joomla's language folder
					JFile::copy($file, $path);

					// Set file modification time to same as original file so we can track change when needed
					touch($path, filemtime(preg_replace('/(\r|\n)/', '', $file)));
				}
			}
		}
	}

	/**
	 * Load default language of specific component.
	 *
	 * If default language of the specified component is not already installed,
	 * this method will install the default language automatically then load it.
	 *
	 * If default language of the specified component is already installed, this
	 * method will simply return.
	 *
	 * @param   string  $component  Component folder name.
	 *
	 * @return  boolean
	 */
	public static function loadDefault($component = '')
	{
		// Get component
		$component = empty($component) ? JFactory::getApplication()->input->getCmd('option') : $component;

		// Get default language
		$lang = JFactory::getLanguage();
		$code = $lang->getDefault();

		// Is frontend application?
		$frontend = ! JFactory::getApplication()->isAdmin();

		// Install default language if necessary
		if ( ! self::installed($code, $frontend, $component) AND self::installable($code, $frontend, $component))
		{
			// Install default language
			self::install((array) $code, $frontend, false, $component);

			// Load default language
			@$lang->load($component, JPATH_BASE, $code, true);
		}
		elseif ($component != JFactory::getApplication()->input->getCmd('option'))
		{
			@$lang->load($component, JPATH_BASE, $code, true);
		}
	}

	/**
	 * Create Javascript language object.
	 *
	 * This method create Javascript object containing raw text as key and its
	 * meaning, in active language, as value. For example, the following method
	 * call:
	 *
	 * <code>JSNUtilsLanguage::toJavascript(
	 *     'JSN.lang',
	 *     array(
	 *         'JSN_EXTFW_LANGUAGE_ENGB',
	 *         'JSN_EXTFW_LANGUAGE_DEDE',
	 *         'JSN_EXTFW_LANGUAGE_FRFR'
	 *     )
	 * );</code>
	 *
	 * Will generate and return the Javascript code below (assuming active
	 * language in Joomla is English):
	 *
	 * <code>JSN.lang = {
	 *     'JSN_EXTFW_LANGUAGE_ENGB': 'English',
	 *     'JSN_EXTFW_LANGUAGE_DEDE': 'German',
	 *     'JSN_EXTFW_LANGUAGE_FRFR': 'French'
	 * };</code>
	 *
	 * @param   string  $name   Javascript variable to hold text translation.
	 * @param   array   $texts  Array of raw text.
	 *
	 * @return  string
	 */
	public static function toJavascript($name, $texts)
	{
		// Preset variable
		$js = array();

		// Generate text translation
		foreach ($texts AS $text)
		{
			$js[] = "'{$text}': '" . str_replace("'", "\\'", JText::_($text)) . "'";
		}

		// Finalize Javascript code
		$js = "{$name} = {" . implode(', ', $js) . '};';

		return $js;
	}

	/**
	 * Method to get text translation.
	 *
	 * @param   array  $strings  String to translate.
	 *
	 * @return  array
	 */
	public static function getTranslated($strings)
	{
		$translated = array();

		foreach ($strings AS $string)
		{
			$translated[strtoupper($string)] = JText::_($string);
		}

		return $translated;
	}
}

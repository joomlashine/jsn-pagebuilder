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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import necessary Joomla libraries
jimport('joomla.application.component.model');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.file');
jimport('joomla.installer.installer');

// Define product version caching expiration time
defined('CHECK_UPDATE_PERIOD') OR define('CHECK_UPDATE_PERIOD',	86400);

/**
 * Model class of JSN Installer library.
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNInstallerModel extends JSNBaseModel
{
	/**
	 * Base download link.
	 *
	 * @var  string
	 */
	protected $downloadLink = 'http://www.joomlashine.com/index.php?option=com_lightcart&controller=remoteconnectauthentication&task=authenticate&tmpl=component&upgrade=yes&';

	/**
	 * Check version link.
	 *
	 * @var  string
	 */
	protected $checkLink = 'http://www.joomlashine.com/versioning/product_version.php?category=cat_extension';

	/**
	 * Parsed check update URL.
	 *
	 * @var	array
	 */
	protected static $versions;

	/**
	 * Constructor
	 *
	 * @param   array  $config  An array of configuration options.
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// Load language manually
		$lang = JFactory::getLanguage();
		$lang->load('jsn_installer', JPATH_COMPONENT_ADMINISTRATOR . '/libraries/joomlashine/installer');

		// Get application object
		$this->app = JFactory::getApplication();

		// Get input object
		$this->input = $this->app->input;

		// Get Joomla config
		$this->config = JFactory::getConfig();
	}

	/**
	 * Download dependency package.
	 *
	 * @return  string  Package name.
	 */
	public function download()
	{
		// Get dependency declaration
		$extension = (object) $_GET;

		// Get product edition
		$edition = $this->getEdition();

		// Get Joomla version
		$JVersion = new JVersion;

		// Store username/password
		$this->app->setUserState('jsn.installer.customer.username', $this->input->getUsername('customer_username'));
		$this->app->setUserState('jsn.installer.customer.password', $this->input->getString('customer_password'));

		// Get download link
		$url = $this->getLink($extension, $JVersion);

		// Generate file name for depdendency package
		$name[]	= 'jsn';
		$name[]	= $extension->identified_name;

		if ($edition)
		{
			$name[] = strtolower(str_replace(' ', '-', isset($extension->edition) ? $extension->edition : $edition));
		}

		$name[]	= 'j' . $JVersion->RELEASE;
		$name[]	= 'install.zip';
		$name	= implode('_', $name);

		// Try to download the update package
		try
		{
			// Check if temporary directory exists
			if ( ! $this->config->get('ftp_enable') AND ( ! is_dir($this->config->get('tmp_path')) OR ! is_writable($this->config->get('tmp_path'))))
			{
				throw new Exception('FAIL:' . JText::_('JSN_EXTFW_INSTALLER_TEMPORARY_DIRECTORY_NOT_WRITABLE') . '|' . $url);
			}

			// Set maximum execution time
			ini_set('max_execution_time', 300);

			// Get package data
			$result = $this->fetchHttp($url);

			// Validate downloaded data
			if (strlen($result) < 10)
			{
				// Get LightCart error code
				throw new Exception(JText::_('JSN_EXTFW_LIGHTCART_ERROR_' . $result));
			}

			// Store downloaded package data to temporary directory
			if ( ! JFile::write($this->config->get('tmp_path') . '/' . $name, $result))
			{
				throw new Exception('FAIL:' . JText::_('JSN_EXTFW_INSTALLER_PACKAGE_SAVING_FAILED') . '|' . $url);
			}
		}
		catch (Exception $e)
		{
			throw $e;
		}

		return $name;
	}

	/**
	 * Install dependency package.
	 *
	 * @return  string
	 */
	public function install()
	{
		// Get dependency declaration
		$extension = (object) $_GET;

		// Get product edition
		$edition = $this->getEdition();

		// Get Joomla version
		$JVersion = new JVersion;

		// Get download link
		$url = $this->getLink($extension, $JVersion);

		// Finalize upload
		if (isset($_FILES['package']))
		{
			if ( ! JFile::upload($_FILES['package']['tmp_name'], $this->config->get('tmp_path') . '/' . $_FILES['package']['name']))
			{
				throw new Exception('FAIL:' . JText::_('JSN_EXTFW_INSTALLER_PACKAGE_SAVING_FAILED') . '|' . $url);
			}

			$this->input->set('package', $_FILES['package']['name']);
		}

		if ($this->input->getString('package'))
		{
			// Initialize dependency package path
			$file = $this->config->get('tmp_path') . '/' . $this->input->getString('package');
			$path = substr($file, 0, -4);

			if ( ! is_file($file))
			{
				// Check temporary directory existen
				if ( ! $this->config->get('ftp_enable') AND ( ! is_dir($this->config->get('tmp_path')) OR ! is_writable($this->config->get('tmp_path'))))
				{
					throw new Exception('FAIL:' . JText::_('JSN_EXTFW_INSTALLER_TEMPORARY_DIRECTORY_NOT_WRITABLE') . '|' . $url);
				}
				else
				{
					throw new Exception('FAIL:' . JText::sprintf('JSN_EXTFW_INSTALLER_PACKAGE_NOT_FOUND', $this->input->getString('package')) . '|' . $url);
				}
			}

			$extension->source = $path;

			// Extract dependency package
			if ( ! JArchive::extract($file, $path))
			{
				throw new Exception('FAIL:' . JText::_('JSN_EXTFW_INSTALLER_EXTRACT_PACKAGE_FAIL') . '|' . $url);
			}

			// Switch off debug mode to catch JInstaller error message manually
			$config	= JFactory::getConfig();
			$debug	= $config->get('debug');

			$config->set('debug', version_compare($JVersion->RELEASE, '3.0', '<') ? false : true);

			// Get JSN Installer
			require_once JPATH_COMPONENT_ADMINISTRATOR . '/subinstall.php';
			
			$installer = $this->input->getCmd('option') . 'InstallerScript';
			$installer = new $installer;

			try
			{
				$installer->installExtension($extension);
			}
			catch (Exception $e)
			{
				throw $e;
			}

			// Clean-up temporary folder and file
			JFolder::delete($extension->source);
			JFile::delete("{$extension->source}.zip");

			// Restore debug settings
			$config->set('debug', $debug);

			// Check if installation success
			$messages = JFactory::getApplication()->getMessageQueue();

			if (class_exists('JError'))
			{
				$messages = array_merge(JError::getErrors(), $messages);
			}

			foreach ($messages AS $message)
			{
				if (
					(is_array($message) AND @$message['type'] == 'error')
					OR
					(is_object($message) AND ( ! method_exists($message, 'get') OR $message->get('level') == E_ERROR))
				)
				{
					$errors[is_array($message) ? $message['message'] : $message->getMessage()] = 1;
				}
			}

			if (@count($errors))
			{
				throw new Exception('<ul><li>' . implode('</li><li>', array_keys($errors)) . '</li></ul>');
			}
		}
		else
		{
			throw new Exception('FAIL:' . JText::_('JSN_EXTFW_INSTALLER_MISSING_PACKAGE_NAME') . '|' . $url);
		}

		return 'SUCCESS';
	}

	/**
	 * Finalize dependency installation.
	 *
	 * @return  void
	 */
	public function finalize()
	{
		// Save live update notification setting to config table
		$model	= new JSNConfigModel;
		$form	= $model->getForm(array(), true);
		$data	= array('live_update_notification' => (string) $this->input->getInt('live_update_notification', 0));

		try
		{
			$model->save($form, $data);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * Check dependency.
	 *
	 * @param   array  &$dependencies  An array of dependency package.
	 * @param   bool   $checkUpdate    Whether to check for dependency update or not?
	 *
	 * @return  mixed
	 */
	public function check(&$dependencies, $checkUpdate = true)
	{
		// Initialize variables
		$missingDependency		= 0;
		$authenticationRequired	= false;

		// Get product edition
		$edition = $this->getEdition();

		// Get object for working with extension table
		$extension = JTable::getInstance('Extension');

		// Get installed Joomla version
		$JVersion = new JVersion;

		// Check dependency
		foreach ($dependencies AS & $dependency)
		{
			if ($dependency instanceof SimpleXMLElement)
			{
				$tmp = (array) $dependency;
				$tmp = (object) $tmp['@attributes'];
				$tmp->title = trim((string) $dependency != '' ? (string) $dependency : ($dependency['title'] ? (string) $dependency['title'] : $tmp->name));

				$dependency = $tmp;
			}

			// Skip dependency that is being removed
			if (isset($dependency->remove) AND $dependency->remove)
			{
				continue;
			}

			// Build dependency path
			switch ($dependency->type = strtolower($dependency->type))
			{
				case 'component':
				case 'module':
					$path = (( ! isset($dependency->client) OR $dependency->client != 'site') ? JPATH_BASE : JPATH_ROOT) . "/{$dependency->type}s";
				break;

				case 'plugin':
					$path = JPATH_ROOT . '/plugins/' . $dependency->folder;
				break;

				case 'template':
					$path = JPATH_ROOT . '/templates';
				break;
			}

			$path .= '/' . $dependency->name;

			// Check if dependency is installed
			$installed = file_exists($path) ? true : false;

			if ( ! $checkUpdate)
			{
				$installed ? ($dependency->upToDate = true) : $missingDependency++;

				// Continue immediately because checking for dependency update is disabled
				continue;
			}

			// Check if dependency has newer version
			if ($installed)
			{
				// Load dependency details
				$extension->load(
					array(
						'type'		=> $dependency->type,
						'element'	=> $dependency->name,
						'folder'	=> isset($dependency->folder) ? $dependency->folder : ''
					)
				);

				// Get currently installed dependency version
				$current = json_decode($extension->manifest_cache);
				$current = (is_object($current) AND isset($current->version)) ? $current->version : '0.0.0';
			}
			else
			{
				$current = '0.0.0';
			}

			// Get latest version for dependency
			if ( ! isset($dependency->identified_name)
				OR ! ($result = $this->hasUpdate($dependency->identified_name, $current, $JVersion->RELEASE))
				OR ($hasError = $result instanceof Exception))
			{
				// Store errors
				! isset($result) OR ! $result OR ! $hasError OR $errors[] = $result->getMessage();

				// Skip listing if dependency is up-to-date
				(version_compare($current, '0.0.0', 'gt') OR  ! isset($dependency->identified_name)) ? ($dependency->upToDate = true) : $missingDependency++;

				if (isset($dependency->upToDate) AND $dependency->upToDate)
				{
					// Update dependency tracking
					$ext = strtolower(substr($this->input->getCmd('option'), 4));
					$dep = ! empty($extension->custom_data) ? (array) json_decode($extension->custom_data) : array();

					if ( ! count($dep) OR ! in_array($ext, $dep))
					{
						$dep[] = $ext;

						try
						{
							$db	= JFactory::getDbo();
							$q	= $db->getQuery(true);

							$q->update('#__extensions');
							$q->set("custom_data = '" . json_encode($dep) . "'");
							$q->where("element = '{$dependency->name}'");
							$q->where("type = '{$dependency->type}'", 'AND');
							$extension->type != 'plugin' OR $q->where("folder = '{$dependency->folder}'", 'AND');

							$db->setQuery($q);
							$db->execute();
						}
						catch (Exception $e)
						{
							$this->app->enqueueMessage($e->getMessage(), 'warning');
						}
					}
				}
			}
			else
			{
				$missingDependency++;

				// Is authentication required?
				$authentication = false;

				if (isset($result->authentication) AND $result->authentication)
				{
					$authentication = true;
				}
				elseif (isset($result->editions))
				{
					foreach ($result->editions AS $item)
					{
						if (strcasecmp($item->edition, $edition) == 0 AND $item->authentication)
						{
							$authentication = true;
						}
					}
				}

				// Prepare for authentication
				if ($authentication)
				{
					$authenticationRequired	= true;
					$dependency->edition	= str_replace(' ', '+', trim(isset($result->edition) ? $result->edition : $edition));
				}
			}
		}

		if ($missingDependency == 0)
		{
			$this->saveDependency($dependencies);

			return -1;
		}

		return isset($errors) ? $errors : $authenticationRequired;
	}

	/**
	 * Get product edition.
	 *
	 * @return  string
	 */
	public function getEdition()
	{
		$edition = 'JSN_' . strtoupper(substr($this->input->getCmd('option'), 4)) . '_EDITION';

		if (defined($edition))
		{
			eval('$edition = ' . $edition . ';');
		}
		else
		{
			$edition = null;
		}

		return $edition;
	}

	/**
	 * Method to get latest dependency version.
	 *
	 * @param   string  $identified_name        Dependency's identified name.
	 * @param   string  $current_version        Current dependency version.
	 * @param   string  $requiredJoomlaVersion  Joomla version required by extension, e.g. 2.5, 3.0, etc.
	 * @param   object  $version                Latest version object used for recursive calls.
	 *
	 * @return  mixed  Object containing update information if dependency is outdated, FALSE otherwise.
	 */
	protected function hasUpdate($identified_name, $current_version, $requiredJoomlaVersion = JSN_SAMPLE_REQUIRED_JOOMLA_VER, $version = '')
	{
		static $result;

		// Only communicate with server if check update URLs is not load before
		if (empty($version))
		{
			if ( ! isset(self::$versions))
			{
				try
				{
					// Get Joomla config
					$config	= JFactory::getConfig();

					// Generate cache file path
					$cache = $config->get('tmp_path') . '/JoomlaShineUpdates.json';

					// Get latest version from local file if not timed out
					if (is_readable($cache) AND time() - filemtime($cache) < CHECK_UPDATE_PERIOD)
					{
						// Decode JSON encoded update details
						self::$versions = json_decode(JFile::read($cache));
					}
					else
					{
						// Always update cache file modification time
						is_readable($cache) AND touch($cache, time());

						try
						{
							self::$versions = $this->fetchHttp($this->checkLink);

							// Cache latest version to local file system
							JFile::write($cache, self::$versions);

							// Decode JSON encoded update details
							self::$versions = json_decode(self::$versions);
						}
						catch (Exception $e)
						{
							throw $e;
						}
					}
				}
				catch (Exception $e)
				{
					return $e;
				}
			}

			$version	= self::$versions;
			$result		= false;
		}

		// Get installed Joomla version
		$JVersion = new JVersion;

		// Get latest dependency version
		if ( ! $result)
		{
			foreach ($version->items AS $item)
			{
				if (isset($item->items))
				{
					$this->hasUpdate($identified_name, $current_version, $requiredJoomlaVersion, $item);
					continue;
				}

				if (isset($item->identified_name) AND $item->identified_name == $identified_name)
				{
					$result = $item;
					break;
				}
			}

			if (is_object($result))
			{
				// Does product support installed Joomla version?
				$tags = explode(';', $result->tags);

				if ( ! in_array($JVersion->RELEASE, $tags))
				{
					$result = false;
				}

				// Does product upgradable?
				if ($result AND ! empty($requiredJoomlaVersion) AND ! $this->isJoomlaCompatible($requiredJoomlaVersion) AND ! version_compare($result->version, $current_version, '>='))
				{
					$result = false;
				}

				// Does product have newer version?
				if ($result AND (empty($requiredJoomlaVersion) OR $this->isJoomlaCompatible($requiredJoomlaVersion)) AND ! version_compare($result->version, $current_version, '>'))
				{
					$result = false;
				}
			}
		}

		return $result;
	}

	/**
	 * Generate link to download dependency package.
	 *
	 * @param   object  $extension  Extension details.
	 * @param   object  $JVersion   Joomla version object.
	 *
	 * @return  string  Link to download dependency package.
	 */
	protected function getLink($extension, $JVersion)
	{
		// Build query string
		$query[] = 'joomla_version=' . $JVersion->RELEASE;
		$query[] = 'username=' . $this->app->getUserState('jsn.installer.customer.username');
		$query[] = 'password=' . $this->app->getUserState('jsn.installer.customer.password');
		$query[] = 'identified_name=' . $extension->identified_name;

		// Build final download link
		$url = $this->downloadLink . implode('&', $query);

		return $url;
	}

	/**
	 * Save dependency declaration to a constant.
	 *
	 * @param   array  &$dependencies  An array of dependency package.
	 *
	 * @return  void
	 */
	public function saveDependency(&$dependencies)
	{
		// Get component name
		$component = substr($this->input->getCmd('option'), 4);

		if ( ! defined('JSN_' . strtoupper($component) . '_DEPENDENCY'))
		{
			// Get Joomla config
			$config = JFactory::getConfig();

			// Unset some unnecessary properties
			foreach ($dependencies AS & $dependency)
			{
				unset($dependency->source);
				unset($dependency->upToDate);
			}

			$dependencies = json_encode($dependencies);

			// Store dependency declaration
			file_exists($defines = JPATH_COMPONENT_ADMINISTRATOR . '/defines.php')
				OR file_exists($defines = JPATH_COMPONENT_ADMINISTRATOR . '/defines.' . $component . '.php')
				OR file_exists($defines = JPATH_COMPONENT_ADMINISTRATOR . '/' . $component . '.defines.php')
				OR $defines = JPATH_COMPONENT_ADMINISTRATOR . '/' . $component . '.php';

			if ($config->get('ftp_enable') OR is_writable($defines))
			{
				$buffer = preg_replace(
						'/(defined\s*\(\s*._JEXEC.\s*\)[^\n]+\n)/',
						'\1' . "\ndefine('JSN_" . strtoupper($component) . "_DEPENDENCY', '" . $dependencies . "');\n",
						JFile::read($defines)
				);

				JFile::write($defines, $buffer);
			}
		}
	}

	/**
	 * Get remote content via http client.
	 *
	 * @param   string  $url  URL to fetch content.
	 *
	 * @return  string  Fetched content.
	 */
	protected function fetchHttp($url)
	{
		$result = '';

		// Initialize HTTP client
		class_exists('http_class') OR require_once JPATH_COMPONENT_ADMINISTRATOR . '/libraries/3rd-party/httpclient/http.php';

		$http = new http_class;
		$http->follow_redirect		= 1;
		$http->redirection_limit	= 5;
		$http->GetRequestArguments($url, $arguments);

		// Open connection
		if (($error = $http->Open($arguments)) == '')
		{
			if (($error = $http->SendRequest($arguments)) == '')
			{
				// Get response body
				while (true)
				{
					if (($error = $http->ReadReplyBody($body, 1000)) != '' OR strlen($body) == 0)
					{
						break;
					}
					$result .= $body;
				}
			}
			else
			{
				throw new Exception($error);
			}

			// Close connection
			$http->Close();
		}
		else
		{
			throw new Exception($error);
		}

		return $result;
	}

	/**
	 * Method for checking if extension is compatible with installed Joomla version.
	 *
	 * @param   string  $requiredJoomlaVersion  Joomla version required by extension, e.g. 2.5, 3.0, etc.
	 *
	 * @return  boolean
	 */
	public static function isJoomlaCompatible($requiredJoomlaVersion)
	{
		// Get installed Joomla version
		$JVersion = new JVersion;

		// Check if installed Joomla version is compatible
		return (strpos($JVersion->getShortVersion(), $requiredJoomlaVersion) !== false);
	}
}

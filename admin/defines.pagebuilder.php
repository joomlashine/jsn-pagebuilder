<?php
/**
 * 1.1.7    $Id$
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

// Define product identified name and version
define('JSN_PAGEBUILDER_IDENTIFIED_NAME', 'ext_pagebuilder');
define('JSN_PAGEBUILDER_VERSION', '1.1.7');
define('JSN_PAGEBUILDER_EDITION', 'FREE');

// Define required Joomla version
define('JSN_PAGEBUILDER_REQUIRED_JOOMLA_VER', '3.2');

// Only define below constant if product has multiple edition
// define('JSN_SAMPLE_EDITION', 'free');

// Define some necessary links
define('JSN_PAGEBUILDER_INFO_LINK', 'http://www.joomlashine.com/joomla-extensions/jsn-pagebuilder.html');
define('JSN_PAGEBUILDER_DOC_LINK', 'http://www.joomlashine.com/joomla-extensions/jsn-pagebuilder-docs.zip');
define('JSN_PAGEBUILDER_REVIEW_LINK', 'http://www.joomlashine.com/joomla-extensions/jsn-pagebuilder-on-jed.html');
define('JSN_PAGEBUILDER_UPDATE_LINK', 'index.php?option=com_pagebuilder&view=update');
define('JSN_PAGEBUILDER_UPGRADE_LINK', 'index.php?option=com_pagebuilder&view=upgrade');
define('JSNPB_ADMIN_ROOT', dirname(__FILE__));
define('JSNPB_ADMIN_URL', JUri::root(true) . '/administrator/components/com_pagebuilder');
define('JSNPB_SHORTCODE_PREFIX', 'pb_');
define('JSNPB_SHORTCODE_SESSION_NAME', 'jsn_pagebuilder');
define('JSNPB_FRAMEWORK_ASSETS', JURI::root(true) . '/plugins/system/jsnframework/assets');
define('JSNPB_ADMIN_PATH', JPATH_ROOT . '/administrator/components/com_pagebuilder/');
define('JSNPB_ASSETS_URL', JURI::root(true) . '/administrator/components/com_pagebuilder/assets/');
define('JSNPB_ASSETS_PATH', JSNPB_ADMIN_PATH . 'assets/');
define('JSNPB_PLG_SYSTEM_ASSETS_URL', JURI::root(true) . '/plugins/system/pagebuilder/assets/');
define('JSNPB_ELEMENT_URL', JUri::root(true) . '/plugins/jsnpagebuilder/defaultelements');
define('JSN_PAGEBUILDER_LIB_JSNJS_URI', JURI::root() . 'administrator/components/com_pagebuilder/assets/joomlashine/js/');
define('JSN_PAGEBUILDER_LIB_JSNCSS_URI', JURI::root() . 'administrator/components/com_pagebuilder/assets/joomlashine/css/');
define('JSN_PAGEBUILDER_3RD_EXTENSION_STRING', 'JSN3rdExtension');
define('JSN_PAGEBUILDER_EXT_IDENTIFIED_NAME_PREFIX', 'ext_pagebuilder_');
$supportedExtensions                        = array();
$supportedExtensions['com_k2']['extension'] = 'com_k2';
$supportedExtensions['com_k2']['thumbnail'] = JSNPB_ASSETS_URL . 'images/supports/logo-com-k2.jpg';
//$supportedExtensions['com_easyblog']['extension'] = 'com_easyblog';
//$supportedExtensions['com_easyblog']['thumbnail'] = JSNPB_ASSETS_URL .'images/supports/logo-com-easyblog.jpg';
@define('JSNPB_SUPPORTED_EXT_LIST', json_encode($supportedExtensions));

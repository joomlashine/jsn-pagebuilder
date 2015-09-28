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


jimport('joomla.application.component.controller');

/**
 * Controller for process upgrade JSN PageBuilder
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPagebuilderControllerUpgrade extends JSNUpgradeController{

    /**
     * Constructor
     *
     * @oaram array $config An optional associative array of configuration settings
     * Recognized key value include 'name', 'default_task', 'mod_path', 'view_path'
     * (this list is not meant to be comprehensive).
     *
    */
    public function __construct($config = array()){
        parent::__construct($config);
    }

    /**
     * Type view method for MVC based architecture
     *
     * This function is provide as a default implementation, in most cases
     * you will need to override it in your own controller.
     *
     * @param   boolean $cachable   If true, the view output will be cached
     * @param   array   $urlparams  An array of safe url parameters and their variable types, for valid value see{@link JFilterInput::clean()}.
     *
     * @return  JController A JController object to support chaining.
    **/

    public function display($cachable = false, $urlparams = false){

        JRequest::setVar('layout', 'default');
        JRequest::setVar('view', 'upgrade');
        JRequest::setVar('model', 'upgrade');
        parent::display();
    }
}
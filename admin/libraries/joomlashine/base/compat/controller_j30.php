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

/**
 * Base controller class for use across JSN libraries and extensions.
 *
 * Below is a sample use of <b>JSNBaseController</b> class:
 *
 * <code>class JSNConfigController extends JSNBaseController
 * {
 *     function save()
 *     {
 *         // Get input object
 *         $input = JFactory::getApplication()->input;
 *
 *         // Validate request
 *         $this->initializeRequest($input);
 *
 *         // Initialize variables
 *         $this->model = $this->getModel(
 *             $input->getCmd('controller') ? $input->getCmd('controller') : $input->getCmd('view')
 *         );
 *         $config = $this->model->getForm();
 *         $data   = $input->getVar('jsnconfig', array(), 'post', 'array');
 *
 *         // Attempt to save the configuration
 *         $return = true;
 *
 *         try
 *         {
 *             $this->model->save($data);
 *         }
 *         catch (Exception $e)
 *         {
 *             $return = $e;
 *         }
 *
 *         // Complete request
 *         $this->finalizeRequest($return, $input);
 *     }
 * }</code>
 *
 * @method	initializeRequest(&amp;$input, $checkToken)	Validate form token and user permission.
 * @method	finalizeRequest($return, &amp;$input)		Redirect based on the value returned by apprpriate model method.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNBaseController extends JControllerLegacy
{
	/**
	 * Method for hiding a message.
	 *
	 * @return	void
	 */
	public function hideMsg()
	{
		jexit(JSNUtilsMessage::hideMessage(JFactory::getApplication()->input->getInt('msgId')));
	}

	/**
	 * Validate task request.
	 *
	 * @param   object   &$input      JInput object.
	 * @param   booealn  $checkToken  Check token by default, set to false to disable token checking.
	 *
	 * @return  void
	 */
	protected function initializeRequest(&$input, $checkToken = true)
	{
		// Validate token
		if ($checkToken AND ! JSession::checkToken())
		{
			jexit(JText::_('JINVALID_TOKEN'));
		}

		// Validate user permission
		if ( ! JFactory::getUser()->authorise('core.admin', $input->getCmd('option')))
		{
			if ($input->getInt('ajax') == 1)
			{
				jexit(JText::_('JERROR_ALERTNOAUTHOR'));
			}
			else
			{
				JFactory::getApplication()->redirect(JRoute::_('index.php'), JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			}
		}
	}

	/**
	 * Finalize task request.
	 *
	 * @param   mixed   $return      Model execution results.
	 * @param   object  &$input      JInput object.
	 * @param   string  $successMsg  Success message.
	 * @param   string  $failMsg     Fail message.
	 *
	 * @return  void
	 */
	protected function finalizeRequest($return, &$input, $successMsg = 'JSN_EXTFW_CONFIG_SAVE_SUCCESS', $failMsg = 'JERROR_SAVE_FAILED')
	{
		$app = JFactory::getApplication();

		// Check the return value
		if ($return instanceof Exception)
		{
			if ($input->getInt('ajax') == 1)
			{
				jexit(JText::_($return->getMessage()));
			}
			else
			{
				// Save failed, go back to the screen and display a notice.
				$app->enqueueMessage(JText::_($return->getMessage()), 'error');

				$app->redirect(JRoute::_('index.php?option=' . $input->getCmd('option') . '&view=' . $input->getCmd('view')));
			}
		}

		// Save successed, complete the task
		if ($input->getInt('ajax') == 1)
		{
			jexit(JText::_($successMsg));
		}
		else
		{
			$app->enqueueMessage(JText::_($successMsg));

			$app->redirect(JRoute::_('index.php?option=' . $input->getCmd('option') . '&view=' . $input->getCmd('view')));
		}
	}
}

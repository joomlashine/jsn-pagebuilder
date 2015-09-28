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

include_once 'helpers/helper.php';

/**
 * JSN Image Show shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeImageShow extends IG_Pb_Element {

    /**
     * Constructor
     *
     */
    public function __construct() {
        if (JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_imageshow")) {
            require_once JPATH_ADMINISTRATOR .'/components/com_imageshow/classes/jsn_is_factory.php';
        }
        parent::__construct();
    }

    /**
     * Include admin scripts
     *
     * @return mixed
     */
    public function backend_element_assets() {
    }

    /**
     * DEFINE configuration information of shortcode
     *
     * @return mixed
     */
    function element_config() {
        $this->config['shortcode'] = 'pb_imageshow';
        $this->config['name'] = JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW');
        $this->config['cat'] = JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_EXTRA');
        $this->config['icon'] = "icon-pb-imageshow";
        $this->config['description'] = JText::_("JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_DES");

        $this->config['exception'] = array(
            'default_content' => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW'),
        );
    }

    /**
     * DEFINE setting options of shortcode in backend
     */
    public function backend_element_items()
    {
        $allShowList = JSNPbImageShowHelper::getAllShowList();
        $allShowCase = JSNPbImageShowHelper::getAllShowCase();
        $this->items = array(
            'content' => array(
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE"),
                    "id"      => "el_title",
                    "type"    => "text_field",
                    "class"   => "jsn-input-xlarge-fluid",
                    "std"     => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_ELEMENT_TITLE_STD'),
                    "role"    => "title",
                    "tooltip" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES"),
                ),
                array(
                    'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_SHOWLIST'),
                    'id'      => 'imageshow_showlist',
                    'type'    => 'select',
                    "class"   => "jsn-input-large-fluid",
                    'std'     => JSNPagebuilderHelpersType::getFirstOption($allShowList),
                    'options' => $allShowList,
                ),
                array(
                    'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_SHOWCASE'),
                    'id'      => 'imageshow_showcase',
                    'type'    => 'select',
                    "class"   => "jsn-input-large-fluid",
                    'std'     => JSNPagebuilderHelpersType::getFirstOption($allShowCase),
                    'options' => $allShowCase,
                ),
            ),
            'styling' => array(
                array(
                    'type' => 'preview',
                ),
                array(
                    'name'            => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_OVERALL_WIDTH'),
                    'type'            => array(
                        array(
                            'id'           => 'width_value',
                            'type'         => 'text_number',
                            'std'          => '100',
                            'class'        => 'input-mini',
                            'validate'     => 'number',
                            'parent_class' => 'combo-item merge-data',
                        ),
                        array(
                            'id'           => 'width_unit',
                            'type'         => 'select',
                            'options'      => array('%' => '%', 'px' => 'px'),
                            'std'          => '%',
                            'class'        => 'input-mini',
                            'parent_class' => 'combo-item merge-data',
                        ),
                    ),
                    'container_class' => 'combo-group',
                    'tooltip'         => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_OVERALL_WIDTH_DES'),
                ),
                array(
                    'name'     => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_OVERALL_HEIGHT'),
                    'id'       => 'height',
                    'type'     => 'text_number',
                    'std'      => '450',
                    'class'    => 'input-mini',
                    'validate' => 'number',
                ),
            ),
        );
    }

    /**
     * DEFINE setting options of shortcode in frontend
     */
    public function frontend_element_items()
    {
        $this->items = array(
            'content' => array(
                array(
                    "name"    => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE"),
                    "id"      => "el_title",
                    "type"    => "text_field",
                    "class"   => "jsn-input-xlarge-fluid",
                    "std"     => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_ELEMENT_TITLE_STD'),
                    "role"    => "title",
                    "tooltip" => JText::_("JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES"),
                ),
                array(
                    'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_SHOWLIST'),
                    'id'      => 'imageshow_showlist',
                    'type'    => 'select',
                    "class"   => "jsn-input-large-fluid",
                ),
                array(
                    'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_SHOWCASE'),
                    'id'      => 'imageshow_showcase',
                    'type'    => 'select',
                    "class"   => "jsn-input-large-fluid",
                ),
            ),
            'styling' => array(
                array(
                    'type' => 'preview',
                ),
                array(
                    'name'            => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_OVERALL_WIDTH'),
                    'type'            => array(
                        array(
                            'id'           => 'width_value',
                            'type'         => 'text_number',
                            'std'          => '100',
                            'class'        => 'input-mini',
                            'validate'     => 'number',
                            'parent_class' => 'combo-item merge-data',
                        ),
                        array(
                            'id'           => 'width_unit',
                            'type'         => 'select',
                            'options'      => array('%' => '%', 'px' => 'px'),
                            'std'          => '%',
                            'class'        => 'input-mini',
                            'parent_class' => 'combo-item merge-data',
                        ),
                    ),
                    'container_class' => 'combo-group',
                    'tooltip'         => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_OVERALL_WIDTH_DES'),
                ),
                array(
                    'name'     => JText::_('JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_OVERALL_HEIGHT'),
                    'id'       => 'height',
                    'type'     => 'text_number',
                    'std'      => '450',
                    'class'    => 'input-mini',
                    'validate' => 'number',
                ),
            ),
        );
    }

    /**
     * DEFINE shortcode content
     *
     * @param null $attributes
     * @param mixed $content
     * @return string
     */
    function element_shortcode($attributes = null, $content = null) {
        $arr_params = JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $attributes);
        extract($arr_params);

        if (!JSNPagebuilderHelpersPagebuilder::checkComponentEnabled("com_imageshow")) {
	        $html_element = "<p class='jsn-bglabel'>" . JText::_( 'JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_MSG_INSTALL_EASYSLIDER_AND_ENABLE' ) . '</p>';
            return $this->element_wrapper($html_element, $arr_params);
        }

        /** @var JSNISUtils $objUtils */
        $objUtils = JSNISFactory::getObj('classes.jsn_is_utils');
        /** @var JSNISShow $objJSNShow */
        $objJSNShow = JSNISFactory::getObj('classes.jsn_is_show');
        /** @var JSNISShowcase $objJSNShowcase */
        $objJSNShowcase = JSNISFactory::getObj('classes.jsn_is_showcase');
        /** @var JSNISShowlist $objJSNShowlist */
        $objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
        /** @var JSNISImages $objJSNImages */
        $objJSNImages = JSNISFactory::getObj('classes.jsn_is_images');

        $language = '';
        $shortEdition = $objUtils->getShortEdition();


        if ($objUtils->checkSupportLang()) {
            $objLanguage = JFactory::getLanguage();
            $language = $objLanguage->getTag();
        }

        $html = '';

        $width = !isset($attributes['width_value']) ? '' : $attributes['width_value'] . $attributes['width_unit'];
        $height = !isset($attributes['height']) ? '' : $attributes['height'];
        $showListID = isset($attributes['imageshow_showlist']) ? (int) $attributes['imageshow_showlist'] : 0;
        $showCaseID = isset($attributes['imageshow_showcase']) ? (int) $attributes['imageshow_showcase'] : 0;

	    if($showCaseID == 0)
	    {
		    $html_element = "<p class='jsn-bglabel'>" . JText::_( 'JSN_PAGEBUILDER_ELEMENT_IMAGESHOW_MSG_CREATE_SHOWCASE' ) . '</p>';
		    return $this->element_wrapper($html_element, $arr_params);
	    }

        $showlistInfo = $objJSNShowlist->getShowListByID($showListID);

        if (is_null($showlistInfo)) {
            $missingDataBox = $objUtils->displayShowlistMissingMessage();
        }

        $showcaseInfo = $objJSNShowcase->getShowCaseByID($showCaseID);

        if (is_null($showcaseInfo)) {
            $missingDataBox = $objUtils->displayShowcaseMissingMessage();
        }

        $themeProfile = false;

        if ($showcaseInfo) {
            /** @var JSNISShowcaseTheme $objJSNTheme */
            $objJSNTheme = JSNISFactory::getObj('classes.jsn_is_showcasetheme');
            $themeProfile = $objJSNTheme->getThemeProfile($showcaseInfo->showcase_id);
        }

        if (!$themeProfile) {
            $missingDataBox = $objUtils->displayThemeMissingMessage();
        }

        if (!is_null($showcaseInfo) && !is_null($showlistInfo) && $themeProfile) {
            $width = $width != '' ? $width : @$showcaseInfo->general_overall_width;
            $width = $width != '' ? $width : '100%';
            $height = $height != '' ? $height : @$showcaseInfo->general_overall_height;
            $height = $height != '' ? $height : '450';

            $imagesData = $objJSNImages->getImagesByShowlistID($showlistInfo['showlist_id']);

            $posPercentageWidth = strpos($width, '%');

            if ($posPercentageWidth) {
                $width = substr($width, 0, $posPercentageWidth + 1);
            }
            else {
                $width = (int) $width;
            }

            $height = (int) $height;
            $object = new stdClass();
            $object->width = $width;
            $object->height = $height;
            $object->showlist_id = $showListID;
            $object->showcase_id = $showCaseID;
            $object->item_id = 0;
            $object->random_number = $objUtils->randSTR(8);
            $object->language = $language;
            $object->edition = $shortEdition;
            $object->images = $imagesData;
            $object->showlist = $showlistInfo;
            $object->showcase = $showcaseInfo;
            $object->theme_id = $themeProfile->theme_id;
            $object->theme_name = $themeProfile->theme_name;
            $object->plugin = true;

            $html .= '<div class="jsn-container">';
            $html .= '<div class="jsn-gallery">';

            $result = $objJSNTheme->displayTheme($object);
            if (JFactory::$application->isAdmin()) {
                $result = str_replace(JUri::root() . "administrator/", JUri::root(), $result);
            }

            if ($result !== false) {
                $html .= $result;
            }

            $html .= '</div>';
            $html .= '</div>';
            $html_element = $html;
        } else {
            $html_element = $missingDataBox;
        }

        return $this->element_wrapper($html_element, $arr_params);
    }
}

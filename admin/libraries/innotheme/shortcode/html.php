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

if ( ! class_exists( 'IG_Pb_Helper_Html' ) ) {
	/**
	 * Define HTML output of Element Types
	 *
	 * @package  IG_PageBuilder
	 * @since    1.0.0
	 */
	class IG_Pb_Helper_Html {
		
		/**
		 * Get dependency information of an element
		 * 
		 * @param type $element
		 * 
		 * @return type
		 */
		static function get_dependency($element) {
			$depend_info = array('data' => '', 'class' => '');
			$dependency = !empty($element['dependency']) ? $element['dependency'] : "";
			if ($dependency) {
				$depend_info['data'] = " data-depend-element='param-{$dependency[0]}' data-depend-operator='{$dependency[1]}' data-depend-value='{$dependency[2]}'";
				$depend_info['class'] = ' pb_hidden_depend pb_depend_other ';
			}
			return $depend_info;
		}

		/**
		 * Get depend class & data to show/hide this option
		 *
		 * @param array $element
		 *
		 * @return array
		 */
		static function get_extra_info($element) {
			// check if element has dependened elements
			if (!isset($element['class']))
				$element['class'] = "";
			$element['class'] .= (!empty($element['has_depend']) && $element['has_depend'] == "1") ? " pb_has_depend" : "";

			$depend_info = self::get_dependency($element);
			$element['depend_class'] = $depend_info['class'];
			$element['depend_data'] = $depend_info['data'];
			return $element;
		}

		/**
		 * Add parent class for option/ group of options
		 * 
		 * @param type $output
		 * 
		 * @return type
		 */
		static function bound_options($output) {
			return '<div class="controls">' . $output . '</div>';
		}

		/**
		 * Add data attributes for element
		 * 
		 * @param type $element
		 * @param type $output
		 * 
		 * @return type
		 */
		static function get_data_info($element, $output) {
			$role = !empty($element["role"]) ? "data-role='{$element["role"]}'" : "";
			$role .=!empty($element["role_type"]) ? "data-role-type='{$element["role_type"]}'" : "";
			$role .=!empty($element["related_to"]) ? "data-related-to='{$element["related_to"]}'" : "";
			$output = str_replace("DATA_INFO", $role, $output);
			return $output;
		}

		/**
		 * Get style info
		 * 
		 * @param type $element
		 * @param type $output
		 * 
		 * @return type
		 */
		static function get_style($element, $output) {
			$style = !empty($element["style"]) ? $element["style"] : "";
			if (is_array($element["style"])) {
				$styles = array();
				foreach ($element["style"] as $att_name => $att_value) {
					$styles[] = "$att_name : $att_value";
				}
				$styles = "style = '" . implode(";", $styles) . "'";
			} else
				$styles = "";
			$output = str_replace("STYLE", $styles, $output);
			return $output;
		}

        /**
         * Output final HTML of a element
         *
         * @param array $element
         * @param string $output
         * @param string $label
         * @param bool|string $no_id
         *
         * @return string
         */
		static function final_element($element, $output, $label, $no_id = false) {
			// data info settings
			$output = self::get_data_info($element, $output);
			// custom style settings
			//$output = self::get_style($element, $output);
			// parent class
			if (!empty($element['parent_class'])) {
				$output = "<div class='{$element['parent_class']}'>" . $output . "</div>";
			}

			if (isset($element['blank_output']))
				return $output;
			else if (isset($element['bound']) && $element['bound'] == "0")
				return $label . $output;
			else {
				$id = (isset($element['id']) && !$no_id) ? "id='parent-{$element['id']}'" : '';
				if (!(isset($element['wrap']) && $element['wrap'] == "0")) {
					$output = self::bound_options($output);
				}
				$wrap_class = (!isset($element['wrap_class'])) ? "control-group" : $element['wrap_class'];
				$container_class = isset($element['container_class']) ? $element['container_class'] : '';
				$depend_class = isset($element['depend_class']) ? $element['depend_class'] : "";
				$depend_data = isset($element['depend_data']) ? $element['depend_data'] : "";
				$data_wrap_related = isset($element['data_wrap_related']) ? "data-related-to='{$element['data_wrap_related']}'" : "";

				return "<div $id class='$wrap_class $container_class $depend_class' $depend_data $data_wrap_related> $label $output </div>";
			}
		}

		/**
		 * Show/Hide label for a type element
		 * 
		 * @param array $element
		 * 
		 * @return string
		 */
		static function get_label($element) {
			$tooltip_class = isset($element['tooltip']) ? 'pb-label-des-tipsy' : '';
			$tooltip_text = ( $tooltip_class != '' ) ? 'original-title = "' . $element['tooltip'] . '"' : '';
			$label = ((isset($element['showlabel']) && $element['showlabel'] == "0") || !isset($element['name'])) ? "" : "<label class='control-label {$tooltip_class}' {$tooltip_text} for='{$element['id']}'>{$element['name']}</label>";
			return $label;
		}

		/**
		 * Text area with WYSIWYG
		 * 
		 * @param type $element
		 * 
		 * @return type
		 */
		static function tiny_mce($element) {
			// Load js and style sheet for frontend
			$base     = JUri::root();
			$jCfg = JFactory::getConfig();
			$language = JFactory::getLanguage();
			$text_direction = 'data-direction="ltr"';
			if ($language->isRTL())
			{
				$text_direction = 'data-direction="rtl"';
			}
			$urlbase = 'data-url='.$base;
            if (file_exists(JPATH_ROOT . "/media/editors/tinymce/langs/" . $language->getTag() . ".js")) {
                $langPrefix = $language->getTag();
            }
            elseif (file_exists(JPATH_ROOT . "/media/editors/tinymce/langs/" . substr($language->getTag(), 0, strpos($language->getTag(), '-')) . ".js")) {
                $langPrefix = substr($language->getTag(), 0, strpos($language->getTag(), '-'));
            }
            else {
                $langPrefix = "en";
            }
            $langPrefix = "data-lang=" . $langPrefix;

            $jceData = "";
			if( file_exists(JPATH_ROOT . '/media/editors/tinymce/tinymce.min.js') && $jCfg->get('editor') != 'jce')
			{
				if ($jCfg->get('editor') != 'tinymce' )
				{
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/tinymce.min.js', 'js' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/plugins/table/plugin.min.js', 'js' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/plugins/link/plugin.min.js', 'js' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/plugins/image/plugin.min.js', 'js' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/plugins/code/plugin.min.js', 'js' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/plugins/hr/plugin.min.js', 'js' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/plugins/charmap/plugin.min.js', 'js' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/plugins/autolink/plugin.min.js', 'js' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/plugins/lists/plugin.min.js', 'js' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/plugins/importcss/plugin.min.js', 'js' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/themes/modern/theme.min.js', 'js' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/skins/lightgray/content.inline.min.css', 'css' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/skins/lightgray/content.min.css', 'css' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/skins/lightgray/skin.min.css', 'css' );
					JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'media/editors/tinymce/skins/lightgray/skin.ie7.min.css', 'css' );
				}
				$editor = 'tinymce';
			}
			elseif($jCfg->get('editor') == 'jce')
			{
				include_once JPATH_ROOT . "/administrator/components/com_jce/includes/loader.php";
                include_once JPATH_ROOT . "/administrator/components/com_jce/models/editor.php";
                include_once JPATH_ROOT . "/components/com_jce/editor/libraries/classes/token.php";

                // create token
                $token = WFToken::getToken();
                $jceData .= " data-token=" . $token;

                // etag - version
                $etag = md5(WFModelEditor::$version);
                $jceData .= " data-etag=" . $etag;

                // get current component
                $option = JFactory::getApplication()->input->get('option');
                $component = WFExtensionHelper::getComponent(null, $option);

                // set default component id
                $component_id = 0;
                $component_id = isset($component->extension_id) ? $component->extension_id : ($component->id ? $component->id : 0);
                $jceData .= " data-component_id=" . $component_id;

                $editor = 'jce';
			}
			else
			{
				JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'plugins/system/jsnframework/assets/3rd-party/jquery-jwysiwyg/jquery.wysiwyg.js', 'js' );
				JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'plugins/system/jsnframework/assets/3rd-party/jquery-jwysiwyg/jquery.wysiwyg.css', 'css' );
				JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'plugins/system/jsnframework/assets/3rd-party/jquery-jwysiwyg/jquery.wysiwyg-0.9.js', 'js' );
				JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'plugins/system/jsnframework/assets/3rd-party/jquery-jwysiwyg/jquery.wysiwyg-0.9.css','css' );
				JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'plugins/system/jsnframework/assets/3rd-party/jquery-jwysiwyg/controls/wysiwyg.colorpicker.js', 'js' );
				JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'plugins/system/jsnframework/assets/3rd-party/jquery-jwysiwyg/controls/wysiwyg.table.js', 'js' );
				JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'plugins/system/jsnframework/assets/3rd-party/jquery-jwysiwyg/controls/wysiwyg.cssWrap.js', 'js' );
				JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'plugins/system/jsnframework/assets/3rd-party/jquery-jwysiwyg/controls/wysiwyg.image.js', 'js' );
				JSNPagebuilderHelpersFunctions::print_asset_tag( $base . 'administrator/components/com_pagebuilder/assets/js/jquery-jwysiwyg/controls/wysiwyg.link.js', 'js' );
				JSNPagebuilderHelpersFunctions::print_asset_tag( JSNPB_ASSETS_URL . 'css/jwysiwyg.css', 'css' );
			}
			$element = self::get_extra_info($element);
			$label   = self::get_label($element);
			$type    = ! empty($element["input-type"]) ? $element["input-type"] : "text";
			$role    = isset( $element['role'] ) ? "role_{$element['role']}" : '';
			$role2   = isset( $element['role_2'] ) ? 'data-role="title"' : '';
			
			$output  = "<textarea type='$type' class='{$element['class']} jsn_tiny_mce {$role} {$editor}' id='{$element['id']}' name='{$element['id']}' {$role2} {$text_direction} {$urlbase} {$langPrefix} {$jceData} DATA_INFO>{$element['std']}</textarea>";
			
			return self::final_element($element, $output, $label);
		}

		/**
		 * Simple Input text
		 * 
		 * @param type $element
		 * 
		 * @return string
		 */
		static function text_field($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$type = !empty($element["input-type"]) ? $element["input-type"] : "text";
			$output = "<input type=\"$type\" class=\"{$element['class']}\" value=\"{$element['std']}\" id=\"{$element['id']}\" name=\"{$element['id']}\" DATA_INFO />";

			return self::final_element($element, $output, $label);
		}

		/**
		 * Textarea option
		 * 
		 * @param type $element
		 * 
		 * @return type
		 */
		static function text_area($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$element['row'] = ( isset($element['row']) ) ? $element['row'] : '4';
			$element['col'] = ( isset($element['col']) ) ? $element['col'] : '50';
			if ($element['exclude_quote'] == '1') {
				$element['std'] = str_replace('<pb_quote>', '"', $element['std']);
			}
			$output = "<textarea class='{$element['class']}' id='{$element['id']}' rows='{$element['row']}' cols='{$element['col']}' DATA_INFO>{$element['std']}</textarea>";
			//$output .= "<input type='hidden' id='{$element['id']}' value='' />";
			return self::final_element($element, $output, $label);
		}

		/**
		 * Text input which has prefix/postfix Bootstrap add-on
		 * 
		 * @param type $element
		 * 
		 * @return type
		 */
		static function text_append($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);

			$ext_class = (isset($element['append_before'])) ? "input-prepend" : "";
			$ext_class .= ( isset($element['append']) ) ? " input-append" : '';
			$output = "<div class='$ext_class'>";
			$output .= (isset($element['append_before'])) ? "<span class='add-on input-group-addon'>{$element['append_before']}</span>" : "";
			$output .= "<input type='{$element['type_input']}' class='{$element['class']}' value='{$element['std']}' id='{$element['id']}' name='{$element['id']}' DATA_INFO />";
			$output .= (isset($element['append'])) ? "<span class='add-on'>{$element['append']}</span>" : "";
			$output .= "</div>";

			return self::final_element($element, $output, $label);
		}

		/**
		 * Simple Input Number
		 * 
		 * @param type $element
		 * 
		 * @return string
		 */
		static function text_number($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$output = "<input type='number' class='{$element['class']}' value='{$element['std']}' id='{$element['id']}' name='{$element['id']}' DATA_INFO />";
			return self::final_element($element, $output, $label);
		}

		/**
		 * Color picker
		 * 
		 * @param type $element
		 * 
		 * @return string
		 */
		static function color_picker($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$bg_color = ($element['std']) ? $element['std'] : '#000';
			$_hidden = ( isset($element['hide_value']) && $element['hide_value'] == false ) ? 'type="text"' : 'type="hidden"';
			$output = "<input " . $_hidden . " size='10' id='{$element['id']}' class='input-mini' disabled='disabled' name='{$element['id']}' value='{$element['std']}'  DATA_INFO />";
			$output .= "<div id='color-picker-{$element['id']}' class='color-selector'><div style='background-color: {$bg_color}'></div></div>";

			//$output = "<input class='{$element['class']} it_color_picker' id='{$element['id']}' name='{$element['id']}' type='text' value='{$element['std']}'  DATA_INFO />
			//<div class='cw-color-picker it_color_picker_cw' rel='{$element['id']}'></div>";
			return self::final_element($element, $output, $label);
		}

		/**
		 * Gradient picker
		 * 
		 * @param type $element
		 * 
		 * @return string
		 */
		static function gradient_picker($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$output = "<input type='hidden' class='jsn-grad-ex' id='{$element['id']}' name='{$element['id']}' value='{$element['std']}'  DATA_INFO />";
			$output .= "<div class='classy-gradient-box'></div>";
			return self::final_element($element, $output, $label);
		}

		/**
		 * Dimension type, which defines Width, Height of element
		 * 
		 * @param type $element
		 * @param type $input_params
		 * 
		 * @return type
		 */
		static function dimension($element, $input_params) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$element['dimension_elements'] = isset($element['dimension_elements']) ? explode(',', str_replace(' ', '', ($element['dimension_elements']))) : array('w', 'h');
			$_no_prefix_id = str_replace('param-', '', $element['id']);


			$output = '';
			if (in_array('w', $element['dimension_elements'])) {
				$_idx_width = $_no_prefix_id . "_width";
				$_idx_width_unit = $_no_prefix_id . "_width_unit";

				$element['width_std'] = isset($element[$_idx_width]) ? $element[$_idx_width]['std'] : '';
				$element['width_std'] = isset($input_params[$_idx_width]) ? $input_params[$_idx_width] : $element['width_std'];

				// Width and Width unit
				$_with = array(
					'id' => $element['id'] . "_width",
					'type' => "text_append",
					'type_input' => "number",
					'class' => "jsn-input-number input-mini",
					'parent_class' => "combo-item",
					'std' => $element['width_std'],
					'append_before' => "W",
					'validate' => "number",
					'bound' => '0'
				);

				if (isset($element[$_idx_width_unit])) {
					$element['width_unit_std'] = isset($element[$_idx_width_unit]) ? $element[$_idx_width_unit]['std'] : '';
					$element['width_unit_std'] = isset($input_params[$_idx_width_unit]) ? $input_params[$_idx_width_unit] : $element['width_unit_std'];

					$_w_unit = array(
						'id' => $element['id'] . "_width_unit",
						'type' => 'select',
						'class' => 'input-mini combo-item',
						'bound' => '0'
					);

					$_w_unit = array_merge($_w_unit, $element[$_idx_width_unit]);
					$_w_unit['std'] = $element['width_unit_std'];
					$_append = '';
				} else {
					$_with = array_merge($_with, array('append' => 'px'));
				}

				$output .= self::text_append($_with);
				$output .= isset($element[$_idx_width_unit]) ? self::select($_w_unit) : '';
			}


			// Height and Height Unit
			if (in_array('h', $element['dimension_elements'])) {
				$_idx_height = $_no_prefix_id . "_height";
				$_idx_height_unit = $_no_prefix_id . "_height_unit";

				$element['height_std'] = isset($element[$_idx_height]) ? $element[$_idx_height]['std'] : '';
				$element['height_std'] = isset($input_params[$_idx_height]) ? $input_params[$_idx_height] : $element['height_std'];
				$_append = 'px';

				$_height = array(
					'id' => $element['id'] . "_height",
					'type_input' => "number",
					'class' => "jsn-input-number input-mini",
					'parent_class' => "combo-item",
					'std' => $element['height_std'],
					'append_before' => "H",
					'validate' => "number",
					'bound' => '0'
				);

				if (isset($element[$_idx_height_unit])) {
					$element['height_unit_std'] = isset($element[$_idx_width_unit]) ? $element[$_idx_width_unit]['std'] : '';
					$element['height_unit_std'] = isset($input_params[$_idx_width_unit]) ? $input_params[$_idx_width_unit] : $element['width_unit_std'];
					$_h_unit = array(
						'id' => $element['id'] . "_height_unit",
						'type' => 'select',
						'class' => 'input-mini combo-item',
						'bound' => '0'
					);
					$_h_unit = array_merge($_h_unit, $element[$_idx_height_unit]);
					$_h_unit['std'] = $element['height_unit_std'];
					$_append = '';
				} else {
					$_height = array_merge($_height, array('append' => 'px'));
				}
				$output .= self::text_append($_height);
				$output .= isset($element[$_idx_height_unit]) ? self::select($_h_unit) : '';
			}

			return self::final_element($element, $output, $label);
		}

		/**
		 * Option to Define top/right/bottom/left margin for element
		 * 
		 * @param type $element
		 * @param type $input_params
		 * 
		 * @return type
		 */
		static function margin($element, $input_params) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$_no_prefix_id = str_replace('param-', '', $element['id']);
			// Set default margin element
			// t: top
			// r: right
			// b: bottom
			// l: left
			$element['margin_elements'] = isset($element['margin_elements']) ? explode(',', str_replace(' ', '', ($element['margin_elements']))) : array('t', 'r', 'b', 'l');

			$output = '';
			$_br = false;
			if (in_array('t', $element['margin_elements'])) {
				$_idx_top = $_no_prefix_id . "_top";
				$_br = true;
				$element['top_std'] = isset($element[$_idx_top]) ? $element[$_idx_top]['std'] : '';
				$element['top_std'] = isset($input_params[$_idx_top]) ? $input_params[$_idx_top] : $element['top_std'];
				$_top = array(
					'id' => $element['id'] . "_top",
					'type' => "text_append",
					'type_input' => "number",
					'class' => "jsn-input-number input-mini",
					'parent_class' => "combo-item",
					'std' => $element['top_std'],
					'append_before' => '<i class="icon-arrow-up"></i>',
					'append' => "px",
					'validate' => "number",
					'bound' => '0'
				);
				$output .= self::text_append($_top);
			}

			if (in_array('r', $element['margin_elements'])) {
				$_idx_right = $_no_prefix_id . "_right";
				$_br = true;
				$element['right_std'] = isset($element[$_idx_right]) ? $element[$_idx_right]['std'] : '';
				$element['right_std'] = isset($input_params[$_idx_right]) ? $input_params[$_idx_right] : $element['right_std'];
				$_right = array(
					'id' => $element['id'] . "_right",
					'type_input' => "number",
					'class' => "jsn-input-number input-mini",
					'parent_class' => "combo-item",
					'std' => $element['right_std'],
					'append_before' => '<i class="icon-arrow-right"></i>',
					'append' => "px",
					'validate' => "number",
					'bound' => '0'
				);
				$output .= self::text_append($_right);
			}

			$output .= $_br ? '<div class="clearbreak"></div>' : '';

			if (in_array('b', $element['margin_elements'])) {
				$_idx_bottom = $_no_prefix_id . "_bottom";
				$element['bottom_std'] = isset($element[$_idx_bottom]) ? $element[$_idx_bottom]['std'] : '';
				$element['bottom_std'] = isset($input_params[$_idx_bottom]) ? $input_params[$_idx_bottom] : $element['bottom_std'];
				$_bottom = array(
					'id' => $element['id'] . "_bottom",
					'type_input' => "number",
					'class' => "jsn-input-number input-mini",
					'parent_class' => "combo-item",
					'std' => $element['bottom_std'],
					'append_before' => '<i class="icon-arrow-down"></i>',
					'append' => "px",
					'validate' => "number",
					'bound' => '0'
				);
				$output .= self::text_append($_bottom);
			}

			if (in_array('l', $element['margin_elements'])) {
				$_idx_left = $_no_prefix_id . "_left";
				$element['left_std'] = isset($element[$_idx_left]) ? $element[$_idx_left]['std'] : '';
				$element['left_std'] = isset($input_params[$_idx_left]) ? $input_params[$_idx_left] : $element['left_std'];
				$_left = array(
					'id' => $element['id'] . "_left",
					'type_input' => "number",
					'class' => "jsn-input-number input-mini",
					'parent_class' => "combo-item",
					'std' => $element['left_std'],
					'append_before' => '<i class="icon-arrow-left"></i>',
					'append' => "px",
					'validate' => "number",
					'bound' => '0'
				);
				$output .= self::text_append($_left);
			}

			return self::final_element($element, $output, $label);
		}

		/**
		 * Select box
		 * 
		 * @param array $element
		 * 
		 * @return string
		 */
        static function select($element) {
            $selected_value = $element['std'];
            $options = $element['options'];
            $output = "";
            $element = self::get_extra_info($element);
            $label = self::get_label($element);
            $isMultiple = (isset($element['multiple']) && ($element['multiple'] || $element['multiple'] == 'multiple')) ? true : false;
            $multiple = $isMultiple ? 'multiple="multiple"' : '';
            $selected_value = $isMultiple ? explode(',', $selected_value) : $selected_value;
            if (is_array($options) && count($options) > 0) {
                $output = "<select id='{$element['id']}' name='{$element['id']}' class='{$element['class']}' {$multiple} >";
                foreach ($options as $key => $value) {
                    if (is_array($value)) {
                        if (isset($value['type']) && $value['type'] == "optiongroup") {
                            $output .= '<optgroup label="' . $value['text'] . '">';
                            $_optionInGroup = $value['options'];
                            if (is_array($_optionInGroup)) {
                                foreach ($_optionInGroup as $_key => $_value) {
                                    if ($isMultiple) {
                                        $_selected = in_array($_key, $selected_value) ? 'selected' : '';
                                    } else {
                                        $_selected = ($_key == $selected_value) ? 'selected' : '';
                                    }
                                    $output .= "<option value='$_key' $_selected>$_value</option>";
                                }
                            }
                            $output .= '</optgroup>';
                        } else if (isset($value['disable']) && $value['disable']) {
                            $_text = $value['text'];
                            $output .= "<option value='$key' disabled>$_text</option>";
                        }
                    } else {
                        $option_value = $key;
                        if ($isMultiple) {
                            $selected = in_array($option_value, $selected_value) ? 'selected' : '';
                        } else {
                            $selected = ($option_value == $selected_value) ? 'selected' : '';
                        }
                        $output .= "<option value='$option_value' $selected>$value</option>";
                    }
                }
            } else {
                $output = "<select id='{$element['id']}' name='{$element['id']}' class='{$element['class']}' {$multiple} data-placeholder='No data options'>";
            }
            $output .= "</select>";
            if (isset($element['append_text'])) {
                $output .= "<span class='add-on'>{$element['append_text']}</span>";
            }
            if (isset($element['multiple'])) {
                $output .= "<input type='hidden' id='{$element['id']}_select_multi' value='{$element['std']}' />";
            }
            return self::final_element($element, $output, $label);
        }

		/**
		 * JSN select fonts element
		 * 
		 * @param type $element
		 * 
		 * @return string
		 */
		static function jsn_select_font_type($element) {
			$selected_value = $element['std'];
			$options = $element['options'];
			$output = "";
			$label = "";
			if (is_array($options) && count($options) > 0) {
				$element = self::get_extra_info($element);
				$label = self::get_label($element);

				$output = "<select id='{$element['id']}' name='{$element['id']}' class='jsn-fontFaceType {$element['class']}' data-selected='{$selected_value}' value='{$selected_value}' >";
				foreach ($options as $key => $value) {
					if (!is_numeric($key)) {
						$option_value = $key;
					} else {
						$option_value = $value;
					}
					$selected = ($option_value == $selected_value) ? 'selected' : '';
					$output .= "<option value='$option_value' $selected>$value</option>";
				}
				$output .= "</select>";
			}
			return self::final_element($element, $output, $label);
		}

		/**
		 * Selectbox to select font
		 * 
		 * @param type $element
		 * 
		 * @return type
		 */
		static function jsn_select_font_value($element) {
			$selected_value = $element['std'];
			$element = self::get_extra_info($element);
			$label = self::get_label($element);

			$output = "<select id='{$element['id']}' class='jsn-fontFace {$element['class']}' data-selected='{$selected_value}' value='{$selected_value}'>";
			$output .= "<option value='{$selected_value}' selected='selected'>{$selected_value}</option>";
			$output .= "</select>";

			return self::final_element($element, $output, $label);
		}

		/**
		 * Label
		 * 
		 * @param array $element
		 * 
		 * @return string
		 */
		static function label($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$output = "<span class='add-on {$element['class']}'>{$element['std']}</span>";
			return self::final_element($element, $output, $label);
		}

		/**
		 * Radio
		 * 
		 * @param type $element
		 * 
		 * @return string
		 */
		static function radio($element) {
			$element['class'] = isset($element['class']) ? $element['class'] : 'radio inline';
			$element['input_type'] = 'radio';
			return self::checkbox($element);
		}

		/**
		 * Checkbox option
		 * 
		 * @param type $element
		 * 
		 * @return type
		 */
		static function checkbox($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$type = isset($element['input_type']) ? $element['input_type'] : 'checkbox';

			$element['std'] = explode("__#__", $element['std']);
			$output = $add_class = $linebreak = "";
			$_class = isset($element['class']) ? $element['class'] : "checkbox inline";
			$_pb_has_depend = (!empty($element['has_depend']) && $element['has_depend'] == "1") ? " pb_has_depend" : "";

			foreach ($element['options'] as $key => $text) {
				$checked = (in_array($key, $element['std']) || $element['std'][0] == "all") ? "checked" : "";
				$action_item = "";
				if (isset($element['popover_items']) && is_array($element['popover_items']))
					$action_item = in_array($key, $element['popover_items']) ? "data-popover-item='yes'" : '';
				if (isset($element['label_type'])) {
					if ($element['label_type'] == 'image') {
						// hide radio button
						$add_class = ' hidden';
						$option_html = "";
						$dimension = $element['dimension'];
						$width_height = "width:{$dimension[0]}px;height:{$dimension[1]}px;";
						if (!is_array($text)) {
							$option_html .= "<span style='$width_height' class='radio_image'></span>";
						} else {
							$linebreak = isset($text['linebreak']) ? '<br>' : '';
							$background = isset($text['img']) ? "background-image:url({$text['img']})" : "";
                            $_optionClass = isset($text['class']) ? $text['class'] : "";
							$option_html .= "<span style='$width_height $background' title='{$text[0]}' class='radio_image {$_optionClass}'></span>";
						}
						$text = $option_html;
					}
				}
				$str = "<label class='" . $_class . "'><input class='{$_pb_has_depend}{$add_class}' type='" . $type . "' value='$key' id='{$element['id']}' name='{$element['id']}' $checked DATA_INFO $action_item/>$text</label>$linebreak";

				if (isset($element['wrapper_item_start']))
					$str = $element['wrapper_item_start'] . $str;
				if (isset($element['wrapper_item_end']))
					$str = $str . $element['wrapper_item_end'];
				$output .= $str;
			}
			if ($type == 'checkbox') {
				$output .= "<input type='hidden' value=' ' id='{$element['id']}' name='{$element['id']}' DATA_INFO/>";
			}

			return self::final_element($element, $output, $label);
		}

		/**
		 * Button
		 * 
		 * @param type $element
		 * 
		 * @return string
		 */
		static function button($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$element['class'] = ($element['class']) ? $element['class'] . " btn" : "btn";
			$action_type = isset($element["action_type"]) ? " data-action-type = '{$element["action_type"]}' " : "";
			$action = isset($element["action"]) ? " data-action = '{$element["action"]}' " : "";
			$output = "<button class='{$element['class']}' $action_type $action>{$element['std']}</button>";
			return self::final_element($element, $output, $label);
		}

		/**
		 * Button group
		 * 
		 * @param type $element
		 * 
		 * @return string
		 */
		static function button_group($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);

			$output = "<div class='btn-group'>
			  <a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>
				" . JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_HTML_CONVERT_TO') . "...
				<span class='caret'></span>
			  </a>
			  <ul class='dropdown-menu'>";
			foreach ($element["actions"] as $action) {
				$output .= "<li><a href='#' data-action = '{$action["action"]}' data-action-type = '{$action["action_type"]}'>{$action['std']}</a></li>";
			}
			$output .="</ul></div>";
			return self::final_element($element, $output, $label);
		}

		/**
		 * Radio Button group
		 * 
		 * @param type $element
		 * 
		 * @return string
		 */
		static function radio_button_group($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);

			$output = "<div class='btn-group pb-btn-group' data-toggle='buttons-radio'>";
			foreach ($element['options'] as $key => $text) {
				$active = ($key == $element['std']) ? 'active' : '';
				$output .= "<button type='button' class='btn btn-icon $active' data-value='$key'><i class='pb-btn-$key'>$text</i></button>";
			}
			$output .= "</div>";
			$output .= "<div class='radio-group pb-btn-radio hidden'>";
			foreach ($element['options'] as $key => $text) {
				$active = ($key == $element['std']) ? 'active' : '';
				$output .= "<input type='radio' name='{$element['id']}' id='{$element['id']}' value='$key'/>";
			}
			$output .= "</div>";

			return self::final_element($element, $output, $label);
		}

		/**
		 * Tag
		 * 
		 * @param type $element
		 * 
		 * @return string
		 */
		static function tag($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$element['class'] = ($element['class']) ? $element['class'] . " select2" : "select2";
			$output = "<input type='hidden' value='{$element['std']}' id='{$element['id']}' class='{$element['class']}' data-share='pb_share_data' DATA_INFO />";
			return self::final_element($element, $output, $label);
		}

		/**
		 * Icons
		 * 
		 * @param array $element
		 * 
		 * @return string
		 */
		static function icons($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$output = "<div id='icon_selector'>
				<input type='hidden' value='{$element['std']}' id='{$element['id']}' name='{$element['id']}'  DATA_INFO />
			</div>";
			return self::final_element($element, $output, $label);
		}

        /**
         * Group items
         *
         * @param array $elementConfig
         *
         * @return string
         *
         */
		static function group($elementConfig) {
			$label_item = ( isset($elementConfig['label_item']) ) ? $elementConfig['label_item'] : '';
			$add_item = isset($elementConfig['add_item_text']) ? $elementConfig['add_item_text'] : JText::_("JSN_PAGEBUILDER_LIB_SHORTCODE_HTML_ADD_ITEM");
			$sub_items =  $elementConfig["sub_items"];

			$overwrite_shortcode_data = isset($elementConfig["overwrite_shortcode_data"]) ? $elementConfig["overwrite_shortcode_data"] : true;
			$sub_item_type = $elementConfig['sub_item_type'];
			$items_html = array();
			$shortcode_name = ( isset( $elementConfig['shortcode_name'] ) ) ? $elementConfig['shortcode_name'] : str_replace(JSNPB_SHORTCODE_PREFIX, "", $elementConfig["shortcode"]);
			$sub_shortcode = ( isset( $elementConfig['sub_shortcode'] ) ) ? $elementConfig['sub_shortcode'] : $elementConfig["shortcode"] . '_item';
			if ($sub_items) {
				foreach ($sub_items as $idx => $item) {
					$element = new $sub_item_type();
					// check if $item['std'] is empty or not
					$shortcode_data = "";
					if (!$label_item)
						$content = ucwords($shortcode_name) . " " . JText::_("JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_ITEM") . " " . ($idx + 1);
					else
						$content = $label_item . " " . ($idx + 1);
					if (isset($elementConfig['no_title'])) {
						$content = $elementConfig['no_title'];
					}

					if (!empty($item["std"])) {
						// keep shortcode data as it is
						$shortcode_data = $item["std"];
						// reassign params for shortcode base on std string
						$extract_params = JSNPagebuilderHelpersShortcode::extractParams(($item["std"]));
						$params = JSNPagebuilderHelpersShortcode::generateShortcodeParams($element->items, NULL, $extract_params, TRUE, FALSE, $content);
						$element->shortcode_data();
						if (!empty($params["assign_title"])) {
							if ($overwrite_shortcode_data) {
								$shortcode_data = $element->config['shortcode_structure'];
							}
						}
						$content = $params["assign_title"];
					}
					
					$content = $element->element_in_pgbldr($content, $shortcode_data);
					$items_html[] = str_replace( 'PB_INDEX_TRICK', $idx + 1, $content );
				}
			}

			$style = (isset($elementConfig['style'])) ? 'style="' . $elementConfig['style'] . '"' : '';
			$items_html = implode("", $items_html);
			$element_name = (isset($elementConfig['name'])) ? $elementConfig['name'] : JText::_(ucwords((!$label_item ) ? $shortcode_name : $label_item)) . JText::_("JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_ITEMS");
			$data_modal_title = ( ! empty( $label_item ) ) ? ' data-modal-title="' . $label_item . '"' : ' data-modal-title="' . $elementConfig['name'] . '"';
			$html_element = "<div id='{$elementConfig['id']}' class='control-group'><label class='control-label'>{$element_name}</label>
					<div class='item-container has_submodal controls'>
						<ul $style class='ui-sortable jsn-items-list item-container-content jsn-rounded-medium' id='group_elements'>
							$items_html
						</ul>
						<a href='javascript:void(0);' class='jsn-add-more pb-more-element' data-shortcode-item='" . $sub_shortcode . "' {$data_modal_title}><i class='icon-plus'></i>" . JText::_($add_item) . "</a>
					</div></div>";

			if (isset($elementConfig['use_id'])) {
				$html_element = "<div id='parent-{$elementConfig['id']}' class='control-group'>" . $html_element . "</div>";
			}

			return $html_element;
		}


		/**
		 * List of other option types (checkbox, select...)
		 *
		 * @param type $element
		 *
		 * @return type
		 */
		static function group_table($elementConfig) {

			$parentAttrs = array();
			$parentAttrType = array();
			$parentAttrTitle = array();
			$paramsValue = array();

			$session = JFactory::getSession();
			$matches	=  array();
			$sessionValue = $session->get('JSNPA_SHORTCODECONTENT');
			$session_shortcode_params = $session->get('JSNPA_SHORTCODEPARAMS');

			$pKey 	= array();
			$iKey	= array();

			if ($sessionValue != null && $sessionValue != '')
			{
				$extSessionValue 	= JSNPagebuilderHelpersShortcode::extractParams($sessionValue);
				$extSessionValue 	= @$extSessionValue['sc_inner_content'];
				$attrShortCode		= str_replace('_item', '', $elementConfig["shortcode"]) . 'attr_item';
				preg_match_all('(\[' . $attrShortCode . '[^\]]+\]\[/' . $attrShortCode . '\])', $extSessionValue, $matches, PREG_SET_ORDER);

			}

			if ($session_shortcode_params != null && $session_shortcode_params != '')
			{
				$extSessionParamValue 	= JSNPagebuilderHelpersShortcode::extractParams($session_shortcode_params);

				$extSessionParamValue 	= @$extSessionParamValue['sc_inner_content'];

				$attrShortCode		= $elementConfig["shortcode"] .'_item';

				preg_match_all('(\[' . $attrShortCode . '[^\]]+\]\[/' . $attrShortCode . '\])', $extSessionParamValue, $paramMatches, PREG_SET_ORDER);

			}

			if (count($matches))
			{
				foreach ($matches as $matche)
				{
					$tmpExt = JSNPagebuilderHelpersShortcode::extractParams($matche[0]);
					$tmpExt ['std'] =  $matche[0];
					$newTitle = $tmpExt['prtbl_item_attr_title'];
					$parentAttrs [] = $tmpExt;
				}
			}

			if (count($paramMatches))
			{
				foreach ($paramMatches as $paramMatche)
				{
					$tmpExt = JSNPagebuilderHelpersShortcode::extractParams($paramMatche[0]);
					$tmpExt ['std'] =  $paramMatche[0];
					$paramsValue [] = $tmpExt;
				}

			}

			$label_item = ( isset($elementConfig['label_item']) ) ? $elementConfig['label_item'] : '';
			$sub_items =  count($paramsValue) ? $paramsValue : $elementConfig["sub_items"];


			$overwrite_shortcode_data = isset($elementConfig["overwrite_shortcode_data"]) ? $elementConfig["overwrite_shortcode_data"] : true;
			$sub_item_type = $elementConfig['sub_item_type'];
			$items_html = array();
			$shortcode_name = ( isset( $elementConfig['shortcode_name'] ) ) ? $elementConfig['shortcode_name'] : str_replace(JSNPB_SHORTCODE_PREFIX, "", $elementConfig["shortcode"]);
			// get id of parameter to extract
			$extract_title = isset ( $elementConfig['extract_title'] ) ? $elementConfig['extract_title'] : '';


			$extra_params = array(
				'drag_handle' => false
			);

			if(count($parentAttrs) > 0){
				$parentAttrUpdate = $parentAttrs;
				if (count($parentAttrs) && count($sub_items))
				{

					foreach ($parentAttrs as $parentKey => $parentAttr)
					{
						foreach ($sub_items as $subKey => $sub_item)
						{
							if ($parentAttr['prtbl_item_attr_id'] == $sub_item['prtbl_item_attr_id'])
							{

								$iKey [] = $subKey;
								$parentAttrType[$subKey] = $parentAttrs[$parentKey]['prtbl_item_attr_type'];
								$parentAttrTitle[$subKey] = $parentAttrs[$parentKey]['prtbl_item_attr_title'];
								unset($parentAttrs[$parentKey]);

							}
						}
					}
				}


				if (count($iKey))
				{
					$iKey = array_unique($iKey);
				}

				$clone_sub_items = array();


				if (count($iKey) && count($sub_items))
				{

					foreach ($sub_items as $subKey => $sub_item)
					{

						if (in_array($subKey, $iKey))
						{
							$sub_item['std'] = str_replace('prtbl_item_attr_type="' . $sub_item['prtbl_item_attr_type'] . '"', 'prtbl_item_attr_type="' . $parentAttrType[$subKey] . '"', $sub_item['std']);
							$sub_item['prtbl_item_attr_type'] = $parentAttrType[$subKey];
							$sub_item['std'] = str_replace('prtbl_item_attr_title="' . $sub_item['prtbl_item_attr_title'] . '"', 'prtbl_item_attr_title="' . $parentAttrTitle[$subKey] . '"', $sub_item['std']);
							$sub_item['prtbl_item_attr_title'] = $parentAttrTitle[$subKey];
							$clone_sub_items[] = $sub_item;
						}

					}

					$sub_items = $clone_sub_items;

				}
				if(count($parentAttrs) > 0){
					if(count($parentAttrs) != count($parentAttrUpdate)){
						if(count($sub_items) && count($parentAttrs)){
							$sub_items = array_merge($sub_items, $parentAttrs);
						}
					}else{
						$sub_items = $parentAttrs;
					}
				}else{
					if(count($sub_items) && count($parentAttrs)){
						$sub_items = array_merge($sub_items, $parentAttrs);
					}
				}
			}else{
				$sub_items = '';
			}

			if ($sub_items) {
				foreach ($sub_items as $idx => $item) {
					$element = new $sub_item_type();
					// check if $item['std'] is empty or not
					$shortcode_data = "";
					if (!$label_item)
						$content = ucwords($shortcode_name) . " " . JText::_("JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_ITEM") . " " . ($idx + 1);
					else
						$content = $label_item . " " . ($idx + 1);
					if (isset($elementConfig['no_title'])) {
						$content = $elementConfig['no_title'];
					}

					if (!empty($item["std"])) {
						// keep shortcode data as it is
						$shortcode_data = $item["std"];
						// reassign params for shortcode base on std string
						$extract_params = JSNPagebuilderHelpersShortcode::extractParams(($item["std"]));
						$params = JSNPagebuilderHelpersShortcode::generateShortcodeParams($element->items, NULL, $extract_params, TRUE, FALSE, $content);

						$element->shortcode_data();

						$params['extract_title'] = empty ( $params['assign_title'] ) ? '(Untitled)' : $params['assign_title'];

						$content = $params['extract_title'];
						if ( $overwrite_shortcode_data ) {
							$shortcode_data = $element->config['shortcode_structure'];
						}
					}
					$element_type = (array) $element->element_in_pgbldr( $content, $shortcode_data, '', '', true, $extra_params );

					foreach ( $element_type as $element_structure ) {
						$items_html[$shortcode_data] = $element_structure;
					}
				}
			}

			$style = (isset($elementConfig['style'])) ? 'style="' . $elementConfig['style'] . '"' : '';

			$html = '';
			foreach($items_html as $shortcode_data => $item_html){

				if(!empty($extract_title)){
					$attrs = JSNPagebuilderHelpersShortcode::shortcodeParseAtts($shortcode_data);
					$title = isset($attrs[$extract_title]) ? $attrs[$extract_title] : '';
					$html .= sprintf('<tr><td><b>%s</b></td><td>%s</td></tr>', $title, $item_html);

				}
			}
			$html = sprintf( '<table class="%s" %s>%s</table>', 'table table-bordered', $style, $html );
			$element_name = (isset($elementConfig['name'])) ? $elementConfig['name'] : JText::_(ucwords((!$label_item ) ? $shortcode_name : $label_item)) . JText::_("JSN_PAGEBUILDER_LIB_SHORTCODE_ELEMENT_ITEMS");
			$html_element = "<div id='{$elementConfig['id']}' class='control-group'><label class='control-label'>{$element_name}</label>
					<div class='item-container submodal_frame_2 controls group-table {$elementConfig['class']}'>
						<div class='item-container-content jsn-items-list'>
                    $html
                    </div>
					</div></div>";

			return $html_element;
		}

		/**
		 * HR element
		 * 
		 * @param type $element
		 * 
		 * @return string
		 */
		static function hr($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$output = "<hr  DATA_INFO />";
			return self::final_element($element, $output, $label);
		}

		/**
		 * Preview Box of shortcode
		 * 
		 * @return type
		 */
		static function preview() {
			/*return '<div class="control-group">
			<div id="preview_container">
			<div id="pb_overlay_loading" class="jsn-overlay jsn-bgimage image-loading-24"></div>
			<iframe style="width:100%" scrolling="yes" id="shortcode_preview_iframe" name="shortcode_preview_iframe" class="shortcode_preview_iframe" ></iframe>
			<div id="preview"></div></div></div>';*/
			
			return '';
		}

		/**
		 * List Extra Element
		 * 
		 * @param type $element
		 * 
		 * @return string
		 */
		static function list_extra($element) {
			$html = "<div class='{$element['class']}'>";
			$html .= "<div id='{$element['id']}' class='jsn-items-list ui-sortable'>";

			if ($element['std']) {
				
			}

			$html .= "</div>";
			$html .= "<a class='jsn-add-more add-more-extra-list' onclick='return false;' href='#'><i class='icon-plus'></i>" . JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_HTML_ADD_ITEM') . "</a>";
			$html .= "</div>";
			return $html;
		}

		/**
		 * Horizonal list of filter options
		 * 
		 * @param type $data
		 * @param type $id
		 * 
		 * @return string
		 */
		static function filter_list($data, $id) {
			$html = "<ul id='filter_$id' class='nav nav-pills elementFilter'>";
			foreach ($data as $idx => $value) {
				$active = ($idx == 0) ? "active" : "";
				$html .= "<li class='$active'><a href='#' class='" . str_replace(" ", "_", $value) . "'>" . ucfirst($value) . "</a></li>";
			}
			$html .= "</ul>";
			return $html;
		}

		/**
		 * Generate HTML in Pagebuilder for Table type
		 * 
		 * @param sub_item_type $element
		 * 
		 * @return type
		 */
		static function table($element) {
			$sub_items = $element["sub_items"];
			$sub_item_type = $element['sub_item_type'];
			$items_html = array();
			$element_name = $element['name'];
			// Get HTML of Each Cell
			$shortcode_data_arr = array();

			foreach ($sub_items as $idx => $item) {
				$element = new $sub_item_type();
				$shortcode_data = "";
				$content = "&nbsp;"; // don't leave it empty
				if (!empty($item["std"])) {
					// keep shortcode data as it is
					$shortcode_data = $item["std"];
					// reassign params for shortcode base on std string
					$extract_params = JSNPagebuilderHelpersShortcode::extractParams(($item["std"]));
					$params = JSNPagebuilderHelpersShortcode::generateShortcodeParams($element->items, NULL, $extract_params, TRUE, FALSE, $content);
					$element->shortcode_data();
					if (!empty($params["assign_title"])) {
						$content = $params["assign_title"];
						$shortcode_data = $element->config['shortcode_structure'];
					}
					$shortcode_data_arr[$idx] = $shortcode_data;
				}

				$items_html[] = $element->element_in_pgbldr($content, $shortcode_data);				
			}

			// Wrap cell to a Table to display in Pagebuilder
			$row = 0;
			$updated_html = array();
			$columns_count = array();
			foreach ($items_html as $idx => $cell) {
				if (!isset($columns_count[$row]))
					$columns_count[$row] = 0;
				else
					$columns_count[$row] ++;

				$cell_html = "";
				$cell_wrap = ($row == 0) ? "th" : "td";
				if (strpos($cell, '[pb_table_item tagname="tr_start" ][/pb_table_item]') !== false)
					$cell_html .= "<tr>";
				else if (strpos($cell, '[pb_table_item tagname="tr_end" ][/pb_table_item]') !== false) {
					// Delete button on right side of table
					$action_html = ($row == 0) ? "" : "<a href='#' title='" . JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_HTML_DELETE') . "' onclick='return false;' data-target='row_table' class='element-delete'><i class='icon-trash'></i></a>";
					$cell_html .= "<$cell_wrap valign='middle' class='pb-delete-column-td'><div class='jsn-iconbar'>$action_html</div></$cell_wrap>";
					$cell_html .= "</tr>";
					$row++;
				} else {
					extract(JSNPagebuilderHelpersShortcode::shortcodeParseAtts($shortcode_data_arr[$idx]));
					$width = !empty($width_value) ? "width='$width_value$width_type'" : "";
					$cell_html .= "<$cell_wrap rowspan='$rowspan' colspan='$colspan' $width>$cell</$cell_wrap>";
				}
				$updated_html[] = $cell_html;
			}

			// Delete button below the table
			$bottom_row = "<tr class='pb-row-of-delete'>";
			for ($i = 0; $i < max($columns_count) - 1; $i++) {
				$bottom_row .= "<td><div class='jsn-iconbar'><a href='#' title='" . JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_HTML_DELETE') . "' onclick='return false;' data-target='column_table' class='element-delete'><i class='icon-trash'></i></a></div></td>";
			}
			$bottom_row .= "</tr>";
			$updated_html[] = $bottom_row;

			$items_html = "<table class='table table-bordered' id='table_content'>" . implode("", $updated_html) . "</table>";
			// end Wrap

			$buttons = '<button class="btn table_action" data-target="table_row">' . JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_HTML_ADD_ROW') . '</button>
						<button class="btn table_action" data-target="table_column">' . JText::_('JSN_PAGEBUILDER_HELPER_ROW_ADD_COLUMN') . '</button>';

			return "<div class='item-container has_submodal table_element'>
                            <input type='hidden' id='param-el_table' value='table'>
							<label for='control-label'>$element_name</label>
							<div class='jsn-fieldset-filter'><div class='btn-toolbar clearafter'>$buttons</div></div>
							<div class='ui-sortable item-container-content'>
								$items_html
							</div>
						</div>";
		}

		/**
		 * Text input hidden
		 * 
		 * @param type $element
		 * 
		 * @return type
		 */
		static function hidden($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$output = "<input type='hidden' id='{$element['id']}' class='{$element['class']}' value='{$element['std']}'  DATA_INFO />";
			return self::final_element($element, $output, $label);
		}

		/**
		 * Selectbox of Image Size options
		 * 
		 * @param type $element
		 * 
		 * @return type
		 */
		static function large_image($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$output = "<div id='{$element['id']}_wrapper' class='large_image_wrapper'><select id=\"select_{$element['id']}\"><option value=\"none\">" . JText::_('JSN_PAGEBUILDER_HELPER_TYPE_NONE') . "</option></select></div>";
			$output .= "<div class='image_loader'></div>";
			$output .= "<input type='hidden' id='{$element['id']}' class='{$element['class']}' value='{$element['std']}'  DATA_INFO />";
			return self::final_element($element, $output, $label);
		}

		/**
		 * Selectbox of Google Map Destination
		 * 
		 * @param type $element
		 * 
		 * @return type
		 */
		static function destination($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$output = "<div id='{$element['id']}_wrapper' class='pb-destination-wrapper'><select id=\"select_{$element['id']}\"><option value=\"none\">" . JText::_('JSN_PAGEBUILDER_HELPER_TYPE_NONE') . "</option></select></div>";
			$output .= "<div class='image_loader'></div>";
			$output .= "<input type='hidden' id='{$element['id']}' class='{$element['class']}' value='{$element['std']}'  DATA_INFO />";
			return self::final_element($element, $output, $label);
		}

		/**
		 * Input field to select Media
		 * 
		 * @param type $element
		 * 
		 * @return type
		 */
		static function select_media($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$_filter_type = isset($element['filter_type']) ? $element['filter_type'] : 'image';
			$output = '<div class="input-append row-fluid">
								<input type="text" class="' . $element['class'] . ' select-media-text" value="' . $element['std'] . '" id="' . $element['id'] . '">
								<button class="btn select-media" filter_type="' . $_filter_type . '" id="' . $element['id'] . '_button" type="button">...</button>
								<button class="btn btn-icon select-media-remove" type="button"><i class="icon-remove"></i></button>
							</div>';
			return self::final_element($element, $output, $label);
		}

		/**
		 * Horizonal slider to select a numeric value
		 * 
		 * @param type $element
		 * 
		 * @return type
		 */
		static function slider($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$output = '<script type="text/javascript">JoomlaShine.jQuery( document ).ready( function ()
					{
						JoomlaShine.jQuery("#' . $element['id'] . '_slider").slider({
							range: "min",
							value: ' . $element['std'] . ',
							min: 1,
							max: 14,
							start: function(event, ui){
								JoomlaShine.jQuery("#' . $element['id'] . '_slider").css({ overflow: "visible", margin: "0" });
						        JoomlaShine.jQuery("#' . $element['id'] . '_slider").parent().css({ overflow: "visible" });
							},
							stop: function(event, ui){
								JoomlaShine.jQuery("#' . $element['id'] . '").val( ui.value ).change();
								JoomlaShine.jQuery("#' . $element['id'] . '_slider").css({ overflow: "visible", margin: "0"});
						        JoomlaShine.jQuery("#' . $element['id'] . '_slider").parent().css({ overflow: "visible"});
							}
						});
					});</script>';
			$output .= '<div id="' . $element['id'] . '_slider" class="' . $element['class'] . '" ></div>';
			$output .= '<input type="hidden" id="' . $element['id'] . '" value="' . $element['std'] . '" />';
			return self::final_element($element, $output, $label);
		}

		/**
		 * List of "items_list"
		 * 
		 * @param type $element
		 * 
		 * @return type
		 */
		static function array_($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);
			$output = "";
			$items = isset($element['items']) ? $element['items'] : '';

			if (is_array($items)) {
				foreach ($items as $element_) {
					$element_func = $element_["type"];
					$element_['wrap'] = '0';
					$element_['wrap_class'] = '';
					$element_['std'] = $element['std'];
					$element_['id'] = $element['id'];

					$output .= self::$element_func($element_);
				}
			}
			return self::final_element($element, $output, $label);
		}

		/**
		 * List of other option types (checkbox, select...)
		 * 
		 * @param type $element
		 * 
		 * @return type
		 */
		static function items_list($element) {
			$element = self::get_extra_info($element);
			$label = self::get_label($element);

			$options_type = isset($element['options_type']) ? $element['options_type'] : '';
			$ul_wrap = isset($element['ul_wrap']) ? $element['ul_wrap'] : true;
			$output = "";
			$element_clone = $element;
			$element_clone['wrapper_item_start'] = "<li class='jsn-item jsn-iconbar-trigger'>";
			$element_clone['wrapper_item_end'] = "</li>";
			$element_clone['blank_output'] = "1";
			$element_clone['class'] = (isset($element['class']) ? $element['class'] : '') . ' ' . $options_type;

			// re-arrange $element['options'] array by the order of value in $element['std']
			$element_clone['std'] = str_replace(',', '__#__', $element_clone['std']);
			if (!isset($element_clone['no_order'])) {
				$std_val = explode("__#__", $element_clone['std']);
				$std = array();
				foreach ($std_val as $value) {
					if (trim($value) != "" && isset($element_clone['options'][$value]))
						$std[$value] = $element_clone['options'][$value];
				}
				// other option value which is not defined in std
				foreach ($element_clone['options'] as $key => $value) {
					if (!in_array($key, $std_val))
						$std[$key] = $value;
				}
				$element_clone['options'] = $std;
			}

			$output = self::$options_type($element_clone);
			$output = $ul_wrap ? "<ul class='jsn-items-list ui-sortable'>$output</ul>" : $output;
			return self::final_element($element, $output, $label);
		}
                
                /**
                 * Input field to select Module
                 * 
                 * @param type $element
                 * 
                 * @return type
                 */
                static function select_module($element){
                    $element = self::get_extra_info($element);
                    
                    $label = self::get_label($element);
                    $script= '';
                    $_filter_type = isset($element['filter_type']) ? $element['filter_type'] : 'module';
                    $output =   '<div class="input-append row-fluid">
                                    <input type="text" class="' . $element['class'] . ' select-module-text" value="' . $element['std'] . '" id="' . $element['id'] . '">
                                    <button class="btn select-module" filter_type="' . $_filter_type . '" id="' . $element['id'] . '_button" type="button">...</button>
                                    <button class="btn select-module-remove" type="button"><i class="icon-remove"></i></button>
                                </div>';
                    return self::final_element($element, $output, $label);
                }

	}

	// end class
} // end if !class_exists

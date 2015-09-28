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
defined('_JEXEC') or die('Restricted access');

/**
 * Google Map shortcode element
 *
 * @package JSN_PageBuilder
 * @since   1.0.4
 **/
class JSNPBShortcodeGooglemap extends IG_Pb_Element
{

	/**
	 * Constructor
	 *
	 * @return  type
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Include admin scripts
	 *
	 * @return type
	 */
	public function backend_element_assets()
	{
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/googlemap/assets/js/googlemap.js', 'js');
		JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/googlemap/assets/css/googlemap.css', 'css');
	}

	/**
	 * DEFINE configuration information of shortcode
	 *
	 * @return type
	 */
	public function element_config()
	{
		$this->config['shortcode']        = 'pb_googlemap';
		$this->config['name']             = JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP');
		$this->config['cat']              = JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_EXTRA');
		$this->config['icon']             = 'icon-google-map';
		$this->config['description']      = JText::_("JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_DES");
		$this->config['has_subshortcode'] = __CLASS__ . 'Item';
		$this->config['exception']        = array(
			'default_content'             => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP'),
			'data-modal-title'            => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP'),
		);
	}

    /**
     * DEFINE setting options of shortcode in backend
     */
    public function backend_element_items()
    {
        $this->frontend_element_items();
    }

    /**
     * DEFINE setting options of shortcode in frontend
     */
    public function frontend_element_items()
    {
		$this->items = array(
			'content' => array(
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE'),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'jsn-input-xxlarge-fluid',
					'std'     => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_ELEMENT_TITLE_STD'),
					'role'    => 'title',
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ELEMENT_TITLE_DES'),
				),
				array(
					'id'            => 'gmap_items',
					'type'          => 'group',
					'shortcode'     => $this->config['shortcode'],
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array('std' => '[pb_googlemap_item gmi_title="Apple Store, Fifth Avenue" gmi_desc_content="767 5th Ave New York, NY 10153, United States  1 212-336-1440" gmi_url="https://plus.google.com/105794202623216829535/about?gl=vn" gmi_image="https://lh6.googleusercontent.com/-kRHmUypT7rk/UWuDd_MbjsI/AAAAAACMJTw/0Bk8Pszwyls/s250-c-k-no/Apple Store, Fifth Avenue" gmi_long="-73.989637" gmi_lat="40.741220" gmi_enable_direct="no" gmi_destination=""]767 5th Ave New York, NY 10153, United States  1 212-336-1440[/pb_googlemap_item]'),
						array('std' => '[pb_googlemap_item gmi_title="Paley Park" gmi_desc_content="New York, NY 10022 United States" gmi_url="https://plus.google.com/101814405146294453824/about?gl=vn" gmi_image="https://lh6.googleusercontent.com/-pEEYVRCcoXg/T5UfT58tJ3I/AAAAAAAAZa8/sfiH6w8_R5g/s90/berlin-wall-manhattan-ny-nyc_thumb.jpg" gmi_long="-73.975152" gmi_lat="40.760196" gmi_enable_direct="no" gmi_destination=""]New York, NY 10022 United States[/pb_googlemap_item]'),
					),
					'label_item'    => 'Marker',
					'add_item_text' => 'Add Marker',
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_CONTAINER_STYLE'),
					'id'      => 'gmap_container_style',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => JSNPagebuilderHelpersType::getFirstOption(JSNPagebuilderHelpersType::getQRContainerStyle()),
					'options' => JSNPagebuilderHelpersType::getQRContainerStyle(),
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_CONTAINER_STYLE_DES'),
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT'),
					'id'      => 'gmap_alignment',
					'type'    => 'select',
					'std'     => JSNPagebuilderHelpersType::getFirstOption(JSNPagebuilderHelpersType::getTextAlign()),
					'options' => JSNPagebuilderHelpersType::getTextAlign(),
					'tooltip' => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_ALIGNMENT_DES')
				),
				array(
					'name'                      => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_DIMENSION'),
					'container_class'           => 'combo-group',
					'type'                      => 'dimension',
					'id'                        => 'gmap_dimension',
					'extended_ids'              => array('gmap_dimension_width', 'gmap_dimension_width_unit', 'gmap_dimension_height'),
					'gmap_dimension_width'      => array('std' => '100'),
					'gmap_dimension_height'     => array('std' => '300'),
					'gmap_dimension_width_unit' => array(
						'options' => array('px' => 'px', '%' => '%'),
						'std'     => '%',
					),
				),
				array(
					'name'               => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_MARGIN'),
					'container_class'    => 'combo-group',
					'id'                 => 'gmap_margin',
					'type'               => 'margin',
					'extended_ids'       => array('gmap_margin_top', 'gmap_margin_right', 'gmap_margin_bottom', 'gmap_margin_left'),
					'gmap_margin_top'    => array('std' => '10'),
					'gmap_margin_bottom' => array('std' => '10'),
					'tooltip'            => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENT_MARGIN_DES'),
				),
				array(
					'type' => 'hr',
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_ZOOM_LEVEL'),
					'id'      => 'gmap_zoom',
					'class'   => 'pb-slider',
					'type'    => 'slider',
					'std_max' => '18',
					'std'     => '12',
				),
				array(
					'name'    => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_MAP_TYPE'),
					'id'      => 'gmap_type',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => 'ROADMAP',
					'options' => JSNPagebuilderHelpersType::getGmapType(),
				),
				array(
					'name'            => JText::_('JSN_PAGEBUILDER_DEFAULT_ELEMENTS'),
					'id'              => 'gmap_elements',
					'type'            => 'checkbox',
					'class'           => 'jsn-column-item checkbox',
					'container_class' => 'jsn-columns-container jsn-columns-count-two',
					'std'             => 'streetViewControl__#__zoomControl__#__panControl__#__mapTypeControl__#__scaleControl__#__overviewControl',
					'options'         => array(
						'streetViewControl' => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_STREET_VIEW'),
						'zoomControl'       => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_ZOOM_CONTROL'),
						'panControl'        => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_PAN_CONTROL'),
						'mapTypeControl'    => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_MAP_TYPE_CONTROL'),
						'scaleControl'      => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_SCALE_CONTROL'),
						'overviewControl'   => JText::_('JSN_PAGEBUILDER_ELEMENT_GOOGLEMAP_OVERVIEW_CONTROL'),

					),
				),
			),
		);
	}

	/**
	 * DEFINE shortcode content
	 *
	 * @param type $atts
	 * @param type $content
	 *
	 * @return string
	 */
	public function element_shortcode($atts = null, $content = null)
	{
		$html_element = $container_class = $str_scripts = '';
		$arr_params   = JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts);
		extract($arr_params);
		$arrDefaultOptions = array('streetViewControl', 'zoomControl', 'panControl', 'mapTypeControl', 'scaleControl', 'overviewControl');
		if ($gmap_elements)
		{
			$arr_elements = array_filter(explode('__#__', $gmap_elements));
			foreach ($arrDefaultOptions as $i => $item)
			{
				if (in_array($item, $arr_elements))
				{
					$str_scripts .= $item . ':true, ';
				}
				else
				{
					$str_scripts .= $item . ':false, ';
				}
			}
		}
		$random_id       = JSNPagebuilderHelpersShortcode::generateRandomString();
		$player_elements = '';
		if ($gmap_alignment == 'right')
		{
			$player_elements .= '$("#pb-gmap-' . $random_id . '").css({"float" : "right"});';
		}
		elseif ($gmap_alignment == 'center')
		{
			$player_elements .= '$("#pb-gmap-' . $random_id . '").css({"margin" : "0 auto", "display" : "block"});';
		}
		elseif ($gmap_alignment == 'left')
		{
			$player_elements .= '$("#pb-gmap-' . $random_id . '").css({"float" : "left"});';
		}

		$html_element .= '<script type="text/javascript">(function ($) {
			$( document ).ready( function () {
				' . $player_elements . '
				var map_' . $random_id . '				= "";
				var marker_locations_' . $random_id . ' = [];
				var jsnpbPathRoot = "' . JURI::root() . '"
				function get_latlong(obj) {
					var myLatlng		 = new google.maps.LatLng( obj.gmi_lat, obj.gmi_long);
					return myLatlng;
				}

				function get_infobox(obj) {
					var infowindow		= "";
					var contentString	 = "<div class=\"pb-gmi-info\" style=\"width:250px\" >";
					contentString		 += "<div class=\"pb-gmi-thumb\">";
					if ( obj.gmi_image != "" && obj.gmi_image != "http://" )
					{					 
					
	                    if ((/\.(gif|jpg|jpeg|tiff|png)$/i).test(obj.gmi_image) && !/\b(http|https)/.test(obj.gmi_image))
	                    {
	                    	contentString		 += "<img src=\"" + jsnpbPathRoot + obj.gmi_image + "\" />";
	                    }	
	                    else
	                    {
	                    	contentString		 += "<img src=\""  + obj.gmi_image + "\" />";
	                    }
					}
					contentString		 += "</div>";
					contentString		 += "<span class=\"pb-gmi-title\"><b>" + obj.gmi_title + "</b></span>";
					if ( obj.gmi_desc_content )
						contentString		 += "<p>" + obj.gmi_desc_content + "</p>";
					if ( obj.gmi_url != "" && obj.gmi_url != "http://" )
						contentString		 += "<a href=\"" + obj.gmi_url + "\" target=\"_blank\">more...></a>";
					contentString		 += "</div>";
					infowindow		 = new google.maps.InfoWindow({
						content: contentString
					});
					infowindow.setOptions({maxWidth:300});
					return infowindow;
				}

				function markerAtPoint(latlng) {
					for (var i = 0; i < marker_locations_' . $random_id . '.length; ++i) {
						if (marker_locations_' . $random_id . '[i].equals(latlng)) return true;
					}
					return false;
				}

				function initialize_' . $random_id . '() {
					var gmap_zoom	= parseInt("' . $gmap_zoom . '");
					var gmap_lat	= "";
					var gmap_long	= "";
					var gmap_type	= google.maps.MapTypeId.' . $gmap_type . ';
					var directionsService_' . $random_id . '	 = new google.maps.DirectionsService();
					var has_direction		= false;

					var mapOptions_' . $random_id . ' = {
						zoom: gmap_zoom,
						center: new google.maps.LatLng(0,0),
						mapTypeId: gmap_type,
						' . $str_scripts . '
					};
					map_' . $random_id . ' = new google.maps.Map(document.getElementById(\'pb-gmap-' . $random_id . '\'), mapOptions_' . $random_id . ');
					var rendererOptions_' . $random_id . ' = {
						map: map_' . $random_id . ',
						suppressMarkers: true
					}
					var directionsDisplay_' . $random_id . ' = new google.maps.DirectionsRenderer(rendererOptions_' . $random_id . ');
					directionsDisplay_' . $random_id . '.setMap(map_' . $random_id . ');

					// Check has directions
					$( "#pb-gmap-wrapper-' . $random_id . ' .pb-gmi-lat-long" ).each(function (index) {
						var obj = JSON.parse($(this).val());
						if ( obj.gmi_lat != "" && obj.gmi_long != "" && obj.gmi_destination != "" ) {
							has_direction = true;
						}
					});

					// Add markers
					if ( has_direction == false ) {
						$( "#pb-gmap-wrapper-' . $random_id . ' .pb-gmi-lat-long" ).each(function (index) {
							var obj = JSON.parse($(this).val());
							if ( obj.gmi_lat != "" && obj.gmi_long != "" ) {
								var myLatlng	 = get_latlong(obj);
								var infowindow	 = get_infobox(obj);
								if ( map_' . $random_id . ' ) {
									var marker		= new google.maps.Marker({
										position: myLatlng,
										map: map_' . $random_id . ',
										title: obj.gmi_title
									});
									marker_locations_' . $random_id . '.push(myLatlng);
									map_' . $random_id . '.setCenter(marker.getPosition());
									google.maps.event.addListener(marker, \'click\', function() {
										infowindow.open(map_' . $random_id . ',marker);
									});
								}
							}
						});
					} else {

						$( "#pb-gmap-wrapper-' . $random_id . ' .pb-gmi-lat-long" ).each(function (i) {
							var obj = JSON.parse($(this).val());
							$( "#pb-gmap-wrapper-' . $random_id . ' .pb-gmi-lat-long" ).each(function (j) {
								var sub_obj	 = JSON.parse($(this).val());

								if ( sub_obj.gmi_title == obj.gmi_destination && sub_obj.gmi_lat != "" && sub_obj.gmi_long != "" ) {
									var start        = get_latlong(obj);
									var end			 = get_latlong(sub_obj);

									var infowindow	 = get_infobox(obj);
									if ( map_' . $random_id . ' ) {
										var marker			 = new google.maps.Marker({
											position: start,
											map: map_' . $random_id . ',
											title: obj.gmi_title
										});
										marker_locations_' . $random_id . '.push(start);
										google.maps.event.addListener(marker, \'click\', function() {
											infowindow.open(map_' . $random_id . ',marker);
										});
									}

									var sub_infowindow	 = get_infobox(sub_obj);
									if ( map_' . $random_id . ' ) {
										var sub_marker		 = new google.maps.Marker({
											position: end,
											map: map_' . $random_id . ',
											title: sub_obj.gmi_title
										});
										marker_locations_' . $random_id . '.push(end);
										google.maps.event.addListener(sub_marker, \'click\', function() {
											sub_infowindow.open(map_' . $random_id . ',sub_marker);
										});
									}

									var request = {
										origin:start,
										destination:end,
										travelMode: google.maps.DirectionsTravelMode.DRIVING
									};

									directionsService_' . $random_id . '.route(request, function(response, status) {
										if (status == google.maps.DirectionsStatus.OK) {
											directionsDisplay_' . $random_id . '.setDirections(response);
										}
									});
								}

								if ( markerAtPoint(new google.maps.LatLng( obj.gmi_lat, obj.gmi_long) ) == false ) {
									var myLatlng	 = get_latlong(obj);
									var infowindow	 = get_infobox(obj);
									if ( map_' . $random_id . ' ) {
										var marker		= new google.maps.Marker({
											position: myLatlng,
											map: map_' . $random_id . ',
											title: obj.gmi_title
										});
										marker_locations_' . $random_id . '.push(myLatlng);
										map_' . $random_id . '.setCenter(marker.getPosition());
										google.maps.event.addListener(marker, \'click\', function() {
											infowindow.open(map_' . $random_id . ',marker);
										});
									}
								}
							});
						});
					}
				}
				google.maps.event.addDomListener(window, \'load\', initialize_' . $random_id . ');
			});
		})(jQuery)</script>';

		$class = 'pb-googlemap';
		if ($gmap_container_style == 'img-thumbnail')
		{
			$class .= ' img-thumbnail';
		}

		if ($gmap_margin_top)
		{
			$gmap_styles[] = "margin-top:{$gmap_margin_top}px !important";
		}
		if ($gmap_margin_bottom)
		{
			$gmap_styles[] = "margin-bottom:{$gmap_margin_bottom}px !important";
		}
		if ($gmap_margin_right)
		{
			$gmap_styles[] = "margin-right:{$gmap_margin_right}px !important";
		}
		if ($gmap_margin_left)
		{
			$gmap_styles[] = "margin-left:{$gmap_margin_left}px !important";
		}
		if ($gmap_dimension_height)
		{
			$gmap_styles[] = "height:{$gmap_dimension_height}px !important";
		}
		if ($gmap_dimension_width)
		{
			$gmap_styles[] = "width:{$gmap_dimension_width}{$gmap_dimension_width_unit} !important";
		}
		$styles = (count($gmap_styles)) ? ' style="' . implode(';', $gmap_styles) . '"' : '';

		$html_element .= '<div id="pb-gmap-wrapper-' . $random_id . '">';
		$html_element .= '<div id="pb-gmap-' . $random_id . '" ' . $styles . ' class="' . $class . '"></div>';
		$sub_shortcode = empty($content) ? JSNPagebuilderHelpersShortcode::removeAutop($content) : JSNPagebuilderHelpersBuilder::generateShortCode($content, false, 'frontend', true);

		$items        = explode('<!--seperate-->', $sub_shortcode);
		$items        = array_filter($items);
		$initial_open = (!isset($initial_open) || $initial_open > count($items)) ? 1 : $initial_open;
		foreach ($items as $idx => $item)
		{
			$open        = ($idx + 1 == $initial_open) ? 'in' : '';
			$items[$idx] = $item;
		}

		if (!empty($sub_shortcode))
		{

			$sub_html = $sub_shortcode;
			$html_element .= $sub_html;
			$html_element .= '</div>';
		}
		$document = JFactory::getDocument();
		$document->addScript('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
		$document->addStyleSheet(JSNPB_ELEMENT_URL . '/googlemap/assets/css/googlemap.css', 'text/css');

		return $this->element_wrapper($html_element, $arr_params);
	}
}
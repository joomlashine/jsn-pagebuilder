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
 * Helper class for weather element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbWeatherHelper
{
    /**
     * @var string
     */
    protected $dataSource;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var array
     */
    protected $currentDay;

    /**
     * @var array
     */
    protected $forecast;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @param string $dataSource
     */
    public function setDataSource($dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public function getAttribute($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    public function getCurrentDay()
    {
        return $this->currentDay;
    }

    /**
     * @param array $currentDay
     */
    public function setCurrentDay($currentDay)
    {
        $this->currentDay = $currentDay;
    }

    /**
     * @return array
     */
    public function getForecast()
    {
        return $this->forecast;
    }

    /**
     * @param array $forecast
     */
    public function setForecast($forecast)
    {
        $this->forecast = $forecast;
    }

    /**
     * Weather data sources
     *
     * @return array
     */
    static function getWeatherDataSources() {
        return array(
            'weather' => JText::_('JSN_PAGEBUILDER_ELEMENT_WEATHER_WEATHER_DATA_SOURCE'),
//            'yahoo'   => JText::_('yahoo.com'),
        );
    }

    static function getNumberDay() {
        return array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
            '7' => '7',
            '8' => '8',
            '9' => '9',
            '10' => '10',
        );
    }

    static function nonUnicodeStrFilter($str) {
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        foreach($unicode as $nonUnicode => $uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    }

    /**
     * GETTING DATA
     *
     * @return object
     */
    public function getData()
    {
        clearstatcache();
        // TODO : improve cache data
        $resourceCurrentDay = $this->fetchDataFromSource();
        $this->setCurrentDay($this->handleCurrentResponse($resourceCurrentDay));
        if ($this->getAttribute('weather_layout') == 'advanced') {
            $resourceForecast = $this->fetchDataFromSource(true);
            $this->setForecast($this->handleForecastResponse($resourceForecast));
        }

        return $this;
    }

    /**
     * @param bool $isForecast
     * @return bool|mixed|string
     */
    public function fetchDataFromSource($isForecast = false)
    {
        $pathCache = JPATH_ROOT . "/tmp/";
        if (!is_writable($pathCache)) {
            $dataArray['response']['error']['description'] = "tmp folder must be writable";
            return json_encode($dataArray);
        }
        $cacheFile = $pathCache . "jsn_pb_" . md5($this->attributes['weather_location_code']);
        $cacheFile .= $isForecast ?  "_forecast.json" :  ".json";
        if (!file_exists($cacheFile) || strtotime(date("Y-m-d", filemtime($cacheFile))) < strtotime(date("Y-m-d"))) {
            // Fetch new data
            $result = JSNUtilsHttp::get($this->getApiUrl($isForecast));
            $resultContent = $result['body'];
            // Write cache
            JFile::write($cacheFile, $resultContent);
        } else {
            // Read cache
            $resultContent = file_get_contents($cacheFile);
        }

        // Return result
        return $resultContent;
    }

    /**
     * Get Api Url (Yahoo, Google, ...)
     *
     * @param bool $isForecast
     * @return string
     */
    public function getApiUrl($isForecast)
    {
        switch ($this->dataSource) {
            case 'yahoo':
                $getDataUrl = 'http://weather.yahooapis.com/forecastrss?w=' . $this->attributes['weather_location_city'] . "&u=" . $this->attributes['weather_measurement'];
                break;
            default :
                // Default : weather
                $config = JSNConfigHelper::get('com_pagebuilder');
                // TODO const key??
                $weatherApiKey = $config->get('weather_api_key');
                // TODO bad code??
                $location = explode(",", $this->attributes['weather_location_code']);
                $apiType = $isForecast ? 'forecast10day' : 'conditions';
                $getDataUrl = 'http://api.wunderground.com/api/' . $weatherApiKey . '/' . $apiType . '/q/' . $location[1] . '/' . self::nonUnicodeStrFilter(str_replace(" ", "", $location[0])) . '.json';
                break;
        }

        return $getDataUrl;
    }

    /**
     * @param $resultData
     *
     * @return array | bool
     */
    public function handleCurrentResponse($resultData)
    {
        $dataArray = json_decode($resultData, true);
        $currentWeather = array();
        switch ($this->dataSource) {
            case 'yahoo' :
                break;
            default :
                // Default : weather
                if (!isset($dataArray['current_observation'])) {
                    if (isset($dataArray['response']['error'])) {
                        $currentWeather['error'] = $dataArray['response']['error'];
                        $currentWeather['error']['description'] = 'JSN_PAGEBUILDER_' . str_replace(" ", "_", strtoupper($dataArray['response']['error']['description']));
                    } else {
                        $currentWeather['error']['description'] = 'No cities match your search query';
                    }
                    $this->deleteCacheFile();
                    return $currentWeather;
                }
                $currentObservation = $dataArray['current_observation'];

                $currentWeather['location_full_name'] = $currentObservation['display_location']['full'];
                $currentWeather['temp_current'] = $currentObservation['temp_' . $this->getAttribute('weather_measurement')] . "&deg;" . strtoupper($this->getAttribute('weather_measurement'));
                $currentWeather['temp_max'] = $currentObservation['feelslike_' . $this->getAttribute('weather_measurement')] . "&deg;" . strtoupper($this->getAttribute('weather_measurement'));
                $currentWeather['temp_min'] = $currentObservation['dewpoint_' . $this->getAttribute('weather_measurement')] . "&deg;" . strtoupper($this->getAttribute('weather_measurement'));
                $currentWeather['icon_url'] = $currentObservation['icon_url'];
                // For advanced style
                $currentWeather['weather'] = $currentObservation['weather'];
                $currentWeather['humidity'] = $currentObservation['relative_humidity'];
                $currentWeather['visibility'] = $currentObservation['visibility_km'] . 'km';
                $currentWeather['wind'] = $currentObservation['wind_kph'] . 'km/h';
                $currentWeather['wind_dir'] = $currentObservation['wind_dir'];
                break;
        }

        return $currentWeather;
    }

    /**
     * @param array $resultData
     *
     * @return array
     */
    public function handleForecastResponse($resultData)
    {
        $dataArray = json_decode($resultData, true);
        $forecastWeather = array();
        switch ($this->dataSource) {
            case 'yahoo' :
                break;
            default :
                // Default : weather
                if (!isset($dataArray['forecast']['simpleforecast']['forecastday'])) {
                    $this->deleteCacheFile(true);
                    return false;
                }
                $forecast = $dataArray['forecast']['simpleforecast']['forecastday'];
                $tempUnit = $this->getAttribute('weather_measurement') == 'c' ? 'celsius' : 'fahrenheit';
                $shortTempUnit = "&deg;" . strtoupper($this->getAttribute('weather_measurement'));

                foreach ($forecast as $_key => $_forecastDay) {
                    $forecastWeather[] = array(
                        'icon_url' => $_forecastDay['icon_url'],
                        'date'     => $_forecastDay['date'],
                        'temp_max' => $_forecastDay['high'][$tempUnit] . $shortTempUnit,
                        'temp_min' => $_forecastDay['low'][$tempUnit] . $shortTempUnit,
                    );
                }

                break;
        }

        return $forecastWeather;
    }

    public function deleteCacheFile($isForecast = false)
    {
        $pathCache = JPATH_ROOT . "/tmp/";
        if (!is_writable($pathCache)) {
            return ;
        }
        $cacheFile = $pathCache . "jsn_pb_" . md5($this->attributes['weather_location_code']);
        $cacheFile .= $isForecast ?  "_forecast.json" :  ".json";
        if (file_exists($cacheFile)) {
            JFile::delete($cacheFile);
        }
    }
}

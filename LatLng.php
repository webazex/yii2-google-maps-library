<?php
/*
 * @copyright Copyright (c) 2013-2019 2amigos
 * @copyright Copyright (c) 2025 Latul Anton (webazex@gmail.com, https://latul.website)
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\google\maps;

use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * LatLng
 *
 * Object to initialize a point with latitude and longitude
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
class LatLng extends Component
{
    /**
     * @var float the latitude
     */
    public $lat;

    /**
     * @var float the longitude
     */
    public $lng;

    /**
     * @throws \yii\base\InvalidConfigException
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->lat === null || $this->lng === null) {
            throw new InvalidConfigException('"lat" and "lng" cannot be null');
        }
    }

    /**
     * Sets the latitude
     * @param float $value
     * @return void
     */
    public function setLat($value)
    {
        $this->lat = (float)$value;
    }

    /**
     * Sets the longitude
     * @param float $value
     * @return void
     */
    public function setLng($value)
    {
        $this->lng = (float)$value;
    }

    /**
     * Returns the JavaScript code for the point
     * @return string
     */
    public function getJs()
    {
        return "new google.maps.LatLng({$this->lat}, {$this->lng})";
    }

    /**
     * Converts pixels to latitude
     * @param int $pixelYZoom
     * @param int $zoom
     * @return float
     */
    public static function pixelsToLat($pixelYZoom, $zoom)
    {
        $mapSize = 1 << $zoom;
        $lat = (M_PI / 2) - (2 * atan(exp((($pixelYZoom / $mapSize) - 0.5) * (2 * M_PI))));
        return $lat * 180 / M_PI;
    }

    /**
     * Converts pixels to longitude
     * @param int $pixelXZoom
     * @param int $zoom
     * @return float
     */
    public static function pixelsToLng($pixelXZoom, $zoom)
    {
        $mapSize = 1 << $zoom;
        $lng = ($pixelXZoom / $mapSize - 0.5) * 360;
        return $lng;
    }
}

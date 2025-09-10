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
 * LatLngBounds
 *
 * Represents a geographical bounding box defined by a southwest and northeast points
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
class LatLngBounds extends Component
{
    /**
     * @var \dosamigos\google\maps\LatLng|null the northeast point
     */
    public $northEast;

    /**
     * @var \dosamigos\google\maps\LatLng|null the southwest point
     */
    public $southWest;

    /**
     * @throws \yii\base\InvalidConfigException
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->northEast === null || $this->southWest === null) {
            throw new InvalidConfigException('"northEast" and "southWest" cannot be null');
        }
    }

    /**
     * Sets the northeast point
     * @param \dosamigos\google\maps\LatLng $value
     * @return void
     */
    public function setNorthEast(LatLng $value)
    {
        $this->northEast = $value;
    }

    /**
     * Sets the southwest point
     * @param \dosamigos\google\maps\LatLng $value
     * @return void
     */
    public function setSouthWest(LatLng $value)
    {
        $this->southWest = $value;
    }

    /**
     * Returns the latitude of the center
     * @return int
     */
    public function getCenterLat()
    {
        return (int)(($this->northEast->lat + $this->southWest->lat) / 2);
    }

    /**
     * Returns the longitude of the center
     * @return int
     */
    public function getCenterLng()
    {
        return (int)(($this->northEast->lng + $this->southWest->lng) / 2);
    }

    /**
     * Returns the zoom level
     * @param int $mapWidth
     * @param int $mapHeight
     * @return int
     */
    public function getZoom($mapWidth, $mapHeight)
    {
        $WORLD_DIM = ['width' => 256, 'height' => 256];
        $ZOOM_MAX = 21;

        $latFraction = (sin(($this->northEast->lat - $this->southWest->lat) * M_PI / 360) / 2);
        $lngFraction = (($this->northEast->lng - $this->southWest->lng) / 360);

        $latZoom = floor(log($mapHeight / $WORLD_DIM['height'] / $latFraction) / log(2));
        $lngZoom = floor(log($mapWidth / $WORLD_DIM['width'] / $lngFraction) / log(2));

        return (int)min($latZoom, $lngZoom, $ZOOM_MAX);
    }

    /**
     * Returns the JavaScript code for the bounds
     * @return string
     */
    public function getJs()
    {
        $sw = $this->southWest->getJs();
        $ne = $this->northEast->getJs();
        return "new google.maps.LatLngBounds({$sw}, {$ne})";
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

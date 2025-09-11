<?php
/*
 * @copyright Copyright (c) 2013-2019 2amigos
 * @copyright Copyright (c) 2025 Latul Anton (webazex@gmail.com, https://latul.website)
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\google\maps;

use dosamigos\google\maps\services\StreetViewPanorama;
use dosamigos\google\maps\ObjectAbstract;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Map
 *
 * Map displays a Google map. This is the starting point for using many of the other objects and classes.
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
class Map extends Component
{
    /**
     * @var string the HTML id attribute of the div where the map will be rendered
     */
    public $containerId;

    /**
     * @var float the initial center latitude
     */
    public $centerLat = 0;

    /**
     * @var float the initial center longitude
     */
    public $centerLng = 0;

    /**
     * @var int the initial zoom level
     */
    public $zoom = 8;

    /**
     * @var \dosamigos\google\maps\services\StreetViewPanorama|null A StreetViewPanorama to display when the Street View pegman is dropped on the map.
     */
    public $streetView;

    /**
     * @var array<string, string> the HTML attributes for the map container
     */
    public $containerOptions = [];

    /**
     * @var string the map type (e.g., roadmap, satellite, hybrid, terrain)
     */
    public $mapType = 'roadmap';

    /**
     * @var array<\dosamigos\google\maps\Event> the event handlers for the underlying Google Maps JS plugin
     */
    public $events = [];

    /**
     * @var array<\dosamigos\google\maps\ObjectAbstract> the overlays to attach to the map
     */
    public $overlays = [];

    /**
     * @param array $config
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct($config = [])
    {
        // Обработка 'center' для совместимости с существующим кодом сайта
        if (isset($config['center']) && $config['center'] instanceof LatLng) {
            $center = $config['center'];
            $this->centerLat = $center->lat;
            $this->centerLng = $center->lng;
            unset($config['center']);  // Удаляем 'center', чтобы избежать ошибки
        }

        parent::__construct($config);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->containerId === null) {
            throw new InvalidConfigException('"containerId" cannot be null');
        }
        if (!in_array($this->mapType, ['roadmap', 'satellite', 'hybrid', 'terrain'])) {
            throw new InvalidConfigException('Invalid "mapType". Must be one of: roadmap, satellite, hybrid, terrain');
        }
    }

    /**
     * Returns the JavaScript code required to initialize the map
     * @return string
     */
    public function getJs()
    {
        $center = new LatLng(['lat' => $this->centerLat, 'lng' => $this->centerLng]);
        $options = $this->getOptions();
        $js = "var {$this->containerId} = new google.maps.Map(document.getElementById('{$this->containerId}'), {$options});";

        foreach ($this->events as $event) {
            if ($event instanceof Event) {
                $js .= $event->getJs($this->containerId);
            }
        }

        foreach ($this->overlays as $overlay) {
            if ($overlay instanceof ObjectAbstract) {
                $js .= $overlay->getJs($this->containerId);
            }
        }

        return $js;
    }

    /**
     * Returns the map options as JSON
     * @return string
     */
    protected function getOptions()
    {
        $options = [
            'center' => new LatLng(['lat' => $this->centerLat, 'lng' => $this->centerLng]),
            'zoom' => $this->zoom,
            'mapTypeId' => "google.maps.MapTypeId." . strtoupper($this->mapType),
        ];

        if ($this->streetView !== null) {
            $options['streetView'] = $this->streetView;
        }

        return json_encode($options);
    }

    /**
     * Registers the map in the view
     * @param \yii\web\View $view
     * @return void
     */
    public function registerAssetBundle($view)
    {
        MapAsset::register($view);
    }
}

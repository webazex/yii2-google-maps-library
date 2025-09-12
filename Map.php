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
use yii\web\View;
use Yii;

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
     * @var string the name of the map object (for JavaScript variable)
     */
    public $name;

    /**
     * @var string the HTML id attribute of the div where the map will be rendered
     */
    public $containerId = 'map';  // По умолчанию 'map'

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
    private $_overlays = [];

    /**
     * @var array<string> the additional JavaScript code to append to the map
     */
    public $js = [];

    /**
     * @var bool whether to auto-register MapAsset (default true)
     */
    public $autoRegisterAsset = true;

    /**
     * @param array $config
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct($config = [])
    {
        // Обработка 'center' для совместимости
        if (isset($config['center']) && $config['center'] instanceof LatLng) {
            $center = $config['center'];
            $this->centerLat = $center->lat;
            $this->centerLng = $center->lng;
            unset($config['center']);
        }

        // Обработка 'width' и 'height' для совместимости
        $style = '';
        if (isset($config['width'])) {
            $style .= 'width: ' . $config['width'] . '; ';
            unset($config['width']);
        }
        if (isset($config['height'])) {
            $style .= 'height: ' . $config['height'] . '; ';
            unset($config['height']);
        }
        if (!empty($style)) {
            $config['containerOptions']['style'] = $style . (isset($config['containerOptions']['style']) ? $config['containerOptions']['style'] : '');
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
        if (!in_array($this->mapType, ['roadmap', 'satellite', 'hybrid', 'terrain'])) {
            throw new InvalidConfigException('Invalid "mapType". Must be one of: roadmap, satellite, hybrid, terrain');
        }
        // Генерируем $name, если не задано
        if ($this->name === null) {
            $this->name = 'map' . uniqid();  // Уникальное имя, например 'map64f1a2b3c4d5e'
        }
        // Автоматическая регистрация MapAsset, если View доступен
        if ($this->autoRegisterAsset && Yii::$app->has('view', true)) {
            MapAsset::register(Yii::$app->view);
        }
    }

    /**
     * Returns the name of the map object
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the JavaScript code required to initialize the map
     * @return string
     */
    public function getJs()
    {
        $center = new LatLng(['lat' => $this->centerLat, 'lng' => $this->centerLng]);
        $options = $this->getOptions();
        $js = "var {$this->name} = new google.maps.Map(document.getElementById('{$this->containerId}'), {$options});";

        foreach ($this->events as $event) {
            if ($event instanceof Event) {
                $js .= $event->getJs($this->name);
            }
        }

        foreach ($this->_overlays as $overlay) {
            if ($overlay instanceof ObjectAbstract) {
                $js .= $overlay->getJs($this->name);  // Передаём $this->name как $map
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

    /**
     * Adds an event to the map
     * @param Event $event
     * @return void
     */
    public function addEvent(Event $event)
    {
        $this->events[] = $event;
    }

    /**
     * Adds an overlay to the map
     * @param ObjectAbstract $overlay
     * @return void
     */
    public function addOverlay(ObjectAbstract $overlay)
    {
        $this->_overlays[] = $overlay;
    }

    /**
     * Returns the overlays attached to the map
     * @return array<\dosamigos\google\maps\ObjectAbstract>
     */
    public function getOverlays()
    {
        return $this->_overlays;
    }

    /**
     * Appends a script to the map
     * @param string $js
     * @return void
     */
    public function appendScript($js)
    {
        $this->js[] = $js;
    }

    /**
     * Displays the map (outputs HTML + JS)
     * @return string
     */
    public function display()
    {
        $html = '<div id="' . $this->containerId . '"' . $this->getContainerAttributes() . '></div>';
        // Оборачиваем JS в callback-функцию, чтобы ждать загрузки API
        $html .= '<script type="text/javascript">';
        $html .= 'function initMapGrok() { ';
        $html .= $this->getJs();
        $html .= ' }';
        $html .= '</script>';

        // Добавляем дополнительные скрипты из appendScript
        foreach ($this->js as $script) {
            $html .= '<script type="text/javascript">' . $script . '</script>';
        }

        return $html;
    }

    /**
     * Returns the container attributes
     * @return string
     */
    protected function getContainerAttributes()
    {
        $attributes = [];
        foreach ($this->containerOptions as $key => $value) {
            $attributes[] = $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '"';
        }
        return implode(' ', $attributes);
    }
}

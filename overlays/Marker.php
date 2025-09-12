<?php

/*
 * @copyright Copyright (c) 2013-2019 2amigos
 * @copyright Copyright (c) 2025 Latul Anton (webazex@gmail.com, https://latul.website)
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\google\maps\overlays;

use dosamigos\google\maps\Event;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\ObjectAbstract;

/**
 * Marker
 *
 * A marker identifies a location on a map. By default, it uses an icon with the Google Maps logo.
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
class Marker extends ObjectAbstract
{
    /**
     * @var \dosamigos\google\maps\LatLng the position of the marker
     */
    public $position;

    /**
     * @var string the title of the marker (for backward compatibility)
     */
    public $title;

    /**
     * @var string the icon URL or options for the marker (for backward compatibility)
     */
    public $icon;

    /**
     * @var \dosamigos\google\maps\overlays\InfoWindow the info window to attach to the marker
     */
    public $infoWindow;

    /**
     * @var string the map name (for JS)
     */
    public $map;

    /**
     * @param array $config
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct($config = [])
    {
        // Обработка 'title' для совместимости
        if (isset($config['title'])) {
            $this->title = $config['title'];
            $this->options['title'] = $this->title;
            unset($config['title']);
        }

        // Обработка 'icon' для совместимости
        if (isset($config['icon'])) {
            $this->icon = $config['icon'];
            $this->options['icon'] = $this->icon;  // Копируем в options для JS (URL иконки)
            unset($config['icon']);
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
        if ($this->position === null) {
            throw new \yii\base\InvalidConfigException('"position" cannot be null');
        }
    }

    /**
     * Sets the position of the marker
     * @param \dosamigos\google\maps\LatLng $value
     * @return void
     */
    public function setPosition(LatLng $value)
    {
        $this->position = $value;
        $this->options['position'] = $value->getJs();  // Для JS
    }

    /**
     * Attaches an info window to the marker
     * @param InfoWindow $infoWindow
     * @return void
     */
    public function attachInfoWindow(InfoWindow $infoWindow)
    {
        $this->infoWindow = $infoWindow;
    }

    /**
     * Adds an event to the marker
     * @param Event $event
     * @return void
     */
    public function addEvent(Event $event)
    {
        $this->events[] = $event;
    }

    /**
     * Returns the JavaScript code for the marker
     * @param string|null $map
     * @return string
     */
    public function getJs($map = null)
    {
        $options = $this->options;
        $options['map'] = $map ?? $this->map;  // Используем переданный $map или свойство $map
        $js = "var {$this->name} = new google.maps.Marker({$this->encode()});";

        if ($this->infoWindow !== null) {
            $js .= $this->infoWindow->getJs($this->name);
        }

        foreach ($this->events as $event) {
            if ($event instanceof Event) {
                $js .= $event->getJs($this->name);
            }
        }

        return $js;
    }
}

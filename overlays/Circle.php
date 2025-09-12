<?php
/*
 * @copyright Copyright (c) 2013-2019 2amigos
 * @copyright Copyright (c) 2025 Latul Anton (webazex@gmail.com, https://latul.website)
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\google\maps\overlays;

use dosamigos\google\maps\ObjectAbstract;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Event;
use yii\base\InvalidConfigException;

/**
 * Circle
 *
 * A circle on the Earth's surface (spherical cap).
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
class Circle extends ObjectAbstract
{
    /**
     * @var \dosamigos\google\maps\LatLng the center of the circle
     */
    public $center;

    /**
     * @var float the radius in meters
     */
    public $radius;

    /**
     * @param array $config
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct($config = [])
    {
        $this->options = array_merge([
            'center' => null,
            'radius' => null,
            'strokeColor' => null,
            'strokeOpacity' => null,
            'strokeWeight' => null,
            'fillColor' => null,
            'fillOpacity' => null,
            'clickable' => null,
            'draggable' => null,
            'editable' => null,
            'visible' => null,
            'zIndex' => null,
        ], $this->options);

        parent::__construct($config);

        // Обработка 'center' для совместимости
        if (isset($config['center']) && $config['center'] instanceof LatLng) {
            $this->center = $config['center'];
            $this->options['center'] = $this->center->getJs();
        }
        // Обработка 'radius'
        if (isset($config['radius'])) {
            $this->radius = (float)$config['radius'];
            $this->options['radius'] = $this->radius;
        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->center === null) {
            throw new InvalidConfigException('"center" cannot be null');
        }
        if ($this->radius === null) {
            throw new InvalidConfigException('"radius" cannot be null');
        }
    }

    /**
     * Sets the center of the circle
     * @param \dosamigos\google\maps\LatLng $value
     * @return void
     */
    public function setCenter(LatLng $value)
    {
        $this->center = $value;
        $this->options['center'] = $value->getJs();
    }

    /**
     * Sets the radius of the circle
     * @param float $value
     * @return void
     */
    public function setRadius($value)
    {
        $this->radius = (float)$value;
        $this->options['radius'] = $this->radius;
    }

    /**
     * Adds an event to the circle
     * @param \dosamigos\google\maps\Event $event
     * @return void
     */
    public function addEvent(Event $event)
    {
        $this->events[] = $event;
    }

    /**
     * Returns the JavaScript code for the circle
     * @param string|null $map
     * @return string
     */
    public function getJs($map = null)
    {
        $options = $this->options;
        $options['map'] = $map ?? $this->map;
        $js = "var {$this->name} = new google.maps.Circle({$this->encode()});";

        foreach ($this->events as $event) {
            if ($event instanceof Event) {
                $js .= $event->getJs($this->name);
            }
        }

        return $js;
    }
}

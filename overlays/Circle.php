<?php

/*
 *
 * @copyright Copyright (c) 2013-2019 2amigos
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 *
 */

namespace dosamigos\google\maps\overlays;

use dosamigos\google\maps\OverlayTrait;
use yii\base\InvalidConfigException;
use dosamigos\google\maps\LatLng;

/**
 * Circle
 *
 * Object to render circles on the map.
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 *
 * @link http://www.2amigos.us/
 * @package dosamigos\google\maps
 */
class Circle extends CircleOptions
{
    use OverlayTrait;

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */

    private $_center;
    private $_radius;
    public function init()
    {
        if ($this->center == null) {
            throw new InvalidConfigException('"center" cannot be null');
        }
    }

    public function setCenter(LatLng $center)
    {
        $this->_center = $center;
        $this->options['center'] = $center;
    }

    public function getCenter()
    {
        return $this->_center;
    }

    public function setRadius($radius)
    {
        $this->_radius = $radius;
        $this->options['radius'] = $radius;
    }

    public function getRadius()
    {
        return $this->_radius;
    }

    /**
     * Returns the center of bounds
     * @return \dosamigos\google\maps\LatLng
     */
    public function getCenterOfBounds()
    {
        return $this->center;
    }

    /**
     * Returns the js code to create a rectangle on a map
     * @return string
     */
    public function getJs()
    {
        $js = $this->getInfoWindowJs();

        $js[] = "var {$this->getName()} = new google.maps.Circle({$this->getEncodedOptions()});";

        foreach ($this->events as $event) {
            /** @var \dosamigos\google\maps\Event $event */
            $js[] = $event->getJs($this->getName());
        }

        return implode("\n", $js);
    }
}

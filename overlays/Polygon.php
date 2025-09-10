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
use dosamigos\google\maps\OverlayTrait;

/**
 * Polygon
 *
 * A polygon (like a polyline) defines a series of connected coordinates in an ordered sequence; additionally,
 * polygons form a closed loop and define a filled region.
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
class Polygon extends ObjectAbstract
{
    use OverlayTrait;

    /**
     * @var array<\dosamigos\google\maps\LatLng> the coordinates of the polygon
     */
    public $coords = [];

    /**
     * @param array<string, mixed> $options
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Returns the JavaScript code for the polygon
     * @return string
     */
    public function getJs()
    {
        $coords = [];
        foreach ($this->coords as $coord) {
            if ($coord instanceof LatLng) {
                $coords[] = $coord->getJs();
            }
        }
        $coordsJs = '[' . implode(',', $coords) . ']';
        $js = [];
        $js[] = "var {$this->name} = new google.maps.Polygon({$this->encode()});";
        if ($this->infoWindow !== null) {
            $js = array_merge($js, $this->getInfoWindowJs());
        }
        foreach ($this->events as $event) {
            if ($event instanceof \dosamigos\google\maps\Event) {
                $js[] = $event->getJs($this->name);
            }
        }
        $js[] = "{$this->name}.setMap({$this->map});";
        return implode("\n", $js);
    }
}

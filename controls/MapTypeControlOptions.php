<?php
/*
 * @copyright Copyright (c) 2013-2019 2amigos
 * @copyright Copyright (c) 2025 Latul Anton (webazex@gmail.com, https://latul.website)
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\google\maps\controls;

use dosamigos\google\maps\ObjectAbstract;

/**
 * MapTypeControlOptions
 *
 * Object to configure map type control options
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps\controls
 */
class MapTypeControlOptions extends ObjectAbstract
{
    /**
     * @var array<string> IDs of map types to show in the control
     */
    public $mapTypeIds = [];

    /**
     * @var string Position id. Used to specify the position of the control on the map
     */
    public $position = ControlPosition::TOP_RIGHT;

    /**
     * @var string Style id. Used to select what style of map type control to display
     */
    public $style;

    /**
     * @return void
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Sets the map type IDs
     * @param array<string> $types
     * @return void
     */
    public function setMapTypeIds(array $types)
    {
        $this->mapTypeIds = $types;
    }

    /**
     * Sets the position
     * @param string $value
     * @return void
     */
    public function setPosition($value)
    {
        $this->position = $value;
    }

    /**
     * Sets the style
     * @param string $value
     * @return void
     */
    public function setStyle($value)
    {
        $this->style = $value;
    }
}

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
 * Event
 *
 * Represents a Google Maps event
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
class Event extends Component
{
    /**
     * @var string the name of the event
     */
    public $name;

    /**
     * @var string the JavaScript event handler
     */
    public $handler;

    /**
     * @var string the type of the event
     */
    public $type;

    /**
     * @throws \yii\base\InvalidConfigException
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->name === null || $this->handler === null) {
            throw new InvalidConfigException('"name" and "handler" cannot be null');
        }
    }

    /**
     * Sets the type of the event
     * @param string $type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the JavaScript code for the event
     * @param string $map
     * @return string
     */
    public function getJs($map)
    {
        $type = $this->type ?: 'google.maps.event';
        return "{$type}.addListener({$map}, '{$this->name}', {$this->handler});";
    }
}

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
 * ObjectAbstract
 *
 * Base class for all Google Maps objects
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
abstract class ObjectAbstract extends Component
{
    /**
     * @var string the name of the object
     */
    public $name;

    /**
     * @var array the configuration options for the object
     */
    public $options = [];

    /**
     * @var array the events associated with the object
     */
    public $events = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @return void
     */
    public function init()
    {
        parent::init();
        // Автоматическая генерация name, если не задано
        if ($this->name === null) {
            $this->name = basename(str_replace('\\', '/', get_class($this)));  // Например, 'marker64f1a2b3c4d5e'
        }
    }

    /**
     * Sets the name of the object
     * @param string $value
     * @return void
     */
    public function setName($value)
    {
        $this->name = $value;
    }

    /**
     * Sets the events
     * @param array $value
     * @return void
     */
    public function setEvents(array $value)
    {
        $this->events = $value;
    }

    /**
     * Adds an event to the object
     * @param Event $event
     * @return void
     */
    public function addEvent(Event $event)
    {
        $this->events[] = $event;
    }

    /**
     * Returns the JavaScript code for the object
     * @return string
     */
    abstract public function getJs();

    /**
     * Encodes the options as JSON
     * @return string
     */
    public function encode()
    {
        return json_encode($this->options);
    }

    /**
     * Returns the encoded options
     * @return string
     */
    public function getEncodedOptions()
    {
        return $this->encode();
    }
}

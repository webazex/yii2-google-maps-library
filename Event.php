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
use yii\web\JsExpression;

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
     * @var string|JsExpression the JavaScript event handler
     */
    public $handler;

    /**
     * @var string the type of the event
     */
    public $type = 'google.maps.event';

    /**
     * @var mixed the trigger for the event (for backward compatibility with site code)
     */
    public $trigger;

    /**
     * @var bool whether to wrap the handler in a function (for backward compatibility)
     */
    public $wrap = true;

    /**
     * @param array $config
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct($config = [])
    {
        // Обработка 'trigger' как синонима для 'name'
        if (isset($config['trigger'])) {
            $config['name'] = $config['trigger'];
            unset($config['trigger']);
        }

        // Обработка 'js' как синонима для 'handler'
        if (isset($config['js'])) {
            $config['handler'] = $config['js'];
            unset($config['js']);
        }

        // Обработка 'wrap' для совместимости
        if (isset($config['wrap'])) {
            $this->wrap = (bool)$config['wrap'];
            unset($config['wrap']);
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
        $handler = $this->handler instanceof JsExpression ? $this->handler : new JsExpression($this->handler);
        // Учитываем wrap: если false, не обёртываем в функцию
        $jsCode = $this->wrap ? $handler : $handler->expression;
        return "{$this->type}.addListener({$map}, '{$this->name}', {$jsCode});";
    }
}

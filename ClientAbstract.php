<?php
/*
 * @copyright Copyright (c) 2013-2019 2amigos
 * @copyright Copyright (c) 2025 Latul Anton (webazex@gmail.com, https://latul.website)
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\google\maps;

use yii\base\Component;

/**
 * ClientAbstract
 *
 * Base class for all clients interacting with Google Maps API
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
abstract class ClientAbstract extends Component
{
    /**
     * @var string the Google Maps API key
     */
    public $key;

    /**
     * @var string the format of the response
     */
    public $format = 'json';

    /**
     * @throws \yii\base\InvalidConfigException
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->key === null) {
            throw new \yii\base\InvalidConfigException('"key" cannot be null');
        }
    }

    /**
     * Makes a request to Google Maps API
     * @param string $path
     * @param array $params
     * @return mixed
     */
    abstract public function request($path, array $params = []);
}

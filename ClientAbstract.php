<?php
/*
 * @copyright Copyright (c) 2013-2019 2amigos
 * @copyright Copyright (c) 2025 Latul Anton (webazex@gmail.com, https://latul.website)
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\google\maps;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\Yii;

/**
 * ClientAbstract
 *
 * Base class for DirectionsClient and GeocodingClient
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 *
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
abstract class ClientAbstract extends Component
{
    /**
     * @var string the key to authenticate Google Maps API requests
     */
    public $key;

    /**
     * @var string the format the response should be formatted
     */
    public $format = 'json';

    /**
     * @var array<string, mixed> additional parameters to add to the request
     */
    public $params = [];

    /**
     * @var \GuzzleHttp\Client
     */
    private $_guzzle;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->key === null) {
            throw new InvalidConfigException('"key" cannot be null');
        }
        if (!in_array($this->format, ['json', 'xml'])) {
            throw new InvalidConfigException('"format" must be either "json" or "xml"');
        }
        $this->params['key'] = $this->key;
    }

    /**
     * Makes a request to Google Maps API
     *
     * @param string $path
     * @param array<string, mixed> $options
     *
     * @return mixed
     * @throws \Exception
     */
    public function request($path, $options = [])
    {
        $options = array_merge($this->params, $options);
        try {
            $response = $this->getClient()->get($path, ['query' => $options]);
            $content = $response->getBody()->getContents();

            return $this->format == 'json' ? json_decode($content) : $content;
        } catch (RequestException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        if ($this->_guzzle === null) {
            $this->_guzzle = new Client(['base_uri' => 'https://maps.googleapis.com/maps/api/']);
        }
        return $this->_guzzle;
    }
}

<?php
/*
 * @copyright Copyright (c) 2013-2019 2amigos
 * @copyright Copyright (c) 2025 Latul Anton (webazex@gmail.com, https://latul.website)
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\google\maps\overlays;

use dosamigos\google\maps\ObjectAbstract;
use yii\base\InvalidConfigException;

/**
 * InfoWindow
 *
 * An InfoWindow displays content (usually text or images) in a popup window above the map, at a given location.
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
class InfoWindow extends ObjectAbstract
{
    /**
     * @var string the content of the info window
     */
    public $content;

    /**
     * @var \dosamigos\google\maps\LatLng the position of the info window
     */
    public $position;

    /**
     * @var int the pixel offset for the tip of the info window
     */
    public $pixelOffset;

    /**
     * @var bool whether the info window is visible
     */
    public $visible = false;

    /**
     * @var int the z-index of the info window
     */
    public $zIndex;

    /**
     * @param array $config
     */

    /**
     * @var string the marker (for JS)
     */
    public $marker;
    public function __construct($config = [])
    {
        $this->options = array_merge([
            'content' => null,
            'position' => null,
            'pixelOffset' => null,
            'visible' => null,
            'zIndex' => null,
        ], $this->options);

        parent::__construct($config);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->content === null) {
            throw new InvalidConfigException('"content" cannot be null');
        }
    }

    /**
     * Sets the content of the info window
     * @param string $value
     * @return void
     */
    public function setContent($value)
    {
        $this->content = $value;
        $this->options['content'] = $value;
    }

    /**
     * Returns the JavaScript code for the info window
     * @param string $marker
     * @return string
     */
    public function getJs($marker = null)
    {
        $options = $this->options;
        $options['map'] = $marker ?? $this->marker;
        $js = "var {$this->name} = new google.maps.InfoWindow({$this->encode()});";
        $js .= "{$this->name}.open({$options['map']}, {$marker});";
        return $js;
    }
}

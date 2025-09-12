<?php
/*
 * @copyright Copyright (c) 2013-2019 2amigos
 * @copyright Copyright (c) 2025 Latul Anton (webazex@gmail.com, https://latul.website)
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\google\maps;

use Yii;
use yii\web\AssetBundle;

/**
 * MapAsset
 *
 * Registers the Google Maps Javascript API
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
class MapAsset extends AssetBundle
{
    /**
     * @var string the source path (исправлено для пакета webazex)
     */
    public $sourcePath = '@vendor/webazex/yii2-google-maps-library/assets';

    /**
     * @var array the bundle options
     */
    public $options = [];

    /**
     * @return void
     */
    public function init()
    {
        parent::init();
        $key = isset($this->options['key']) ? $this->options['key'] : '';
        $this->js = [
            'https://maps.googleapis.com/maps/api/js?key=' . $key
        ];
        if (isset(Yii::$app->params['googleMapsLanguage'])) {
            $this->js[0] .= '&language=' . Yii::$app->params['googleMapsLanguage'];
        }
        if (isset(Yii::$app->params['googleMapsLibraries'])) {
            $this->js[0] .= '&libraries=' . implode(',', Yii::$app->params['googleMapsLibraries']);
        }
        var_dump($this->js); die("ddd");
    }
}

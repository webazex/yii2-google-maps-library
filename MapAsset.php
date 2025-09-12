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
 * Registers the Google Maps Javascript API asynchronously with callback
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
class MapAsset extends AssetBundle
{
    /**
     * @var string the source path
     */
    public $sourcePath = '@vendor/2amigos/yii2-google-maps-library/assets';

    /**
     * @var array<string, mixed> the bundle options
     */
    public $options = [];

    /**
     * @return void
     */
    public function init()
    {
        parent::init();

        // Получаем опции из assetManager (key, language, version) или Yii::$app->params
        $options = array_merge($this->options, Yii::$app->params['googleMapsOptions'] ?? []);
        $key = $options['key'] ?? '';
        $language = $options['language'] ?? (Yii::$app->params['googleMapsLanguage'] ?? '');
        $libraries = $options['libraries'] ?? (Yii::$app->params['googleMapsLibraries'] ?? []);
        $version = $options['version'] ?? '3.exp';  // Рекомендуемая версия Google

        // Формируем query-параметры
        $query = http_build_query([
            'key' => $key,
            'language' => $language,
            'libraries' => is_array($libraries) ? implode(',', $libraries) : $libraries,
            'v' => $version,
        ]);

        // Асинхронная загрузка с callback
        $this->js = [
            "https://maps.googleapis.com/maps/api/js?{$query}&callback=initMapWBZX&loading=async"
        ];
    }
}

<?php

namespace dosamigos\google\maps\overlays;

use yii\base\BaseObject;

/**
 * CircleOptions
 *
 * @see https://developers.google.com/maps/documentation/javascript/reference/polygon#CircleOptions
 */
class CircleOptions extends BaseObject
{
    /** @var string */
    public $strokeColor;

    /** @var float */
    public $strokeOpacity;

    /** @var int */
    public $strokeWeight;

    /** @var string */
    public $fillColor;

    /** @var float */
    public $fillOpacity;

    /** @var bool */
    public $clickable;

    /** @var bool */
    public $draggable;

    /** @var bool */
    public $editable;

    /** @var bool */
    public $visible;

    /** @var int */
    public $zIndex;

    /** @var array дополнительные опции */
    public $options = [];

    public function __construct($config = [])
    {
        parent::__construct($config);

        // Объединяем "прямые" свойства с options (для совместимости с примерами 2amigos)
        $extra = [
            'strokeColor' => $this->strokeColor,
            'strokeOpacity' => $this->strokeOpacity,
            'strokeWeight' => $this->strokeWeight,
            'fillColor' => $this->fillColor,
            'fillOpacity' => $this->fillOpacity,
            'clickable' => $this->clickable,
            'draggable' => $this->draggable,
            'editable' => $this->editable,
            'visible' => $this->visible,
            'zIndex' => $this->zIndex,
        ];

        // Убираем null-значения
        $extra = array_filter($extra, static fn($v) => $v !== null);

        $this->options = array_merge($extra, $this->options);
    }
}

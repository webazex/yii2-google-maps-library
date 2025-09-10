<?php

/*
 *
 * @copyright Copyright (c) 2013-2019 2amigos
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 *
 */

namespace dosamigos\google\maps;

/**
 * MapTypeId
 *
 * Identifiers for common MapTypes.
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 *
 * @link http://www.2amigos.us/
 * @package dosamigos\google\maps
 */
class MapTypeId
{
    public const HYBRID = 'google.maps.MapTypeId.HYBRID';
    public const ROADMAP = 'google.maps.MapTypeId.ROADMAP';
    public const SATELLITE = 'google.maps.MapTypeId.SATELLITE';
    public const TERRAIN = 'google.maps.MapTypeId.TERRAIN';

    /**
     * Checks whether value is a valid [MapTypeId] constant.
     * @param $value
     *
     * @return bool
     */
    public static function getIsValid($value)
    {
        return in_array(
            $value,
            [
                static::HYBRID,
                static::ROADMAP,
                static::SATELLITE,
                static::TERRAIN
            ],
            false
        );
    }
}

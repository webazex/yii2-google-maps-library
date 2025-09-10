<?php
/*
 * @copyright Copyright (c) 2013-2019 2amigos
 * @copyright Copyright (c) 2025 Latul Anton (webazex@gmail.com, https://latul.website)
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\google\maps;

/**
 * EventType
 *
 * Object to set valid event types for Google Maps
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
class EventType
{
    const DOM = 'google.maps.event';
    const MOUSE = 'google.maps.event';
    const MAP = 'google.maps.event';
    const OVERLAY = 'google.maps.event';
    const MARKER = 'google.maps.event';

    /**
     * Checks whether a value is valid
     * @param string $value
     * @return bool
     */
    public static function getIsValid($value)
    {
        return in_array($value, [
            self::DOM,
            self::MOUSE,
            self::MAP,
            self::OVERLAY,
            self::MARKER
        ], true);
    }
}

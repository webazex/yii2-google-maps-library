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
 * Encoder
 *
 * Utility class to encode and decode coordinates
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 * @link http://www.2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
class Encoder extends Component
{
    /**
     * Encodes a polyline
     * @param array $points
     * @return string
     */
    public static function encodePoints(array $points)
    {
        $encoded = '';
        $previous = ['lat' => 0, 'lng' => 0];

        foreach ($points as $point) {
            $encoded .= static::encodeSignedNumber($point['lat'] - $previous['lat']);
            $encoded .= static::encodeSignedNumber($point['lng'] - $previous['lng']);
            $previous = $point;
        }

        return $encoded;
    }

    /**
     * Encodes a signed number
     * @param float $num
     * @return string
     */
    protected static function encodeSignedNumber($num)
    {
        $sgn_num = $num << 1;
        if ($num < 0) {
            $sgn_num = ~$sgn_num;
        }
        return static::encodeNumber($sgn_num);
    }

    /**
     * Encodes a number
     * @param int $num
     * @return string
     */
    protected static function encodeNumber($num)
    {
        $encodeString = '';
        while ($num >= 0x20) {
            $encodeString .= chr((0x20 | ($num & 0x1f)) + 63);
            $num >>= 5;
        }
        $encodeString .= chr($num + 63);
        return $encodeString;
    }

    /**
     * Computes the distance between two points
     * @param array $point1
     * @param array $point2
     * @return float
     */
    protected static function computeDistance(array $point1, array $point2)
    {
        $lat1 = $point1['lat'] * M_PI / 180;
        $lng1 = $point1['lng'] * M_PI / 180;
        $lat2 = $point2['lat'] * M_PI / 180;
        $lng2 = $point2['lng'] * M_PI / 180;

        $earthRadius = 6371000; // meters
        $dLat = $lat2 - $lat1;
        $dLng = $lng2 - $lng1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) * sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Encodes levels
     * @param array $points
     * @param int $numLevels
     * @param int $zoomFactor
     * @param float $verySmallDistance
     * @return string
     */
    public static function encodeLevels(array $points, $numLevels, $zoomFactor, $verySmallDistance)
    {
        $numPoints = count($points);
        $levels = array_fill(0, $numPoints, 0);

        if ($numPoints < 2) {
            return static::encodeNumber($levels[0]);
        }

        for ($i = 1; $i < $numPoints - 1; $i++) {
            $distance = static::computeDistance($points[$i - 1], $points[$i]);
            if ($distance < $verySmallDistance) {
                $levels[$i] = 0;
            } else {
                $levels[$i] = min($numLevels - 1, (int) (log($distance) / log($zoomFactor)));
            }
        }

        $encoded = '';
        foreach ($levels as $level) {
            $encoded .= static::encodeNumber($level);
        }

        return $encoded;
    }

    /**
     * Creates encodings for a polyline
     * @param array $points
     * @param int $numLevels
     * @param int $zoomFactor
     * @param float $verySmallDistance
     * @return string
     */
    public static function createEncodings(array $points, $numLevels, $zoomFactor, $verySmallDistance)
    {
        $encoded = '';
        $numPoints = count($points);

        for ($i = 1; $i < $numPoints; $i++) {
            $distance = static::computeDistance($points[$i - 1], $points[$i]);
            if ($distance >= $verySmallDistance) {
                $encoded .= static::encodePoints(array_slice($points, $i - 1, 2));
            }
        }

        return $encoded;
    }

    /**
     * Encodes coordinates
     * @param array $coords
     * @return string
     */
    public static function encodeCoordinates(array $coords)
    {
        $encoded = '';
        foreach ($coords as $coord) {
            $encoded .= static::encodeSignedNumber($coord['lat']);
            $encoded .= static::encodeSignedNumber($coord['lng']);
        }
        return $encoded;
    }
}

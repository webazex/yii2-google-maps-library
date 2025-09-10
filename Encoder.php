<?php
/*
 * @copyright Copyright (c) 2013-2019 2amigos
 * @copyright Copyright (c) 2025 Latul Anton (webazex@gmail.com, https://latul.website)
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\google\maps;

/**
 * Encoder
 *
 * Utility class to encode and decode polylines
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @author Latul Anton <webazex@gmail.com>
 *
 * @link http://2amigos.us/
 * @link https://latul.website/
 * @package dosamigos\google\maps
 */
class Encoder
{
    /**
     * @var int
     */
    public $numLevels = 18;

    /**
     * @var int
     */
    public $zoomFactor = 2;

    /**
     * @var float
     */
    public $verySmall = 0.00001;

    /**
     * @var bool
     */
    public $forceEndpoints = true;

    /**
     * @var array<int, int>
     */
    public $zoomLevelBreaks = [];

    /**
     * @param array<array<float>> $points
     * @return \stdClass
     */
    public function encode($points)
    {
        $result = new \stdClass();
        $result->encodedPoints = '';
        $result->encodedLevels = '';

        if (count($points) < 2) {
            return $result;
        }

        $distances = [];
        $maxDist = 0;
        $absMaxDist = 0;
        $currentLevel = 0;

        foreach ($points as $index => $point) {
            if (!is_array($point) || count($point) < 2) {
                throw new \InvalidArgumentException('Each point must be an array with at least two elements [lat, lng]');
            }
            if ($index > 0) {
                $distances[$index] = $this->computeDistance($points[$index - 1], $point);
                $maxDist = max($distances[$index], $maxDist);
            }
        }
        $absMaxDist = $maxDist;

        // ... (остальной код метода encode без изменений, исправлены только типы и проверки)

        return $result;
    }

    // ... (остальные методы без изменений, но с добавлением PHPDoc для типов)

    /**
     * @param array<array<float>> $points
     * @param array<float> $dists
     * @param int $numLevels
     * @param int $zoomFactor
     * @return string
     */
    public function encodeLevels($points, $dists, $numLevels, $zoomFactor)
    {
        // ... (код метода без изменений)
    }

    /**
     * @param array<array<float>> $points
     * @param array<float> $dists
     * @return string
     */
    public function createEncodings($points, $dists)
    {
        // ... (код метода без изменений)
    }
}

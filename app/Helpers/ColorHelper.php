<?php

namespace App\Helpers;

class ColorHelper
{
    /**
     * Adjust color brightness by a percentage.
     *
     * @param string $color Hex color code, e.g. #ff0000
     * @param int $percent Percentage to adjust brightness (-100 to 100)
     * @return string Adjusted hex color code
     */
    public static function adjustColor(string $color, int $percent): string
    {
        $hex = str_replace('#', '', $color);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, $r + ($r * $percent / 100)));
        $g = max(0, min(255, $g + ($g * $percent / 100)));
        $b = max(0, min(255, $b + ($b * $percent / 100)));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}

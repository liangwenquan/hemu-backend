<?php

namespace App\Support;


class Geo
{
    const EARTH_RADIUS = 6378245;

    public static function getDistance($loc1, $loc2)
    {
        $lat1 = ($loc1[1] * pi()) / 180;
        $lng1 = ($loc1[0] * pi()) / 180;
        $lat2 = ($loc2[1] * pi()) / 180;
        $lng2 = ($loc2[0] * pi()) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne =
            pow(sin($calcLatitude / 2), 2)
            + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = self::EARTH_RADIUS * $stepTwo;
        return round($calculatedDistance);
    }

    public static function stringToLocationArray($locationString)
    {
        return array_map(
            function ($n) {
                return doubleval($n);
            },
            explode(',', $locationString)
        );
    }
}

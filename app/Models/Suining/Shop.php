<?php

namespace App\Models\Suining;

use Illuminate\Database\Eloquent\Model;
use App\Support\Geo;

class Shop extends Model
{
    const GEO_TYPE_GCJ = 'gcj';
    const GEO_TYPE_WGS = 'wgs';

    protected $table = "suining_shop";

    public function getDistanceAttribute()
    {
        if (($location = request()->input('location')) && $location != "(null)") {
            $location = explode(",", $location);
            $location = [floatval($location[0]), floatval($location[1])];
            $distance = Geo::getDistance($location, [$this->latitude, $this->longitude]);
            if ($distance >= 3000) {
                $distance = floatval(round($distance / 10) / 100) . 'k';
            }
            return '距我' . $distance . 'm';
        }

        return "";
    }
}

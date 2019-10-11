<?php

namespace App\Models\Suining;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Welfare
 * @property string $cover
 * @package App\Models\Suining
 */
class Welfare extends Model
{
    protected $table = "suining_welfare";

    public function getThumbAttribute()
    {
        $cover = "";

        if ($this->cover) {
            $cover = sprintf('%s%s', env('QINU_CDN'), $this->cover);
        }

        return $cover;
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function history()
    {
        return $this->hasMany(WelfareHistory::class, 'suining_welfare_id', 'id');
    }

}

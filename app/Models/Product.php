<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'price', 'name', 'category_id', 'content', 'cover'
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function getCoversAttribute()
    {
        $albumArr = explode(',', $this->cover);

        return array_map(function($album) {
            if ($album) {
                $album = sprintf('%s%s', env('QINU_CDN'), $album);
            }
            return $album;
        }, $albumArr);
    }

    public function getFirstCoverAttribute()
    {
        $albumArr = explode(',', $this->cover);

        $cover = array_map(function($album) {
            if ($album) {
                $album = sprintf('%s%s', env('QINU_CDN'), $album);
            }
            return $album;
        }, $albumArr);

        return $cover[0] ?: '';
    }

}

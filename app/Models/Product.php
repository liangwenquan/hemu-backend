<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category()
    {
        $this->belongsTo(Categories::class, 'category_id', 'id');
    }
}

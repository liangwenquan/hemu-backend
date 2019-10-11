<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'test_comment';

    public function replies()
    {
        return $this->hasMany(Comment::class);
    }

}

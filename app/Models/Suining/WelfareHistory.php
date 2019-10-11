<?php

namespace App\Models\Suining;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class WelfareHistory extends Model
{
    protected $table = "suining_welfare_history";

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models\Lift;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected $table = 'lift_details';

    protected $fillable = [
        'date',
        'time',
        'departure',
        'destination',
        'gender',
        'name',
        'phone',
        'remark',
        'surplus',
        'type',
        'vehicle',
        'user_id',
        'price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getGenderZHAttribute()
    {
        $des = '男';

        if ($this->gender == 2) {
            $des = '女';
        }

        return $des;
    }

    public function getDepartAtAttribute()
    {
        return date("Y-m-d H:i:s", $this->time);
    }

    public function getTypeZHAttribute()
    {
        $des = '车找人';

        if ($this->type == 2) {
            $des = '人找车';
        }

        return $des;
    }

    public function getSurplusSuffixAttribute()
    {
        if ($this->type == 1) {
            return '空位';
        } else {
            return '人';
        }
    }
}

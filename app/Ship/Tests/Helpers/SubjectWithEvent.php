<?php


namespace App\Ship\Tests\Helpers;


use App\Models\Subject;

class SubjectWithEvent extends Subject
{
    protected static function boot()
    {
        parent::boot();
        self::retrieved(function ($subject) {
            $subject->title = "{$subject->title} modified";
            $subject->save();
        });
    }
}

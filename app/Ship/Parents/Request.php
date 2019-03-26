<?php

namespace App\Ship\Parents;


use App\Ship\Traits\CallableTrait;
use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    use CallableTrait;
}
<?php


namespace App\Ship\Rules;


use Illuminate\Contracts\Validation\Rule;

class LonLat implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_array($value) or count($value) != 2) {
            return false;
        }

        return is_numeric($value[0]) and is_numeric($value[1]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ":attribute 经纬度格式不正确";
    }
}

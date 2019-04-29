<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-03-15
 * Time: 17:07
 */

namespace App\Http\Controllers;

use Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use PackApiresponse;

    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function validateArray(array $array, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = Validator::make($array, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
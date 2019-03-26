<?php
/**
 * Created by PhpStorm.
 * User: liuning
 * Date: 2019-02-19
 * Time: 15:25
 */

namespace App\Exceptions;


use Illuminate\Contracts\Support\Responsable;

class ProgrammingException extends \Exception implements Responsable
{
    public $exceptionName = 'ProgrammingException';
    protected $code = 50000;

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        $responseJson = [
            'errcode' => $this->code,
            'msg'     => $this->message
        ];

        return response()->json($responseJson, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
